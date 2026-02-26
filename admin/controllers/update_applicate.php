<?php 
include "../../function/connect.php";
if(isset($_GET['action'])){
    $id = intval($_GET['id']); // Безопасно получаем ID
    
    switch($_GET['action']){
        case 'success':
            $sql = sprintf("UPDATE zayavka SET status='Подтверждена' WHERE id_zayavka='%d'", $id);
            $connect->query($sql);
            header("Location: ../../admin/");
            exit;

        case 'cancel':
            $sql = sprintf("UPDATE zayavka SET status='Отменена' WHERE id_zayavka='%d'", $id);
            $connect->query($sql);
            header("Location: ../../admin/");
            exit;
    }
}
?>