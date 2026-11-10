<?php
declare(strict_types=1);

function h(mixed $value): string { return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8'); }
function rupiah(int|float|string $value): string { return 'Rp ' . number_format((float)$value, 0, ',', '.'); }
function current_role(): string { return (string)($_SESSION['role'] ?? 'guest'); }
function current_name(): string { return (string)($_SESSION['name'] ?? 'Pengunjung'); }
function current_email(): string { return (string)($_SESSION['email'] ?? ''); }
function redirect_to(string $page): never { header('Location: ?page=' . rawurlencode($page)); exit; }
function set_flash(string $message, string $type='success'): void { $_SESSION['flash'] = ['message'=>$message, 'type'=>$type]; }
function pull_flash(): ?array { $flash=$_SESSION['flash'] ?? null; unset($_SESSION['flash']); return is_array($flash) ? $flash : null; }
function csrf_field(): string { return '<input type="hidden" name="csrf" value="'.h($_SESSION['csrf'] ?? '').'">'; }
function verify_csrf(): void {
    $sent=(string)($_POST['csrf'] ?? ''); $known=(string)($_SESSION['csrf'] ?? '');
    if ($sent === '' || $known === '' || !hash_equals($known, $sent)) {
        http_response_code(419); exit('Sesi formulir kedaluwarsa. Muat ulang halaman lalu coba lagi.');
    }
}
function status_badge(string $status): string {
    $class=match(strtolower($status)) {
        'selesai','lunas','aktif','disetujui','tersedia'=>'green',
        'dikirim','disewa','terbit'=>'blue',
        'belum lunas','menunggu penawaran','draft','menunggu'=>'orange',
        'diproses','menunggu dp'=>'purple',
        'dibatalkan','ditolak','rusak'=>'red', default=>'gray'
    };
    return '<span class="badge '.$class.'">'.h($status).'</span>';
}
function page_meta(string $page): array {
    $all=[
        'dashboard'=>['Dashboard Admin','Ringkasan operasional dan aktivitas ScaffoldSync hari ini.'],
        'barang'=>['Inventaris Barang','Kelola stok, kategori, dan harga sewa scaffolding.'],
        'pelanggan'=>['Pelanggan','Kelola data pelanggan dan perusahaan kontraktor.'],
        'pesanan'=>['Pesanan','Periksa pengajuan dan status penyewaan.'],
        'proyek'=>['Proyek','Pantau lokasi proyek dan riwayat penyewaan.'],
        'jadwal'=>['Jadwal Pengiriman & Pengambilan','Atur penugasan dan jadwal operasional lapangan.'],
        'dokumen'=>['Dokumen','Kelola penawaran, surat jalan, invoice, dan pembayaran.'],
        'laporan'=>['Laporan Omzet','Ringkasan pendapatan dan performa penyewaan.'],
        'users'=>['Manajemen Pengguna','Atur akses akun Admin, Owner, dan Mandor.'],
        'katalog'=>['Katalog Scaffolding','Temukan barang sesuai kebutuhan proyek.'],
        'keranjang'=>['Keranjang','Atur barang dan periode penyewaan.'],
        'ajukan'=>['Pengajuan Proyek & Sewa','Lengkapi tanggal sewa, alamat, dan catatan akses truk.'],
        'sewa'=>['Daftar Sewa Saya','Pantau proses penawaran, pembayaran, dan penyewaan.'],
        'detail-sewa'=>['Detail Sewa & Status','Lihat perjalanan pesanan, invoice, dan pembayaran.'],
        'profil'=>['Profil Pelanggan','Kelola informasi akun dan lokasi proyek tersimpan.'],
        'bantuan'=>['Pusat Bantuan','Temukan jawaban dan hubungi Admin ScaffoldSync.'],
        'reset'=>['Reset Kata Sandi','Minta tautan pengaturan ulang kata sandi.'],
        'register'=>['Daftar Pelanggan','Buat akun untuk mengajukan kebutuhan penyewaan.'],
    ];
    [$title,$subtitle]=$all[$page] ?? $all['dashboard'];
    if (current_role()==='owner' && $page==='dashboard') $title='Dashboard Pemilik';
    return compact('title','subtitle');
}
function navigation_for(string $role): array {
    if ($role==='pelanggan') return [
        ['katalog','Katalog','▦'],['keranjang','Keranjang','🛒'],['sewa','Sewa Saya','▤'],['profil','Profil','◉'],['bantuan','Bantuan','?']
    ];
    if ($role==='owner') return [
        ['dashboard','Dashboard','▥'],['barang','Inventaris','⬡'],['pesanan','Pesanan','▣'],['dokumen','Dokumen','▤'],['laporan','Laporan Omzet','↗'],['users','Pengguna','♧']
    ];
    return [
        ['dashboard','Dashboard','▥'],['barang','Inventaris','⬡'],['pelanggan','Pelanggan','♙'],['pesanan','Pesanan','▣'],['proyek','Proyek','⌂'],['jadwal','Jadwal','▦'],['dokumen','Dokumen','▤'],['laporan','Laporan','↗'],['users','Pengguna','♧']
    ];
}
function resolve_page(string $requested): string {
    $pages=['login','reset','register','dashboard','barang','pelanggan','pesanan','proyek','jadwal','dokumen','laporan','users','katalog','keranjang','ajukan','sewa','detail-sewa','profil','bantuan'];
    $page=in_array($requested,$pages,true) ? $requested : (current_role()==='guest' ? 'login' : (current_role()==='pelanggan' ? 'katalog' : 'dashboard'));
    $role=current_role();
    if ($role==='guest' && !in_array($page,['login','reset','register'],true)) return 'login';
    if ($role==='pelanggan' && !in_array($page,['katalog','keranjang','ajukan','sewa','detail-sewa','profil','bantuan'],true)) return 'katalog';
    if ($role==='owner' && !in_array($page,['dashboard','barang','pesanan','dokumen','laporan','users'],true)) return 'dashboard';
    if ($role==='admin' && in_array($page,['katalog','keranjang','ajukan','sewa','detail-sewa','profil','bantuan'],true)) return 'dashboard';
    return $page;
}
function view_for(string $page, string $role): string {
    if (in_array($page,['login','reset','register'],true)) return __DIR__ . '/../Views/pages/auth/' . $page . '.php';
    if ($role==='pelanggan') return __DIR__ . '/../Views/pages/customer/' . $page . '.php';
    if ($role==='owner' && in_array($page,['laporan','users'],true)) return __DIR__ . '/../Views/pages/owner/' . $page . '.php';
    if (in_array($page,['laporan','users'],true)) {
        return __DIR__ . '/../Views/pages/owner/' . $page . '.php';
    }
    $map=['dashboard'=>'dashboard','barang'=>'barang','pelanggan'=>'pelanggan','pesanan'=>'pesanan','proyek'=>'proyek','jadwal'=>'jadwal','dokumen'=>'dokumen'];
    $mapped=$map[$page] ?? 'dashboard';
    return __DIR__ . '/../Views/pages/admin/' . $mapped . '.php';
}
