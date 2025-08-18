<?php
session_start();
include "../../function/connect.php";

if(!isset($_SESSION['login'])) {
    header("Location: ../../auth/");
    exit;
}

$client_id = $_SESSION['client_id'];
$vehicle_id = isset($_POST['vehicle_id']) ? intval($_POST['vehicle_id']) : 0;
$mechanic_id = isset($_POST['mechanic_id']) ? intval($_POST['mechanic_id']) : 0;
$service_id = isset($_POST['service_id']) ? intval($_POST['service_id']) : 0;
$notes = isset($_POST['notes']) ? $connect->real_escape_string($_POST['notes']) : '';
$base_price = isset($_POST['service_price']) ? floatval($_POST['service_price']) : 0;


if($service_id <= 0) {
    header("Location: create_order.php?error=no_service");
    exit;
}

if($vehicle_id <= 0) {
    header("Location: create_order.php?error=no_vehicle");
    exit;
}

if($mechanic_id <= 0) {
    header("Location: create_order.php?error=no_mechanic");
    exit;
}


$discount_query = $connect->query("SELECT discount_percent FROM clients WHERE client_id = $client_id");
$discount_row = $discount_query->fetch_assoc();
$discount_percent = $discount_row['discount_percent'] ?? 0;


$discount_amount = $base_price * $discount_percent / 100;
$total_price = $base_price - $discount_amount;


$sql = "INSERT INTO work_orders (client_id, vehicle_id, mechanic_id, service_id, order_date, 
        status, base_price, discount_amount, total_price, notes) 
        VALUES ($client_id, $vehicle_id, $mechanic_id, $service_id, CURDATE(), 
        'new', $base_price, $discount_amount, $total_price, '$notes')";

if($connect->query($sql)){
    header("Location: ../../profile/?success=order_created");
} else {
    header("Location: create_order.php?error=database_error");
}
exit;
?>