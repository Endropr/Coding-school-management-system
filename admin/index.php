<?php 
session_start();

if(!isset($_SESSION['login']) || $_SESSION['role'] != "admin"){
    header("Location: ../auth/"); 
    exit;
}

include "../inc/header.php";
include "../function/function.php";

$tab = isset($_GET['tab']) ? $_GET['tab'] : 'orders';
?>
<div class="main-content">
    <h1 class="page-title">👑 Панель администратора автосервиса</h1>
    
    <div class="tabs" style="margin-bottom: 30px; border-bottom: 2px solid #eee;">
        <div style="display: flex; gap: 10px; flex-wrap: wrap;">
            <a href="?tab=orders" class="btn btn-sm <?php echo $tab == 'orders' ? 'selected' : ''; ?>" 
               style="background: <?php echo $tab == 'orders' ? '#1a5ddb' : '#666'; ?>;">
               📋 Заказы
            </a>
            <a href="?tab=services" class="btn btn-sm <?php echo $tab == 'services' ? 'selected' : ''; ?>" 
               style="background: <?php echo $tab == 'services' ? '#1a5ddb' : '#666'; ?>;">
               🔧 Услуги
            </a>
            <a href="?tab=mechanics" class="btn btn-sm <?php echo $tab == 'mechanics' ? 'selected' : ''; ?>" 
               style="background: <?php echo $tab == 'mechanics' ? '#1a5ddb' : '#666'; ?>;">
               👨‍🔧 Механики
            </a>
            <a href="?tab=clients" class="btn btn-sm <?php echo $tab == 'clients' ? 'selected' : ''; ?>" 
               style="background: <?php echo $tab == 'clients' ? '#1a5ddb' : '#666'; ?>;">
               👥 Клиенты
            </a>
            <a href="?tab=inventory" class="btn btn-sm <?php echo $tab == 'inventory' ? 'selected' : ''; ?>" 
               style="background: <?php echo $tab == 'inventory' ? '#1a5ddb' : '#666'; ?>;">
               📦 Запчасти
            </a>
        </div>
    </div>
    
    <div class="tab-content">
        <?php if($tab == 'orders'): ?>
            <div class="card">
                <h2 class="card-title">📋 Управление заказами</h2>
                <?php echo fnGetOrdersAdmin(); ?>
            </div>
            
        <?php elseif($tab == 'services'): ?>
         
            <div class="card">
                <h2 class="card-title">🔧 Управление услугами</h2>
                <?php echo fnGetServicesAdmin(); ?>
            </div>
            
        <?php elseif($tab == 'mechanics'): ?>
           
            <div class="card">
                <h2 class="card-title">👨‍🔧 Управление механиками</h2>
                <?php echo fnGetMechanicsAdmin(); ?>
            </div>
            
        <?php elseif($tab == 'clients'): ?>
            
            <div class="card">
                <h2 class="card-title">👥 Управление клиентами</h2>
                <?php 
                $sql = "SELECT * FROM clients WHERE role = 'client' ORDER BY client_id DESC";
                $result = $connect->query($sql);
                
                if($result->num_rows > 0) {
                    echo '<table class="table table-striped">
                    <thead>
                    <tr>
                    <th>ID</th>
                    <th>ФИО</th>
                    <th>Логин</th>
                    <th>Email</th>
                    <th>Телефон</th>
                    <th>Скидка</th>
                    <th>Дата регистрации</th>
                    <th>Действия</th>
                    </tr>
                    </thead>
                    <tbody>';
                    
                    while($row = $result->fetch_assoc()) {
                        echo sprintf('
                        <tr>
                        <td>%s</td>
                        <td>%s %s %s</td>
                        <td>%s</td>
                        <td>%s</td>
                        <td>%s</td>
                        <td>%s%%</td>
                        <td>%s</td>
                        <td>
                            <a href="controllers/clients/edit_clients.php?id=%s" class="btn btn-sm" style="background: #2196F3;">✏️</a>
                        </td>
                        </tr>',
                        $row['client_id'],
                        htmlspecialchars($row['surname']),
                        htmlspecialchars($row['name']),
                        htmlspecialchars($row['patronymic']),
                        htmlspecialchars($row['login']),
                        htmlspecialchars($row['email']),
                        htmlspecialchars($row['phone']),
                        htmlspecialchars($row['discount_percent']),
                        htmlspecialchars($row['registration_date']),
                        $row['client_id']
                        );
                    }
                    
                    echo '</tbody></table>';
                } else {
                    echo '<div class="alert alert-warning">Нет зарегистрированных клиентов</div>';
                }
                ?>
            </div>
            
        <?php elseif($tab == 'inventory'): ?>
           
            <div class="card">
                <h2 class="card-title">📦 Управление запчастями</h2>
                <?php 
                $sql = "SELECT * FROM inventory ORDER BY part_id";
                $result = $connect->query($sql);
                
                if($result->num_rows > 0) {
                    echo '<table class="table table-striped">
                    <thead>
                    <tr>
                    <th>ID</th>
                    <th>Название</th>
                    <th>Артикул</th>
                    <th>Поставщик</th>
                    <th>Цена</th>
                    <th>В наличии</th>
                    <th>Мин. запас</th>
                    <th>Действия</th>
                    </tr>
                    </thead>
                    <tbody>';
                    
                    while($row = $result->fetch_assoc()) {
                        $stock_class = $row['quantity_in_stock'] <= $row['min_quantity'] ? 'style="color: red; font-weight: bold;"' : '';
                        
                        echo sprintf('
                        <tr>
                        <td>%s</td>
                        <td>%s</td>
                        <td>%s</td>
                        <td>%s</td>
                        <td>%s ₽</td>
                        <td %s>%s шт</td>
                        <td>%s шт</td>
                        <td>
                            <div style="display: flex; gap: 5px;">
                                <a href="controllers/inventory/edit_part.php?id=%s" class="btn btn-sm" style="background: #2196F3;">✏️</a>
                                <a href="controllers/inventory/delete_part.php?id=%s" class="btn btn-sm" style="background: #c62828;" 
                                   onclick="return confirm(\'Удалить запчасть?\')">🗑️</a>
                            </div>
                        </td>
                        </tr>',
                        $row['part_id'],
                        htmlspecialchars($row['part_name']),
                        htmlspecialchars($row['part_number']),
                        htmlspecialchars($row['supplier']),
                        number_format($row['unit_price'], 0, '', ' '),
                        $stock_class,
                        htmlspecialchars($row['quantity_in_stock']),
                        htmlspecialchars($row['min_quantity']),
                        $row['part_id'],
                        $row['part_id']
                        );
                    }
                    
                    echo '</tbody></table>';
                } else {
                    echo '<div class="alert alert-warning">Нет запчастей в инвентаре</div>';
                }
                ?>
                
                <div style="margin-top: 30px; text-align: center;">
                    <a href="controllers/inventory/add_part.php" class="btn" style="padding: 12px 30px;">
                        ➕ Добавить запчасть
                    </a>
                </div>
            </div>
        <?php endif; ?>
    </div>
    

    <div class="card" style="margin-top: 30px;">
        <h2 class="card-title">📊 Статистика автосервиса</h2>
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px;">
            <?php
            $stats = fnGetStatistics();
            ?>
            
            <div class="card" style="text-align: center;">
                <h3 style="color: #1a5ddb; font-size: 32px;"><?php echo $stats['clients']; ?></h3>
                <p>Клиентов</p>
            </div>
            
            <div class="card" style="text-align: center;">
                <h3 style="color: #1a5ddb; font-size: 32px;"><?php echo $stats['mechanics']; ?></h3>
                <p>Механиков</p>
            </div>
            
            <div class="card" style="text-align: center;">
                <h3 style="color: #1a5ddb; font-size: 32px;"><?php echo $stats['orders']; ?></h3>
                <p>Всего заказов</p>
            </div>
            
            <div class="card" style="text-align: center;">
                <h3 style="color: #FF9800; font-size: 32px;"><?php echo $stats['active_orders']; ?></h3>
                <p>Активных заказов</p>
            </div>
            
            <div class="card" style="text-align: center;">
                <h3 style="color: #2196F3; font-size: 32px;"><?php echo $stats['revenue']; ?></h3>
                <p>Общая выручка</p>
            </div>
        </div>
    </div>
</div>

<style>
    .badge {
        display: inline-block;
        padding: 4px 8px;
        background: #e3f2fd;
        color: #1976d2;
        border-radius: 12px;
        font-size: 12px;
        font-weight: bold;
    }
    
    .tabs .btn-sm.selected {
        box-shadow: 0 0 0 2px rgba(76, 175, 80, 0.3);
    }
</style>
</body>
</html>