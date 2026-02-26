<?php 
// header.php - для авторизованных пользователей

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Проверяем, авторизован ли пользователь
if(!isset($_SESSION['login'])) {
    // Если не авторизован, редиректим на главную
    header("Location: ../index.php");
    exit;
}

// Определяем, откуда вызывается файл
$current_script = $_SERVER['PHP_SELF'];

// Если находимся в папке profile/
if (strpos($current_script, '/profile/') !== false) {
    $prefix = '../';
} 
// Если находимся в папке admin/
elseif (strpos($current_script, '/admin/') !== false) {
    $prefix = '../';
}
// Если находимся в корне или другой папке
else {
    $prefix = '';
}

// ДЛЯ ОТЛАДКИ (можно удалить после проверки)
echo "<!-- DEBUG: current_script = $current_script -->";
echo "<!-- DEBUG: prefix = $prefix -->";

$menu = "";
if($_SESSION['role'] == "admin") {
    $menu .= '<li><a href="' . $prefix . 'admin/">🧿 Админ панель</a></li>';
}
$menu .= '<li><a href="' . $prefix . 'profile/">🧿 Личный кабинет</a></li>
          <li><a href="' . $prefix . 'admin/controllers/logout.php">🧿 Выйти</a></li>';
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CodeAcademy</title>
    <link rel="stylesheet" href="<?php echo $prefix; ?>assets/style/style.css">
</head>
<body>
    <div class="header">
        <!-- Ссылка на главную страницу -->
        <a href="<?php echo $prefix; ?>index.php">🔹CodeAcademy</a>
        <ul>
            <?= $menu ?>
        </ul>
    </div>