<?php
session_start();

// Включаем подключение к БД
include __DIR__ . "/../function/connect.php";

// Проверка, что все обязательные поля заполнены
$required_fields = ['surname', 'name', 'patronymic', 'login', 'email', 'phone', 'password', 'password-repeat'];
foreach ($required_fields as $field) {
    if (empty($_POST[$field])) {
        header("Location: ../../register/index.php?error=empty_fields");
        exit;
    }
}

// Проверка совпадения паролей
if ($_POST['password'] !== $_POST['password-repeat']) {
    header("Location: ../../register/index.php?error=password_mismatch");
    exit;
}

// Проверка длины пароля
if (strlen($_POST['password']) < 6) {
    header("Location: ../../register/index.php?error=password_short");
    exit;
}

// Экранируем данные
$surname = $connect->real_escape_string(trim($_POST['surname']));
$name = $connect->real_escape_string(trim($_POST['name']));
$patronymic = $connect->real_escape_string(trim($_POST['patronymic']));
$login = $connect->real_escape_string(trim($_POST['login']));
$email = $connect->real_escape_string(trim($_POST['email']));
$phone = $connect->real_escape_string(trim($_POST['phone']));
$password = $connect->real_escape_string($_POST['password']);

// Нормализуем номер телефона
if (strlen($phone) > 18) {  // ИЗМЕНЕНО!
    header("Location: ../../register/index.php?error=phone_too_long");
    exit;
}

// Нормализуем номер телефона (убираем все кроме цифр)
$phone_digits = preg_replace('/[^0-9]/', '', $phone);

// Проверка формата телефона (минимум 10 цифр)
if (strlen($phone_digits) < 10) {
    header("Location: ../../register/index.php?error=invalid_phone");
    exit;
}

// Форматируем телефон для хранения в БД
// Пример: +7 (999) 123-45-67 → 79991234567
$phone_formatted = preg_replace('/[^0-9]/', '', $phone);
if (strlen($phone_formatted) == 11 && $phone_formatted[0] == '8') {
    // Заменяем 8 на 7 для формата +7
    $phone_formatted = '7' . substr($phone_formatted, 1);
}

// Проверка существования логина
$check_login = $connect->query("SELECT client_id FROM clients WHERE login='$login'");
if ($check_login && $check_login->num_rows > 0) {
    header("Location: ../../register/index.php?error=login_exists");
    exit;
}

// Проверка существования email
$check_email = $connect->query("SELECT client_id FROM clients WHERE email='$email'");
if ($check_email && $check_email->num_rows > 0) {
    header("Location: ../../register/index.php?error=email_exists");
    exit;
}

// Проверка существования телефона
$check_phone = $connect->query("SELECT client_id FROM clients WHERE phone='$phone_formatted'");
if ($check_phone && $check_phone->num_rows > 0) {
    header("Location: ../../register/index.php?error=phone_exists");
    exit;
}

// Создаем SQL-запрос
$sql = "INSERT INTO clients (surname, name, patronymic, login, email, phone, password, role, discount_percent, registration_date) 
        VALUES ('$surname', '$name', '$patronymic', '$login', '$email', '$phone_formatted', '$password', 'client', 0, CURDATE())";

// Выполняем запрос
if ($connect->query($sql)) {
    // Получаем ID нового клиента
    $client_id = $connect->insert_id;
    
    $_SESSION['login'] = $login;
    $_SESSION['role'] = 'client';
    $_SESSION['client_id'] = $client_id;
    
    // Редирект в профиль
    header("Location: ../../profile/");
    exit;
} else {
    $error_message = urlencode($connect->error);
    header("Location: ../../register/index.php?error=database_error&details=$error_message");
    exit;
}
?>