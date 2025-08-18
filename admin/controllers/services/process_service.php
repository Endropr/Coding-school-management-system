<?php
session_start();
if(!isset($_SESSION['login']) || $_SESSION['role'] != "admin"){
    header("Location: ../../../auth/");
    exit;
}

include "../../../function/connect.php";

$action = $_GET['action'] ?? '';

if(empty($_POST['name_service'])) {
    if($action == 'add') {
        header("Location: add_service.php?error=empty_fields");
    } else {
        header("Location: edit_service.php?id=" . $_POST['service_id'] . "&error=empty_fields");
    }
    exit;
}

$name_service = $connect->real_escape_string(trim($_POST['name_service']));
$category = $connect->real_escape_string($_POST['category']);
$description = !empty($_POST['description']) ? $connect->real_escape_string($_POST['description']) : '';
$base_price = floatval($_POST['base_price']);
$estimated_time = !empty($_POST['estimated_time']) ? intval($_POST['estimated_time']) : NULL;
$status = $connect->real_escape_string($_POST['status']);

if($action == 'add') {
    $check_service = $connect->query("SELECT service_id FROM services WHERE name_service = '$name_service'");
    if($check_service && $check_service->num_rows > 0) {
        header("Location: add_service.php?error=service_exists");
        exit;
    }
    
    $sql = "INSERT INTO services (name_service, category, description, base_price, estimated_time, status) 
            VALUES ('$name_service', '$category', '$description', $base_price, " . 
            ($estimated_time ? "$estimated_time" : "NULL") . ", '$status')";
    
    if($connect->query($sql)){
        header("Location: add_service.php?success=true");
    } else {
        header("Location: add_service.php?error=database_error");
    }
    
} elseif($action == 'edit') {
    $service_id = intval($_POST['service_id']);
    
    $sql = "UPDATE services SET 
            name_service = '$name_service',
            category = '$category',
            description = '$description',
            base_price = $base_price,
            estimated_time = " . ($estimated_time ? "$estimated_time" : "NULL") . ",
            status = '$status'
            WHERE service_id = $service_id";
    
    if($connect->query($sql)){
        header("Location: edit_service.php?id=$service_id&success=true");
    } else {
        header("Location: edit_service.php?id=$service_id&error=database_error");
    }
}

exit;
?>