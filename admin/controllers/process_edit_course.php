<?php
session_start();
if(!isset($_SESSION['login']) || $_SESSION['role'] != "admin"){
    header("Location: ../../auth/");
    exit;
}

include "../../function/connect.php";

// Проверка ID курса
$id_course = isset($_POST['id_course']) ? intval($_POST['id_course']) : 0;

if($id_course <= 0) {
    header("Location: ../index.php?tab=courses&error=not_found");
    exit;
}

// Проверка заполненности обязательных полей
if(empty($_POST['name_kurs'])) {
    header("Location: edit_course.php?id=$id_course&error=empty_fields");
    exit;
}

// Экранируем данные
$name_kurs = $connect->real_escape_string(trim($_POST['name_kurs']));
$data_nachala = !empty($_POST['data_nachala']) ? $connect->real_escape_string($_POST['data_nachala']) : NULL;
$payments = !empty($_POST['payments']) ? $connect->real_escape_string($_POST['payments']) : '';
$price = isset($_POST['price']) ? intval($_POST['price']) : 0;

$sql = "UPDATE courses SET 
        name_kurs = '$name_kurs',
        data_nachala = " . ($data_nachala ? "'$data_nachala'" : "NULL") . ",
        payments = '$payments',
        price = $price,
        description = '$description'
        WHERE id_course = $id_course";
// Проверяем структуру таблицы для дополнительных полей
$check_fields = $connect->query("SHOW COLUMNS FROM courses");
$has_description = false;
$has_price = false;
$has_duration = false;

while($row = $check_fields->fetch_assoc()) {
    if($row['Field'] == 'description') $has_description = true;
    if($row['Field'] == 'price') $has_price = true;
    if($row['Field'] == 'duration') $has_duration = true;
}

// Формируем SQL запрос
if($has_description && $has_price && $has_duration) {
    $description = !empty($_POST['description']) ? $connect->real_escape_string($_POST['description']) : '';
    $price = isset($_POST['price']) ? intval($_POST['price']) : 0;
    $duration = !empty($_POST['duration']) ? intval($_POST['duration']) : NULL;
    
    $sql = "UPDATE courses SET 
            name_kurs = '$name_kurs',
            data_nachala = " . ($data_nachala ? "'$data_nachala'" : "NULL") . ",
            payments = '$payments',
            description = '$description',
            price = $price,
            duration = " . ($duration ? $duration : "NULL") . "
            WHERE id_course = $id_course";
} else {
    $sql = "UPDATE courses SET 
            name_kurs = '$name_kurs',
            data_nachala = " . ($data_nachala ? "'$data_nachala'" : "NULL") . ",
            payments = '$payments'
            WHERE id_course = $id_course";
}

if($connect->query($sql)){
    header("Location: edit_course.php?id=$id_course&success=true");
    exit;
} else {
    error_log("Ошибка обновления курса: " . $connect->error);
    header("Location: edit_course.php?id=$id_course&error=database_error");
    exit;
}
?>
