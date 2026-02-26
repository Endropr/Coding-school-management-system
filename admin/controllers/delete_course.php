<?php
session_start();
if(!isset($_SESSION['login']) || $_SESSION['role'] != "admin"){
    header("Location: ../../auth/");
    exit;
}

include "../../function/connect.php";

// Получаем ID курса
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if($id <= 0) {
    header("Location: ../index.php?tab=courses");
    exit;
}

// Проверяем, есть ли заявки на этот курс
$check_applications = $connect->query("SELECT COUNT(*) as count FROM zayavka WHERE id_course = $id");
$applications_count = $check_applications->fetch_assoc()['count'];

if($applications_count > 0) {
    // Не удаляем курс, на который есть заявки
    header("Location: ../index.php?tab=courses&error=course_has_applications");
    exit;
}

// Удаляем курс
$sql = "DELETE FROM courses WHERE id_course = $id";

if($connect->query($sql)){
    header("Location: ../index.php?tab=courses&success=deleted");
    exit;
} else {
    header("Location: ../index.php?tab=courses&error=delete_failed");
    exit;
}
?>
