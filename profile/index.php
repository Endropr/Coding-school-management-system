<?php 
session_start();

// Проверка авторизации
if(!isset($_SESSION['login'])){
    header("Location: ../auth/"); // Один уровень выше
    exit;
}

include "../inc/header.php";
include "../function/function.php";

// Получаем ID клиента
$client_id = fnGetClientId($_SESSION['login']);
?>
<div class="main-content">
    <h1 class="page-title">👤 Личный кабинет</h1>
    
    <?php if(isset($_GET['success']) && $_GET['success'] == 'order_created'): ?>
        <div class="alert alert-success">
            ✅ Заказ успешно создан! Ожидайте подтверждения от автосервиса.
        </div>
    <?php endif; ?>
    
    <div class="card">
        <h2 class="card-title">📋 Мои данные</h2>
        <?php echo fnGetClientProfile($_SESSION['login']); ?>
    </div>
    
    <div class="card">
        <h2 class="card-title">🚗 Мои автомобили</h2>
        <?php echo fnGetClientVehicles($client_id); ?>
        
        <div style="text-align: center; margin-top: 20px;">
            <a href="../admin/controllers/vehicles/add_vehicle.php" class="btn">
                ➕ Добавить автомобиль
            </a>
        </div>
    </div>
    
    <div class="card">
        <h2 class="card-title">🔧 Мои заказы</h2>
        <?php echo fnGetClientOrders($client_id); ?>
    </div>
    
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-top: 30px;">
        <a href="../admin/controllers/create_order.php" class="btn" style="text-align: center;">
            🔧 Создать новый заказ
        </a>
        <a href="../admin/controllers/vehicles/add_vehicle.php" class="btn" style="text-align: center; background: #666;">
            🚗 Добавить автомобиль
        </a>
    </div>
</div>
</body>
</html>