<?php
session_start();
if(!isset($_SESSION['login'])){
    header("Location: ../../../auth/");
    exit;
}

include "../../../function/connect.php";

$action = $_GET['action'] ?? '';
$client_id = $_SESSION['client_id'];

if(empty($_POST['brand']) || empty($_POST['model']) || empty($_POST['year']) || 
   empty($_POST['vin']) || empty($_POST['license_plate'])) {
    if($action == 'add') {
        header("Location: add_vehicle.php?error=empty_fields");
    }
    exit;
}

$brand = $connect->real_escape_string(trim($_POST['brand']));
$model = $connect->real_escape_string(trim($_POST['model']));
$year = intval($_POST['year']);
$vin = $connect->real_escape_string(strtoupper(trim($_POST['vin'])));
$license_plate = $connect->real_escape_string(strtoupper(trim($_POST['license_plate'])));
$color = !empty($_POST['color']) ? $connect->real_escape_string($_POST['color']) : NULL;
$mileage = !empty($_POST['mileage']) ? intval($_POST['mileage']) : 0;
$notes = !empty($_POST['notes']) ? $connect->real_escape_string($_POST['notes']) : NULL;

if($action == 'add') {
    $check_vin = $connect->query("SELECT vehicle_id FROM vehicles WHERE vin = '$vin'");
    if($check_vin && $check_vin->num_rows > 0) {
        header("Location: add_vehicle.php?error=vin_exists");
        exit;
    }
    
    $check_plate = $connect->query("SELECT vehicle_id FROM vehicles WHERE license_plate = '$license_plate'");
    if($check_plate && $check_plate->num_rows > 0) {
        header("Location: add_vehicle.php?error=plate_exists");
        exit;
    }
    
    $sql = "INSERT INTO vehicles (client_id, brand, model, year, vin, license_plate, color, mileage, notes) 
            VALUES ($client_id, '$brand', '$model', $year, '$vin', '$license_plate', " . 
            ($color ? "'$color'" : "NULL") . ", $mileage, " . ($notes ? "'$notes'" : "NULL") . ")";
    
    if($connect->query($sql)){
        header("Location: add_vehicle.php?success=true");
    } else {
        header("Location: add_vehicle.php?error=database_error");
    }
}

exit;
?>