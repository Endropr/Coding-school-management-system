<?php
session_start();
include "connect.php";

if(!isset($_SESSION['login'])) {
    die(json_encode(['error' => 'Not authorized']));
}

$client_id = $_SESSION['client_id'];
$result = $connect->query("SELECT discount_percent FROM clients WHERE client_id = $client_id");
$row = $result->fetch_assoc();

echo json_encode([
    'discount_percent' => $row['discount_percent'] ?? 0
]);
?>