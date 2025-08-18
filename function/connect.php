<?php 
$db_host="localhost";
$db_name="root";
$db_pass='';
$db_base='autoservice_db';
$connect = new mysqli($db_host, $db_name, $db_pass, $db_base);

if($connect->connect_error){
    die("Ошибка подключения");
}
?>