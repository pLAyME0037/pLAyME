<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title ?? 'pLAyME Website'; ?></title>
    <link rel="stylesheet" href="../../css/style.css">
</head>
<body>
<?php require __DIR__ . '/partials/header.php'; ?> 
<div class="main-wrapper">
    <div class="content">
        <?php echo $content; ?>
    </div>
    <div class="sidebar">
        <?php require __DIR__ . '/partials/sidebar.php'; ?>
    </div>
</div>
<?php require __DIR__ . "/partials/footer.php"; ?>
</body>
</html>
