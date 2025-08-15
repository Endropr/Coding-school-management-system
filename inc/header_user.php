<?php 
// header_user.php - для обычных пользователей (не админов)

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Проверяем, авторизован ли пользователь
if(!isset($_SESSION['login'])) {
    // Если не авторизован, редиректим на главную
    header("Location: ../index.php");
    exit;
}

// Для пользователей всегда используем ../ для выхода из admin/controllers/vehicles/
$prefix = '../../../'; // Из admin/controllers/vehicles/ в корень

$menu = '<li><a href="' . $prefix . 'profile/">👤 Личный кабинет</a></li>
         <li><a href="' . $prefix . 'controllers/logout.php">🚪 Выйти</a></li>';
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Автосервис "Мастерская"</title>
    <link rel="stylesheet" href="<?php echo $prefix; ?>assets/style/style.css">
</head>
<body>
    <div class="header">
        <a href="<?php echo $prefix; ?>index.php">🚗 Автосервис "Мастерская"</a>
        <ul>
            <?= $menu ?>
        </ul>
    </div>