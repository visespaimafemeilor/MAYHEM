<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tumbleweed</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/style.css">
</head>
<body>

<?php require __DIR__ . '/components/navbar.php'; ?>

<main class="container">
    <?php if (isset($content) && file_exists($content)): ?>
        <?php require $content; ?>
    <?php endif; ?>
</main>

<?php require __DIR__ . '/components/footer.php'; ?>

<script>var BASE_URL = '<?= BASE_URL ?>';</script>
<script src="<?= BASE_URL ?>/assets/js/main.js"></script>
</body>
</html>
