<?php 
session_start();
if(!isset($_SESSION['login'])){
    header ("Location: ../auth/");
}
include "../inc/header.php";
include "../function/function.php";
?>
<div class="main-content">
    <h1 class="page-title">🔹      Личный кабинет      🔹</h1>
    
    <?php if(isset($_GET['success']) && $_GET['success'] == 'application_sent'): ?>
        <div class="alert alert-success">
            ✅ Заявка успешно отправлена! Ожидайте подтверждения администратора.
        </div>
    <?php endif; ?>
    
    <div class="card">
        <?php echo fnGetProfile($_SESSION['login']); ?>
    </div>
    
    <div class="card">
        <h2 class="card-title">🧿 Мои заявки на курсы</h2>
        <?php echo fnGetTablProfile($_SESSION['login']); ?>
    </div>
    
    <div style="text-align: center; margin-top: 30px;">
        <a href="../admin/controllers/application.php" class="btn">
            🔹  Подать новую заявку  🔹
        </a>
    </div>
</div>
</body>
</html>
