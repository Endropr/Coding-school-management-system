<?php 

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if(!isset($_SESSION['login'])) {
    header("Location: ../index.php");
    exit;
}

$current_script = $_SERVER['PHP_SELF'];

if (strpos($current_script, '/profile/') !== false) {
    $prefix = '../';
} 
elseif (strpos($current_script, '/admin/') !== false) {
    $prefix = '../';
}

else {
    $prefix = '';
}


$menu = "";
if($_SESSION['role'] == "admin") {
    $menu .= '<li><a href="' . $prefix . '">👑 Админ панель</a></li>';
}
$menu .= '<li><a href="' . $prefix . 'profile/">👤 Личный кабинет</a></li>
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