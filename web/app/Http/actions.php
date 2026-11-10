<?php
declare(strict_types=1);

function handle_post_action(array &$store): void {
    verify_csrf();
    $action=(string)($_POST['action'] ?? '');
    if ($action==='login') {
        $accounts=[
            'admin@scaffoldsync.local'=>['role'=>'admin','name'=>'Rina Administrator'],
            'owner@scaffoldsync.local'=>['role'=>'owner','name'=>'Owner Pemilik'],
            'customer@scaffoldsync.local'=>['role'=>'pelanggan','name'=>'Budi Santoso'],
        ];
        $email=strtolower(trim((string)($_POST['email'] ?? '')));
        $password=(string)($_POST['password'] ?? '');
        $registered=$_SESSION['registered_accounts'][$email]??null;
        $validDemo=$password==='demo123' && isset($accounts[$email]);
        $validRegistered=is_array($registered) && password_verify($password,(string)$registered['password_hash']);
        if ($validDemo || $validRegistered) {
            session_regenerate_id(true);
            if ($validRegistered) { $_SESSION['role']='pelanggan'; $_SESSION['name']=$registered['name']; }
            else { $_SESSION['role']=$accounts[$email]['role']; $_SESSION['name']=$accounts[$email]['name']; }
            $_SESSION['email']=$email;
            set_flash('Berhasil masuk ke ScaffoldSync.');
            redirect_to($_SESSION['role']==='pelanggan' ? 'katalog' : 'dashboard');
        }
        set_flash('Email atau password tidak sesuai.','error'); redirect_to('login');
    }
    if ($action==='register') {
        $name=trim((string)($_POST['nama']??'')); $email=strtolower(trim((string)($_POST['email']??'')));
        $password=(string)($_POST['password']??''); $confirm=(string)($_POST['password_confirmation']??'');
        if ($name==='' || !filter_var($email,FILTER_VALIDATE_EMAIL) || strlen($password)<6 || $password!==$confirm) {
            set_flash('Periksa nama, email, dan password. Password minimal 6 karakter dan harus sama dengan konfirmasi.','error'); redirect_to('register');
        }
        $_SESSION['registered_accounts'][$email]=['name'=>$name,'password_hash'=>password_hash($password,PASSWORD_DEFAULT)];
        $store['pelanggan'][]=['nama'=>$name,'perusahaan'=>trim((string)($_POST['perusahaan']??'-')),'telepon'=>trim((string)($_POST['telepon']??'-')),'status'=>'Aktif'];
        session_regenerate_id(true); $_SESSION['role']='pelanggan'; $_SESSION['name']=$name; $_SESSION['email']=$email;
        set_flash('Pendaftaran berhasil. Selamat datang di ScaffoldSync.'); redirect_to('katalog');
    }
    if ($action==='logout') {
        $registeredAccounts=$_SESSION['registered_accounts']??[];
        $demoStore=$_SESSION['store']??null;
        $_SESSION=[]; session_regenerate_id(true);
        $_SESSION['csrf']=bin2hex(random_bytes(32));
        $_SESSION['registered_accounts']=$registeredAccounts;
        if (is_array($demoStore)) $_SESSION['store']=$demoStore;
        $_SESSION['cart']=[];
        set_flash('Kamu sudah keluar dari ScaffoldSync.'); redirect_to('login');
    }
    if (current_role()==='guest') redirect_to('login');
    $adminActions=['add_barang','update_barang','delete_barang','add_pelanggan','update_status','add_jadwal','create_document','mark_invoice_paid','reset_demo'];
    $ownerActions=['toggle_user'];
    $customerActions=['create_order','accept_quote','upload_payment','save_profile','update_qty','remove_cart'];
    if (in_array($action,$adminActions,true) && current_role()!=='admin') {
        set_flash('Aksi ini hanya tersedia untuk Admin.','error'); redirect_to('dashboard');
    }
    if (in_array($action,$ownerActions,true) && current_role()!=='owner') {
        set_flash('Aksi ini hanya tersedia untuk Pemilik.','error'); redirect_to('dashboard');
    }
    if (in_array($action,$customerActions,true) && current_role()!=='pelanggan') {
        set_flash('Aksi ini hanya tersedia untuk Pelanggan.','error'); redirect_to('dashboard');
    }
    switch ($action) {
        case 'add_barang':
            $kode=trim((string)($_POST['kode']??'')); $nama=trim((string)($_POST['nama']??''));
            if ($kode==='' || $nama==='') { set_flash('Kode dan nama barang wajib diisi.','error'); redirect_to('barang'); }
            foreach ($store['barang'] as $item) if (strtolower($item['kode'])===strtolower($kode)) { set_flash('Kode barang sudah digunakan.','error'); redirect_to('barang'); }
            $total=max(0,(int)($_POST['total']??0));
            $store['barang'][]=['kode'=>$kode,'nama'=>$nama,'kategori'=>trim((string)($_POST['kategori']??'Lainnya')),'harga'=>max(0,(int)($_POST['harga']??0)),'mingguan'=>max(0,(int)($_POST['mingguan']??0)),'total'=>$total,'tersedia'=>$total,'disewa'=>0,'rusak'=>0];
            set_flash('Barang berhasil ditambahkan ke data demo.'); redirect_to('barang');
        case 'update_barang':
            $code=(string)($_POST['kode']??'');
            foreach ($store['barang'] as &$item) {
                if ($item['kode']!==$code) continue;
                $item['nama']=trim((string)($_POST['nama']??$item['nama']));
                $item['kategori']=trim((string)($_POST['kategori']??$item['kategori']));
                $item['harga']=max(0,(int)($_POST['harga']??$item['harga']));
                $item['mingguan']=max(0,(int)($_POST['mingguan']??$item['mingguan']));
                $newTotal=max((int)$item['disewa']+(int)$item['rusak'],(int)($_POST['total']??$item['total']));
                $item['total']=$newTotal; $item['tersedia']=$newTotal-(int)$item['disewa']-(int)$item['rusak'];
                set_flash('Data barang diperbarui.'); break;
            }
            unset($item); redirect_to('barang');
        case 'delete_barang':
            $code=(string)($_POST['kode']??'');
            foreach ($store['barang'] as $i=>$item) {
                if ($item['kode']!==$code) continue;
                if ((int)$item['disewa']>0) { set_flash('Barang yang sedang disewa tidak dapat dihapus.','error'); redirect_to('barang'); }
                unset($store['barang'][$i]); $store['barang']=array_values($store['barang']); set_flash('Barang dihapus dari data demo.'); break;
            }
            redirect_to('barang');
        case 'add_pelanggan':
            $store['pelanggan'][]=['nama'=>trim((string)($_POST['nama']??'Pelanggan Baru')),'perusahaan'=>trim((string)($_POST['perusahaan']??'-')),'telepon'=>trim((string)($_POST['telepon']??'-')),'status'=>'Aktif'];
            set_flash('Pelanggan berhasil ditambahkan ke data demo.'); redirect_to('pelanggan');
        case 'update_status':
            $idx=(int)($_POST['index']??-1); $allowed=['Menunggu Penawaran','Diproses','Menunggu DP','Dikirim','Disewa','Selesai','Belum Lunas','Dibatalkan'];
            if (isset($store['pesanan'][$idx]) && in_array($_POST['status']??'', $allowed, true)) { $store['pesanan'][$idx]['status']=$_POST['status']; set_flash('Status pesanan diperbarui.'); }
            else set_flash('Perubahan status tidak valid.','error');
            redirect_to('pesanan');
        case 'add_jadwal':
            $store['jadwal'][]=['waktu'=>(string)($_POST['waktu']??'09:00'),'jenis'=>(string)($_POST['jenis']??'Pengiriman'),'barang'=>trim((string)($_POST['barang']??'Scaffolding')),'pelanggan'=>trim((string)($_POST['pelanggan']??'Pelanggan')),'proyek'=>trim((string)($_POST['proyek']??'Proyek')),'mandor'=>trim((string)($_POST['mandor']??'Belum ditugaskan'))];
            set_flash('Jadwal demo berhasil dibuat.'); redirect_to('jadwal');
        case 'create_document':
            $jenis=(string)($_POST['jenis']??'Penawaran');
            if (!in_array($jenis,['Penawaran','Surat Jalan','Invoice'],true)) { set_flash('Jenis dokumen tidak valid.','error'); redirect_to('dokumen'); }
            $prefix=match($jenis) {'Penawaran'=>'PW','Surat Jalan'=>'SJ','Invoice'=>'INV'};
            $next=count($store['dokumen'])+1;
            $status=match($jenis) {'Penawaran'=>'Draft','Surat Jalan'=>'Terbit','Invoice'=>'Belum Lunas'};
            $store['dokumen'][]=['kode'=>$prefix.'-'.str_pad((string)(12+$next),4,'0',STR_PAD_LEFT),'tanggal'=>date('d M Y'),'pelanggan'=>trim((string)($_POST['pelanggan']??'Pelanggan Baru')),'jenis'=>$jenis,'status'=>$status];
            set_flash($jenis.' demo berhasil dibuat.'); redirect_to('dokumen');
        case 'mark_invoice_paid':
            $code=(string)($_POST['kode']??''); $updated=false;
            foreach ($store['dokumen'] as &$document) {
                if ($document['kode']===$code && $document['jenis']==='Invoice') { $document['status']='Lunas'; $updated=true; break; }
            }
            unset($document); set_flash($updated?'Pembayaran invoice dicatat sebagai lunas.':'Invoice tidak ditemukan.',$updated?'success':'error'); redirect_to('dokumen');
        case 'create_order':
            $start=(string)($_POST['mulai']??''); $end=(string)($_POST['selesai']??''); $location=trim((string)($_POST['lokasi']??''));
            if ($location==='' || $start==='' || $end==='' || $end<$start) { set_flash('Lokasi dan rentang tanggal yang valid wajib diisi.','error'); redirect_to('ajukan'); }
            $count=count($store['pesanan']); $code='PS-'.str_pad((string)(13+$count),4,'0',STR_PAD_LEFT);
            $store['pesanan'][]=['kode'=>$code,'pelanggan'=>'Budi Santoso','proyek'=>$location,'tanggal'=>date('d M Y'),'durasi'=>date('d M Y',strtotime($start)).' – '.date('d M Y',strtotime($end)),'status'=>'Menunggu Penawaran'];
            $_SESSION['last_order']=['kode'=>$code,'lokasi'=>$location,'mulai'=>$start,'selesai'=>$end,'catatan'=>trim((string)($_POST['catatan']??''))];
            $_SESSION['cart']=[]; set_flash('Pengajuan berhasil dibuat. Admin perlu memeriksa stok dan jadwal.'); redirect_to('sewa');
        case 'accept_quote':
            set_flash('Persetujuan penawaran dicatat pada prototype.'); redirect_to('detail-sewa');
        case 'upload_payment':
            set_flash('Bukti pembayaran diterima dalam simulasi. Upload permanen perlu API backend.'); redirect_to('detail-sewa');
        case 'save_profile':
            $_SESSION['name']=trim((string)($_POST['nama']??current_name())); set_flash('Profil demo diperbarui.'); redirect_to('profil');
        case 'update_qty':
            $code=(string)($_POST['code']??''); $qty=max(1,min(999,(int)($_POST['qty']??1)));
            if (isset($_SESSION['cart'][$code])) $_SESSION['cart'][$code]=$qty;
            set_flash('Jumlah barang diperbarui.'); redirect_to('keranjang');
        case 'remove_cart':
            unset($_SESSION['cart'][(string)($_POST['code']??'')]); set_flash('Barang dihapus dari keranjang.'); redirect_to('keranjang');
        case 'toggle_user':
            $idx=(int)($_POST['index']??-1);
            if (isset($store['users'][$idx])) { $store['users'][$idx]['status']=$store['users'][$idx]['status']==='Aktif'?'Nonaktif':'Aktif'; set_flash('Status pengguna diperbarui pada data demo.'); }
            redirect_to('users');
        case 'reset_demo':
            unset($_SESSION['store'],$_SESSION['cart']); $_SESSION['store']=require dirname(__DIR__,2).'/data/demo.php'; $_SESSION['cart']=[]; set_flash('Data demo dikembalikan ke data awal.'); redirect_to('dashboard');
    }
    set_flash('Aksi belum tersedia pada prototype.','error');
    redirect_to('dashboard');
}
function add_to_cart(string $code, array $store): void {
    foreach ($store['barang'] as $item) {
        if ($item['kode']===$code) {
            if ((int)$item['tersedia']<=0) { set_flash('Stok barang tidak tersedia.','error'); return; }
            $_SESSION['cart'][$code]=min((int)$item['tersedia'],(int)($_SESSION['cart'][$code]??0)+1);
            set_flash('Barang ditambahkan ke keranjang.'); return;
        }
    }
    set_flash('Barang tidak ditemukan.','error');
}
