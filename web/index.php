<?php
declare(strict_types=1);
require __DIR__ . '/app/bootstrap.php';

$page = resolve_page((string)($_GET['page'] ?? ''));
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    handle_post_action($store);
}
if ($page === 'keranjang' && isset($_GET['add'])) {
    add_to_cart((string)$_GET['add'], $store);
    redirect_to('keranjang');
}
if ($page === 'hapus-keranjang' && isset($_GET['code'])) {
    unset($_SESSION['cart'][(string)$_GET['code']]);
    set_flash('Barang dihapus dari keranjang.');
    redirect_to('keranjang');
}

$role = current_role();
$meta = page_meta($page);
$title = $meta['title'];
$subtitle = $meta['subtitle'];
$menus = navigation_for($role);
$flash = pull_flash();
$view = view_for($page, $role);

if ($role === 'guest') {
    require __DIR__ . '/app/Views/layouts/guest.php';
    exit;
}
require __DIR__ . '/app/Views/layouts/app.php';
