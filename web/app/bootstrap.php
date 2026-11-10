<?php
declare(strict_types=1);

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_name('scaffoldsync_session');
    session_start();
}
require_once __DIR__ . '/Support/helpers.php';
require_once __DIR__ . '/Http/actions.php';

if (!isset($_SESSION['csrf'])) {
    $_SESSION['csrf'] = bin2hex(random_bytes(32));
}
if (!isset($_SESSION['store'])) {
    $_SESSION['store'] = require dirname(__DIR__) . '/data/demo.php';
}
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}
$store =& $_SESSION['store'];
