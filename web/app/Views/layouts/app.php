<!doctype html>
<html lang="id"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><meta name="theme-color" content="#397cac"><title><?= h($title) ?> · ScaffoldSync</title><link rel="stylesheet" href="assets/app.css"></head>
<body><div class="app-shell">
<?php require __DIR__ . '/../partials/sidebar.php'; ?>
<div class="main"><header class="topbar"><button class="mobile-menu" type="button" aria-label="Buka menu" onclick="toggleSidebar()">☰</button><form class="search-top" method="get"><input type="hidden" name="page" value="<?= h($page) ?>"><span>⌕</span><input name="q" placeholder="Cari barang, pelanggan, pesanan..." value="<?= h($_GET['q']??'') ?>"></form><div class="top-user"><div class="avatar small"><?= h(substr(current_name(),0,1)) ?></div><div><b><?= h(current_name()) ?></b><small><?= h(ucfirst(current_role())) ?></small></div><form method="post" class="logout-form"><?= csrf_field() ?><input type="hidden" name="action" value="logout"><button class="logout" title="Keluar">↪</button></form></div></header>
<main class="content">
<?php if ($flash): ?><div class="alert <?= h($flash['type']) ?>" role="status"><?= h($flash['message']) ?></div><?php endif; ?>
<div class="page-head"><div><h1><?= h($title) ?></h1><p><?= h($subtitle) ?></p></div><?php if ($page==='barang'): ?><button class="btn primary" onclick="openModal('modalBarang')">＋ Tambah Barang</button><?php elseif ($page==='pelanggan'): ?><button class="btn primary" onclick="openModal('modalPelanggan')">＋ Tambah Pelanggan</button><?php elseif ($page==='jadwal'): ?><button class="btn primary" onclick="openModal('modalJadwal')">＋ Jadwal Baru</button><?php elseif ($page==='pesanan' && current_role()==='admin'): ?><a class="btn primary" href="?page=ajukan">＋ Pesanan Baru</a><?php endif; ?></div>
<?php require $view; ?>
<?php require __DIR__ . '/../partials/modals.php'; ?>
<footer class="footer-note"><span>ScaffoldSync · Frontend prototype Semester 3</span><span>Dokumen acuan: BPMN · ERD · Use Case</span></footer>
</main></div></div><script src="assets/app.js" defer></script></body></html>
