<!doctype html>
<html lang="id"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><title><?= h($title) ?> · ScaffoldSync</title><link rel="stylesheet" href="assets/app.css"></head>
<body class="guest-body">
<?php if ($flash): ?><div class="guest-alert alert <?= h($flash['type']) ?>" role="status"><?= h($flash['message']) ?></div><?php endif; ?>
<?php require __DIR__ . '/../pages/auth/' . basename($view); ?>
<script src="assets/app.js" defer></script></body></html>
