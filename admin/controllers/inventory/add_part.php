<?php
session_start();
if(!isset($_SESSION['login']) || $_SESSION['role'] != "admin"){
    die("Доступ запрещен. <a href='../../auth/'>Войдите</a> как администратор.");
}

include "../../../inc/header.php";
?>

<div class="main-content" style="max-width: 600px;">
    <h1 class="page-title">📦 Добавление запчасти</h1>
    
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
    
    <form action="process_part.php?action=add" method="post">
        <div class="form-group">
            <label for="part_name">🔩 Название запчасти *</label>
            <input type="text" name="part_name" id="part_name" class="form-control" required 
                   placeholder="Например: Тормозные колодки передние">
        </div>
        
        <div class="form-group">
            <label for="part_number">🏷️ Артикул</label>
            <input type="text" name="part_number" id="part_number" class="form-control" 
                   placeholder="BP12345">
        </div>
        
        <div class="form-group">
            <label for="supplier">🏢 Поставщик</label>
            <input type="text" name="supplier" id="supplier" class="form-control" 
                   placeholder="Brembo">
        </div>
        
        <div class="form-group">
            <label for="unit_price">💰 Цена за единицу (руб.) *</label>
            <input type="number" name="unit_price" id="unit_price" class="form-control" required 
                   min="0" step="10" placeholder="3500">
        </div>
        
        <div class="form-group">
            <label for="quantity_in_stock">📊 Количество на складе</label>
            <input type="number" name="quantity_in_stock" id="quantity_in_stock" class="form-control" 
                   min="0" value="0">
        </div>
        
        <div class="form-group">
            <label for="min_quantity">⚠️ Минимальный запас</label>
            <input type="number" name="min_quantity" id="min_quantity" class="form-control" 
                   min="1" value="5">
            <small style="color: #666;">При достижении этого количества будет показано предупреждение</small>
        </div>
        
        <div class="form-group">
            <label for="category">📁 Категория</label>
            <input type="text" name="category" id="category" class="form-control" 
                   placeholder="Тормозная система, Фильтры, Свечи...">
        </div>
        
        <div style="display: flex; gap: 15px; margin-top: 30px;">
            <button type="submit" class="btn" style="flex: 1;">
                ✅ Добавить запчасть
            </button>
            <a href="../../?tab=inventory" class="btn" style="background: #666; flex: 1; text-align: center;">
                ↩️ Назад к списку
            </a>
        </div>
    </form>
</div>

</body>
</html>