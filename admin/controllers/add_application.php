<?php
session_start();
include "../../function/connect.php";

if(!isset($_SESSION['login'])) {
    header("Location: ../../auth/");
    exit;
}

// Получаем ID пользователя
$select = "SELECT user_id FROM clients WHERE login='" . $_SESSION['login'] . "'";
$select_result = $connect->query($select);
$select_row = $select_result->fetch_assoc();

if(!$select_row) {
    header("Location: ../../auth/");
    exit;
}

$id_user = $select_row['user_id'];

// Получаем данные из формы
$id_course = isset($_POST['id_course']) ? intval($_POST['id_course']) : 0;
$selected_date = isset($_POST['selected_date']) ? $connect->real_escape_string($_POST['selected_date']) : '';
$payment_method = isset($_POST['payment_method']) ? $connect->real_escape_string($_POST['payment_method']) : '';
$message = isset($_POST['message']) ? $connect->real_escape_string($_POST['message']) : '';

// Валидация
if($id_course <= 0) {
    header("Location: application.php?error=no_course");
    exit;
}

if(empty($selected_date)) {
    header("Location: application.php?error=no_date");
    exit;
}

if(empty($payment_method)) {
    header("Location: application.php?error=no_payment");
    exit;
}

// Проверяем существование курса
$check_course = $connect->query("SELECT id_course FROM courses WHERE id_course = $id_course");
if($check_course->num_rows == 0) {
    header("Location: application.php?error=invalid_course");
    exit;
}

// Проверяем, есть ли уже активная заявка от этого пользователя на этот курс
$check_existing = $connect->query("SELECT id_zayavka FROM zayavka 
                                  WHERE user_id = $id_user 
                                  AND id_course = $id_course 
                                  AND (status IS NULL OR status != 'Отменена')");
if($check_existing && $check_existing->num_rows > 0) {
    header("Location: application.php?error=already_applied");
    exit;
}

// Добавляем информацию о дате и способе оплаты в сообщение
$full_message = "Дата начала: " . $selected_date . "\n";
$full_message .= "Способ оплаты: " . $payment_method . "\n";
$full_message .= "Сообщение от пользователя: " . $message;

// Вставляем заявку
$sql = "INSERT INTO zayavka (user_id, id_course, message, time, status) 
        VALUES ('$id_user', '$id_course', '$full_message', CURDATE(), 'Новая')";

if($connect->query($sql)){
    header("Location: ../../profile/?success=application_sent");
} else {
    header("Location: application.php?error=database_error");
}
exit;
?>