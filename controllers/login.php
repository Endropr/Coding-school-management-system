<?php
session_start();
include "../function/connect.php";

$login = $connect->real_escape_string($_POST['login']);
$password = $connect->real_escape_string($_POST['password']);

$sql = sprintf("SELECT * FROM clients WHERE login='%s' AND password='%s'", $login, $password);

$result = $connect->query($sql);
if($result->num_rows){
    $row = $result->fetch_assoc();
    $_SESSION['login'] = $row['login'];
    $_SESSION['role'] = $row['role'];
    $_SESSION['client_id'] = $row['client_id'];
    
    if($row['role'] == 'admin'){
        header("Location: ../admin/");  
    } else {
        header("Location: ../profile/");
    }
    exit;
} else {
    header("Location: ../auth/index.php?message=Некорректный логин или пароль");
    exit;
}
?>