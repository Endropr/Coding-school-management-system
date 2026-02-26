<?php 
$connect=new mysqli("localhost","root",'','Practic_DB3');
if($connect->connect_error){
    die("Ошибка подключения");
}
?>