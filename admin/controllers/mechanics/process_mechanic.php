<?php
session_start();
if(!isset($_SESSION['login']) || $_SESSION['role'] != "admin"){
    header("Location: ../../../auth/");
    exit;
}

include "../../../function/connect.php";

$action = $_GET['action'] ?? '';

if(empty($_POST['surname']) || empty($_POST['name']) || empty($_POST['patronymic']) || empty($_POST['phone'])) {
    if($action == 'add') {
        header("Location: add_mechanic.php?error=empty_fields");
    } else {
        header("Location: edit_mechanic.php?id=" . $_POST['mechanic_id'] . "&error=empty_fields");
    }
    exit;
}

$surname = $connect->real_escape_string(trim($_POST['surname']));
$name = $connect->real_escape_string(trim($_POST['name']));
$patronymic = $connect->real_escape_string(trim($_POST['patronymic']));
$specialization = !empty($_POST['specialization']) ? $connect->real_escape_string($_POST['specialization']) : '';
$phone = $connect->real_escape_string(trim($_POST['phone']));
$hourly_rate = floatval($_POST['hourly_rate']);
$status = $connect->real_escape_string($_POST['status']);
$hired_date = !empty($_POST['hired_date']) ? $connect->real_escape_string($_POST['hired_date']) : date('Y-m-d');

$phone_digits = preg_replace('/[^0-9]/', '', $phone);

if($action == 'add') {
    $check_phone = $connect->query("SELECT mechanic_id FROM mechanics WHERE phone = '$phone'");
    if($check_phone && $check_phone->num_rows > 0) {
        header("Location: add_mechanic.php?error=phone_exists");
        exit;
    }
    if (strlen($phone) > 18) {
    if($action == 'add') {
        header("Location: add_mechanic.php?error=phone_too_long");
    } else {
        header("Location: edit_mechanic.php?id=" . $_POST['mechanic_id'] . "&error=phone_too_long");
    }
    exit;
}

$errors = [
    'empty_fields' => '❌ Все обязательные поля должны быть заполнены!',
    'phone_exists' => '❌ Этот телефон уже зарегистрирован!',
    'phone_too_long' => '❌ Номер телефона слишком длинный (макс. 18 символов)!',
    'database_error' => '❌ Ошибка базы данных.'
];
    $sql = "INSERT INTO mechanics (surname, name, patronymic, specialization, phone, hourly_rate, status, hired_date) 
            VALUES ('$surname', '$name', '$patronymic', '$specialization', '$phone', $hourly_rate, '$status', '$hired_date')";
    
    if($connect->query($sql)){
        header("Location: ../../?tab=mechanics&success=added");
    } else {
        header("Location: add_mechanic.php?error=database_error");
    }
    
} elseif($action == 'edit') {
    $mechanic_id = intval($_POST['mechanic_id']);
    
    $sql = "UPDATE mechanics SET 
            surname = '$surname',
            name = '$name',
            patronymic = '$patronymic',
            specialization = '$specialization',
            phone = '$phone',
            hourly_rate = $hourly_rate,
            status = '$status'
            WHERE mechanic_id = $mechanic_id";
    
    if($connect->query($sql)){
        header("Location: ../../?tab=mechanics&success=updated");
    } else {
        header("Location: edit_mechanic.php?id=$mechanic_id&error=database_error");
    }
}

exit;
?>