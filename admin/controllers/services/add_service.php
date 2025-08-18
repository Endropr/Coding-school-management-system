<?php
session_start();
if(!isset($_SESSION['login']) || $_SESSION['role'] != "admin"){
    die("Доступ запрещен. <a href='../../auth/'>Войдите</a> как администратор.");
}

include "../../../inc/header.php";
?>

<div class="main-content" style="max-width: 600px;">
    <h1 class="page-title">➕ Добавление новой услуги</h1>
    
    <?php if(isset($_GET['error'])): ?>
        <div class="alert alert-error">
            <?php 
            $errors = [
                'empty_fields' => '❌ Название услуги обязательно!',
                'database_error' => '❌ Ошибка базы данных.',
                'service_exists' => '❌ Услуга с таким названием уже существует!'
            ];
            echo $errors[$_GET['error']] ?? '❌ Произошла ошибка';
            ?>
        </div>
    <?php endif; ?>
    
    <?php if(isset($_GET['success'])): ?>
        <div class="alert alert-success">
            ✅ Услуга успешно добавлена!
        </div>
    <?php endif; ?>
    
    <form action="process_service.php?action=add" method="post">
        <div class="form-group">
            <label for="name_service">🔧 Название услуги *</label>
            <input type="text" name="name_service" id="name_service" class="form-control" required 
                   placeholder="Например: Замена масла в двигателе">
        </div>
        
        <div class="form-group">
            <label for="category">📁 Категория</label>
            <select name="category" id="category" class="form-control" required>
                <option value="">-- Выберите категорию --</option>
                <option value="diagnostics">Диагностика</option>
                <option value="engine">Двигатель</option>
                <option value="transmission">КПП</option>
                <option value="brakes">Тормоза</option>
                <option value="suspension">Ходовая часть</option>
                <option value="electrical">Электрика</option>
                <option value="bodywork">Кузовные работы</option>
                <option value="maintenance">Техническое обслуживание</option>
            </select>
        </div>
        
        <div class="form-group">
            <label for="description">📝 Описание услуги</label>
            <textarea name="description" id="description" class="form-control" rows="4" 
                      placeholder="Подробное описание услуги..."></textarea>
        </div>
        
        <div class="form-group">
            <label for="base_price">💰 Базовая цена (руб.) *</label>
            <input type="number" name="base_price" id="base_price" class="form-control" required 
                   min="0" step="100" placeholder="3000">
        </div>
        
        <div class="form-group">
            <label for="estimated_time">⏱️ Примерное время выполнения (минут)</label>
            <input type="number" name="estimated_time" id="estimated_time" class="form-control" 
                   min="15" step="15" placeholder="60">
        </div>
        
        <div class="form-group">
            <label for="status">📊 Статус</label>
            <select name="status" id="status" class="form-control">
                <option value="available">Доступна</option>
                <option value="unavailable">Недоступна</option>
            </select>
        </div>
        
        <div style="display: flex; gap: 15px; margin-top: 30px;">
            <button type="submit" class="btn" style="flex: 1;">
                ✅ Добавить услугу
            </button>
            <a href="../../?tab=services" class="btn" style="background: #666; flex: 1; text-align: center;">
                ↩️ Назад к списку
            </a>
        </div>
    </form>
</div>

</body>
</html>