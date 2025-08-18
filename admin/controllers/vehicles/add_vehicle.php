<?php
session_start();
if(!isset($_SESSION['login'])){
    header ("Location: ../../../auth/");
    exit;
}

include "../../../function/connect.php";

$client_id = $_SESSION['client_id'];

if($_SESSION['role'] == 'admin') {
    include "../../../inc/header.php";
} else {
    include "../../../inc/header_user.php"; 
}
?>

<div class="main-content" style="max-width: 600px;">
    <h1 class="page-title">🚗 Добавление автомобиля</h1>
    
    <?php if(isset($_GET['error'])): ?>
        <div class="alert alert-error">
            <?php 
            $errors = [
                'empty_fields' => '❌ Все обязательные поля должны быть заполнены!',
                'vin_exists' => '❌ Автомобиль с таким VIN уже зарегистрирован!',
                'plate_exists' => '❌ Автомобиль с таким госномером уже зарегистрирован!',
                'database_error' => '❌ Ошибка базы данных.'
            ];
            echo $errors[$_GET['error']] ?? '❌ Произошла ошибка';
            ?>
        </div>
    <?php endif; ?>
    
    <?php if(isset($_GET['success'])): ?>
        <div class="alert alert-success">
            ✅ Автомобиль успешно добавлен!
        </div>
    <?php endif; ?>
    
    <form action="process_vehicle.php?action=add" method="post">
        <input type="hidden" name="client_id" value="<?php echo $client_id; ?>">
        
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-bottom: 20px;">
            <div class="form-group">
                <label for="brand">Марка *</label>
                <input type="text" name="brand" id="brand" class="form-control" required 
                       placeholder="Toyota">
            </div>
            
            <div class="form-group">
                <label for="model">Модель *</label>
                <input type="text" name="model" id="model" class="form-control" required 
                       placeholder="Camry">
            </div>
        </div>
        
        <div class="form-group">
            <label for="year">Год выпуска *</label>
            <input type="number" name="year" id="year" class="form-control" required 
                   min="1990" max="<?php echo date('Y') + 1; ?>" 
                   value="<?php echo date('Y'); ?>">
        </div>
        
        <div class="form-group">
            <label for="vin">VIN номер *</label>
            <input type="text" name="vin" id="vin" class="form-control" required 
                   placeholder="17-значный номер" maxlength="17">
        </div>
        
        <div class="form-group">
            <label for="license_plate">Государственный номер *</label>
            <input type="text" name="license_plate" id="license_plate" class="form-control" required 
                   placeholder="A123BC177" style="text-transform: uppercase;">
        </div>
        
        <div class="form-group">
            <label for="color">Цвет</label>
            <input type="text" name="color" id="color" class="form-control" 
                   placeholder="Черный">
        </div>
        
        <div class="form-group">
            <label for="mileage">Пробег (км)</label>
            <input type="number" name="mileage" id="mileage" class="form-control" 
                   min="0" value="0">
        </div>
        
        <div class="form-group">
            <label for="notes">Примечания</label>
            <textarea name="notes" id="notes" class="form-control" rows="3" 
                      placeholder="Особенности автомобиля, предыдущие ремонты..."></textarea>
        </div>
        
        <div style="display: flex; gap: 15px; margin-top: 30px;">
            <button type="submit" class="btn" style="flex: 1;">
                ✅ Добавить автомобиль
            </button>
            <a href="<?php echo $_SESSION['role'] == 'admin' ? '../../' : '../../../profile/'; ?>" class="btn" style="background: #666; flex: 1; text-align: center;">
                ↩️ Назад в профиль
            </a>
        </div>
    </form>
</div>

<script>
document.getElementById('license_plate').addEventListener('input', function() {
    this.value = this.value.toUpperCase();
});

document.getElementById('vin').addEventListener('input', function() {
    this.value = this.value.toUpperCase();
});
</script>

</body>
</html>