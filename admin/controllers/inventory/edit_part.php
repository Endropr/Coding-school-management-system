<?php
session_start();
if(!isset($_SESSION['login']) || $_SESSION['role'] != "admin"){
    header("Location: ../../../auth/");
    exit;
}

include "../../../function/connect.php";

// Получаем ID запчасти
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if($id <= 0) {
    header("Location: ../../?tab=inventory");
    exit;
}

// Получаем данные запчасти
$sql = "SELECT * FROM inventory WHERE part_id = $id";
$result = $connect->query($sql);
$part = $result->fetch_assoc();

if(!$part) {
    header("Location: ../../?tab=inventory");
    exit;
}

include "../../../inc/header.php";
?>

<div class="main-content" style="max-width: 600px;">
    <h1 class="page-title">✏️ Редактирование запчасти</h1>
    
    <?php if(isset($_GET['error'])): ?>
        <div class="alert alert-error">
            <?php 
            $errors = [
                'empty_fields' => '❌ Название запчасти обязательно!',
                'part_exists' => '❌ Запчасть с таким артикулом уже существует!',
                'database_error' => '❌ Ошибка базы данных.'
            ];
            echo $errors[$_GET['error']] ?? '❌ Произошла ошибка';
            ?>
        </div>
    <?php endif; ?>
    
    <?php if(isset($_GET['success'])): ?>
        <div class="alert alert-success">
            ✅ Запчасть успешно обновлена!
        </div>
    <?php endif; ?>
    
    <form action="process_part.php?action=edit" method="post">
        <input type="hidden" name="part_id" value="<?php echo $part['part_id']; ?>">
        
        <div class="form-group">
            <label for="part_name">🔩 Название запчасти *</label>
            <input type="text" name="part_name" id="part_name" class="form-control" required 
                   value="<?php echo htmlspecialchars($part['part_name']); ?>">
        </div>
        
        <div class="form-group">
            <label for="part_number">🏷️ Артикул</label>
            <input type="text" name="part_number" id="part_number" class="form-control" 
                   value="<?php echo htmlspecialchars($part['part_number'] ?? ''); ?>">
        </div>
        
        <div class="form-group">
            <label for="supplier">🏢 Поставщик</label>
            <input type="text" name="supplier" id="supplier" class="form-control" 
                   value="<?php echo htmlspecialchars($part['supplier'] ?? ''); ?>">
        </div>
        
        <div class="form-group">
            <label for="unit_price">💰 Цена за единицу (руб.) *</label>
            <input type="number" name="unit_price" id="unit_price" class="form-control" required 
                   min="0" step="10" value="<?php echo $part['unit_price']; ?>">
        </div>
        
        <div class="form-group">
            <label for="quantity_in_stock">📊 Количество на складе</label>
            <input type="number" name="quantity_in_stock" id="quantity_in_stock" class="form-control" 
                   min="0" value="<?php echo $part['quantity_in_stock']; ?>">
        </div>
        
        <div class="form-group">
            <label for="min_quantity">⚠️ Минимальный запас</label>
            <input type="number" name="min_quantity" id="min_quantity" class="form-control" 
                   min="1" value="<?php echo $part['min_quantity']; ?>">
            <small style="color: #666;">При достижении этого количества будет показано предупреждение</small>
        </div>
        
        <div class="form-group">
            <label for="category">📁 Категория</label>
            <input type="text" name="category" id="category" class="form-control" 
                   value="<?php echo htmlspecialchars($part['category'] ?? ''); ?>"
                   placeholder="Тормозная система, Фильтры, Свечи...">
        </div>
        
        <div style="display: flex; gap: 15px; margin-top: 30px;">
            <button type="submit" class="btn" style="flex: 1;">
                💾 Сохранить изменения
            </button>
            <a href="../../?tab=inventory" class="btn" style="background: #666; flex: 1; text-align: center;">
                ↩️ Назад к списку
            </a>
        </div>
    </form>
</div>

</body>
</html>
