<?php 
session_start();
if(!isset($_SESSION['login']) || $_SESSION['role'] != "admin"){
    header("Location: ../../auth/"); 
    exit;
}

include "../../function/connect.php";

if(isset($_GET['action'])){
    $id = intval($_GET['id']);
    
    switch($_GET['action']){
        case 'complete':
            $sql = sprintf("UPDATE work_orders SET status='completed', completion_date=CURDATE() WHERE order_id='%d'", $id);
            $connect->query($sql);
            header("Location: ../../admin/?tab=orders");
            exit;

        case 'progress':
            $sql = sprintf("UPDATE work_orders SET status='in_progress' WHERE order_id='%d'", $id);
            $connect->query($sql);
            header("Location: ../../admin/?tab=orders");
            exit;

        case 'cancel':
            $sql = sprintf("UPDATE work_orders SET status='cancelled' WHERE order_id='%d'", $id);
            $connect->query($sql);
            header("Location: ../../admin/?tab=orders");
            exit;
    }
}
?>