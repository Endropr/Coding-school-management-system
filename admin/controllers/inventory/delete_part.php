<?php
session_start();
if(!isset($_SESSION['login']) || $_SESSION['role'] != "admin"){
    header("Location: ../../../auth/");
    exit;
}

include "../../../../function/connect.php";

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if($id <= 0) {
    header("Location: ../../?tab=inventory");
    exit;
}

$check_orders = $connect->query("SELECT COUNT(*) as count FROM order_parts WHERE part_id = $id");
$orders_count = $check_orders->fetch_assoc()['count'];

if($orders_count > 0) {
    header("Location: ../../?tab=inventory&error=part_in_use");
    exit;
}

$sql = "DELETE FROM inventory WHERE part_id = $id";

if($connect->query($sql)){
    header("Location: ../../?tab=inventory&success=deleted");
    exit;
} else {
    header("Location: ../../?tab=inventory&error=delete_failed");
    exit;
}
?>