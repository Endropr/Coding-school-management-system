<?php
session_start();
if(!isset($_SESSION['login']) || $_SESSION['role'] != "admin"){
    header("Location: ../../../auth/");
    exit;
}

include "../../../function/connect.php";

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if($id <= 0) {
    header("Location: ../../?tab=services");
    exit;
}

$check_orders = $connect->query("SELECT COUNT(*) as count FROM work_orders WHERE service_id = $id");
$orders_count = $check_orders->fetch_assoc()['count'];

if($orders_count > 0) {
    header("Location: ../../?tab=services&error=service_has_orders");
    exit;
}

$sql = "DELETE FROM services WHERE service_id = $id";

if($connect->query($sql)){
    header("Location: ../../?tab=services&success=deleted");
    exit;
} else {
    header("Location: ../../?tab=services&error=delete_failed");
    exit;
}
?>