<?php
session_start();

// Проверка авторизации
if(!isset($_SESSION['login']) || $_SESSION['role'] != "admin"){
    die("Доступ запрещен.");
}

include "../../function/connect.php";

// Отладка: покажем что получаем
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Проверка заполненности обязательных полей
if(empty($_POST['name_kurs'])) {
    // Редирект обратно на форму
    header("Location: add_course.php?error=empty_fields");
    exit;
}

// Экранируем данные
$name_kurs = $connect->real_escape_string(trim($_POST['name_kurs']));
$data_nachala = !empty($_POST['data_nachala']) ? $connect->real_escape_string($_POST['data_nachala']) : NULL;
$payments = !empty($_POST['payments']) ? $connect->real_escape_string($_POST['payments']) : '';
$price = isset($_POST['price']) ? intval($_POST['price']) : 0;

$sql = "INSERT INTO courses (name_kurs, data_nachala, payments, price, description) 
        VALUES ('$name_kurs', " . ($data_nachala ? "'$data_nachala'" : "NULL") . ", '$payments', $price, '$description')";
// Проверяем, существует ли уже курс с таким названием
$check_course = $connect->query("SELECT id_course FROM courses WHERE name_kurs = '$name_kurs'");
if($check_course && $check_course->num_rows > 0) {
    header("Location: add_course.php?error=course_exists");
    exit;
}

// Добавляем курс в базу данных (упрощенный вариант)
$sql = "INSERT INTO courses (name_kurs, data_nachala, payments) 
        VALUES ('$name_kurs', " . ($data_nachala ? "'$data_nachala'" : "NULL") . ", '$payments')";

echo "<!-- SQL запрос: $sql -->"; // Для отладки

if($connect->query($sql)){
    // Успешно добавлено - редирект на список курсов
    header("Location: ../index.php?tab=courses&success=added");
    exit;
} else {
    // Ошибка базы данных
    $error_msg = urlencode($connect->error);
    header("Location: add_course.php?error=database_error&msg=$error_msg");
    exit;
}
?>
