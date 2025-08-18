<?php 
session_start();

unset($_SESSION['login']);
unset($_SESSION['role']);
unset($_SESSION['client_id']);
header("Location: ../"); 
exit;
?>