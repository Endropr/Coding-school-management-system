<?php
// header_start.php - для неавторизованных пользователей

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Определяем, откуда вызывается файл
$current_script = $_SERVER['PHP_SELF'];

// Если находимся в папке auth/
if (strpos($current_script, '/auth/') !== false) {
    $prefix = '../';
} 
// Если находимся в папке register/
elseif (strpos($current_script, '/register/') !== false) {
    $prefix = '../';
}
// Если находимся в корне
else {
    $prefix = '';
}

// ДЛЯ ОТЛАДКИ
echo "<!-- DEBUG header_start: current_script = $current_script -->";
echo "<!-- DEBUG header_start: prefix = $prefix -->";

$menu = '<li><a href="' . $prefix . 'auth/">🧿 Авторизация</a></li>
         <li><a href="' . $prefix . 'register/">🧿 Регистрация</a></li>';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CodeAcademy</title>
    <link rel="stylesheet" href="<?php echo $prefix; ?>assets/style/style.css">
</head>
<body>
    <header>
       <div class="header">
    <!-- Проверяем текущую страницу и выбираем правильный путь -->
    <?php 
    $current_page = $_SERVER['PHP_SELF'];
    
    // Определяем путь к главной
    if (strpos($current_page, '/profile/') !== false || 
        strpos($current_page, '/admin/') !== false) {
        // Мы в profile/ или admin/ - нужно выйти на уровень выше
        $home_link = '../index.php';
    } elseif (strpos($current_page, '/auth/') !== false ||
              strpos($current_page, '/register/') !== false) {
        // Мы в auth/ или register/ - нужно выйти на уровень выше
        $home_link = '../index.php';
    } else {
        // Мы в корне
        $home_link = 'index.php';
    }
    ?>   <a href="<?php echo $home_link; ?>">🔹CodeAcademy</a>
    <ul>
        <?= $menu ?>
    </ul>
</div>
    </header>
</body>
</html>
