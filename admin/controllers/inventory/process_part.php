<?php
session_start();
if(!isset($_SESSION['login']) || $_SESSION['role'] != "admin"){
    header("Location: ../../../auth/");
    exit;
}

include "../../../../function/connect.php";

$action = $_GET['action'] ?? '';

// Проверка заполненности обязательных полей
if(empty($_POST['part_name'])) {
    if($action == 'add') {
        header("Location: add_part.php?error=empty_fields");
    }
    exit;
}

// Экранируем данные
$part_name = $connect->real_escape_string(trim($_POST['part_name']));
$part_number = !empty($_POST['part_number']) ? $connect->real_escape_string($_POST['part_number']) : NULL;
$supplier = !empty($_POST['supplier']) ? $connect->real_escape_string($_POST['supplier']) : NULL;
$unit_price = floatval($_POST['unit_price']);
$quantity_in_stock = intval($_POST['quantity_in_stock']);
$min_quantity = intval($_POST['min_quantity']);
$category = !empty($_POST['category']) ? $connect->real_escape_string($_POST['category']) : NULL;

if($action == 'add') {
    // Проверяем, существует ли уже запчасть с таким артикулом
    if($part_number) {
        $check_part = $connect->query("SELECT part_id FROM inventory WHERE part_number = '$part_number'");
        if($check_part && $check_part->num_rows > 0) {
            header("Location: add_part.php?error=part_exists");
            exit;
        }
    }
    
    $sql = "INSERT INTO inventory (part_name, part_number, supplier, unit_price, quantity_in_stock, min_quantity, category) 
            VALUES ('$part_name', " . ($part_number ? "'$part_number'" : "NULL") . ", " . 
            ($supplier ? "'$supplier'" : "NULL") . ", $unit_price, $quantity_in_stock, $min_quantity, " . 
            ($category ? "'$category'" : "NULL") . ")";
    
    if($connect->query($sql)){
        header("Location: ../../?tab=inventory&success=added");
    } else {
        header("Location: add_part.php?error=database_error");
    }
    
} elseif($action == 'edit') {
    $part_id = intval($_POST['part_id']);
    
    $sql = "UPDATE inventory SET 
            part_name = '$part_name',
            part_number = " . ($part_number ? "'$part_number'" : "NULL") . ",
            supplier = " . ($supplier ? "'$supplier'" : "NULL") . ",
            unit_price = $unit_price,
            quantity_in_stock = $quantity_in_stock,
            min_quantity = $min_quantity,
            category = " . ($category ? "'$category'" : "NULL") . "
            WHERE part_id = $part_id";
    
    if($connect->query($sql)){
        header("Location: ../../?tab=inventory&success=updated");
    } else {
        header("Location: edit_part.php?id=$part_id&error=database_error");
    }
}

exit;
?>