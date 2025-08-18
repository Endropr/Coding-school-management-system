<?php
session_start();
if(!isset($_SESSION['login']) || $_SESSION['role'] != "admin"){
    header("Location: ../../../auth/");
    exit;
}

include "../../../function/connect.php";

$client_id = intval($_POST['client_id']);
$email = $connect->real_escape_string(trim($_POST['email']));
$phone = $connect->real_escape_string(trim($_POST['phone']));
$discount_percent = intval($_POST['discount_percent']);
$status = $connect->real_escape_string($_POST['status']);

// Проверяем email на уникальность (кроме текущего клиента)
$check_email = $connect->query("SELECT client_id FROM clients WHERE email = '$email' AND client_id != $client_id");
if($check_email && $check_email->num_rows > 0) {
    header("Location: edit_client.php?id=$client_id&error=email_exists");
    exit;
}

// Проверяем телефон на уникальность (кроме текущего клиента)
$check_phone = $connect->query("SELECT client_id FROM clients WHERE phone = '$phone' AND client_id != $client_id");
if($check_phone && $check_phone->num_rows > 0) {
    header("Location: edit_client.php?id=$client_id&error=phone_exists");
    exit;
}

// Обновляем данные клиента
$sql = "UPDATE clients SET 
        email = '$email',
        phone = '$phone',
        discount_percent = $discount_percent,
        status = '$status'
        WHERE client_id = $client_id";

if($connect->query($sql)){
    header("Location: ../../?tab=clients&success=updated");
} else {
    header("Location: edit_client.php?id=$client_id&error=database_error");
}

exit;
?>