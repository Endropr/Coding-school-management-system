<?php
session_start();
include "../../function/connect.php";


$sql=sprintf("SELECT * FROM clients WHERE login='%s' 
AND password='%s'",$_POST['login'],$_POST['password']);

$result=$connect->query($sql);
if($result->num_rows){
    $row=$result->fetch_assoc();
    $_SESSION['login']=$row['login'];
    $_SESSION['role']=$row['role'];
    header("Location: ../../profile/");
    exit;
    
}
else{
    header("Location: ../../auth/index.php?message=Некорректный логин или пароль");
    exit;
}
?>