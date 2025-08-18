<?php
session_start();
if(!isset($_SESSION['login']) || $_SESSION['role'] != "admin"){
    header("Location: ../../../auth/");
    exit;
}

include "../../../function/connect.php";

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if($id <= 0) {
    header("Location: ../../?tab=services");
    exit;
}
$sql = "SELECT * FROM services WHERE service_id = $id";
$result = $connect->query($sql);
$service = $result->fetch_assoc();

if(!$service) {
    header("Location: ../../?tab=services");
    exit;
}

include "../../../inc/header.php";
?>

<div class="main-content" style="max-width: 600px;">
    <h1 class="page-title">✏️ Редактирование услуги</h1>
    
    <?php if(isset($_GET['error'])): ?>
        <div class="alert alert-error">
            <?php 
            $errors = [
                'empty_fields' => '❌ Название услуги обязательно!',
                'database_error' => '❌ Ошибка базы данных.',
                'not_found' => '❌ Услуга не найдена!'
            ];
            echo $errors[$_GET['error']] ?? '❌ Произошла ошибка';
            ?>
        </div>
    <?php endif; ?>
    
    <?php if(isset($_GET['success'])): ?>
        <div class="alert alert-success">
            ✅ Услуга успешно обновлена!
        </div>
    <?php endif; ?>
    
    <form action="process_service.php?action=edit" method="post">
        <input type="hidden" name="service_id" value="<?php echo $service['service_id']; ?>">
        
        <div class="form-group">
            <label for="name_service">🔧 Название услуги *</label>
            <input type="text" name="name_service" id="name_service" class="form-control" required 
                   value="<?php echo htmlspecialchars($service['name_service']); ?>">
        </div>
        
        <div class="form-group">
            <label for="category">📁 Категория</label>
            <select name="category" id="category" class="form-control" required>
                <option value="">-- Выберите категорию --</option>
                <option value="diagnostics" <?php echo $service['category'] == 'diagnostics' ? 'selected' : ''; ?>>Диагностика</option>
                <option value="engine" <?php echo $service['category'] == 'engine' ? 'selected' : ''; ?>>Двигатель</option>
                <option value="transmission" <?php echo $service['category'] == 'transmission' ? 'selected' : ''; ?>>КПП</option>
                <option value="brakes" <?php echo $service['category'] == 'brakes' ? 'selected' : ''; ?>>Тормоза</option>
                <option value="suspension" <?php echo $service['category'] == 'suspension' ? 'selected' : ''; ?>>Ходовая часть</option>
                <option value="electrical" <?php echo $service['category'] == 'electrical' ? 'selected' : ''; ?>>Электрика</option>
                <option value="bodywork" <?php echo $service['category'] == 'bodywork' ? 'selected' : ''; ?>>Кузовные работы</option>
                <option value="maintenance" <?php echo $service['category'] == 'maintenance' ? 'selected' : ''; ?>>Техническое обслуживание</option>
            </select>
        </div>
        
        <div class="form-group">
            <label for="description">📝 Описание услуги</label>
            <textarea name="description" id="description" class="form-control" rows="4"><?php echo htmlspecialchars($service['description'] ?? ''); ?></textarea>
        </div>
        
        <div class="form-group">
            <label for="base_price">💰 Базовая цена (руб.) *</label>
            <input type="number" name="base_price" id="base_price" class="form-control" required 
                   min="0" step="100" value="<?php echo $service['base_price']; ?>">
        </div>
        
        <div class="form-group">
            <label for="estimated_time">⏱️ Примерное время выполнения (минут)</label>
            <input type="number" name="estimated_time" id="estimated_time" class="form-control" 
                   min="15" step="15" value="<?php echo $service['estimated_time']; ?>">
        </div>
        
        <div class="form-group">
            <label for="status">📊 Статус</label>
            <select name="status" id="status" class="form-control">
                <option value="available" <?php echo $service['status'] == 'available' ? 'selected' : ''; ?>>Доступна</option>
                <option value="unavailable" <?php echo $service['status'] == 'unavailable' ? 'selected' : ''; ?>>Недоступна</option>
            </select>
        </div>
        
        <div style="display: flex; gap: 15px; margin-top: 30px;">
            <button type="submit" class="btn" style="flex: 1;">
                💾 Сохранить изменения
            </button>
            <a href="../../?tab=services" class="btn" style="background: #666; flex: 1; text-align: center;">
                ↩️ Назад к списку
            </a>
        </div>
    </form>
</div>

</body>
</html>