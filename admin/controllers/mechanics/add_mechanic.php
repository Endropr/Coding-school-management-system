<?php
session_start();
if(!isset($_SESSION['login']) || $_SESSION['role'] != "admin"){
    die("Доступ запрещен. <a href='../../auth/'>Войдите</a> как администратор.");
}

include "../../../inc/header.php";
?>

<div class="main-content" style="max-width: 600px;">
    <h1 class="page-title">👨‍🔧 Добавление механика</h1>
    
   <?php if(isset($_GET['error'])): ?>
    <div class="alert alert-error">
        <?php 
        $errors = [
            'empty_fields' => '❌ Все обязательные поля должны быть заполнены!',
            'phone_exists' => '❌ Этот телефон уже зарегистрирован!',
            'phone_too_long' => '❌ Номер телефона слишком длинный (макс. 18 символов)!',
            'database_error' => '❌ Ошибка базы данных.'
        ];
        echo $errors[$_GET['error']] ?? '❌ Произошла ошибка';
        ?>
    </div>
<?php endif; ?>
    
    <form action="process_mechanic.php?action=add" method="post">
        <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 15px; margin-bottom: 20px;">
            <div class="form-group">
                <label for="surname">Фамилия *</label>
                <input type="text" name="surname" id="surname" class="form-control" required>
            </div>
            
            <div class="form-group">
                <label for="name">Имя *</label>
                <input type="text" name="name" id="name" class="form-control" required>
            </div>
            
            <div class="form-group">
                <label for="patronymic">Отчество *</label>
                <input type="text" name="patronymic" id="patronymic" class="form-control" required>
            </div>
        </div>
        
        <div class="form-group">
            <label for="specialization">🔧 Специализация</label>
            <input type="text" name="specialization" id="specialization" class="form-control" 
                   placeholder="Например: Двигатель, КПП, Электрика">
        </div>
        
       <div class="form-group">
    <label for="phone">📞 Телефон *</label>
    <input type="tel" name="phone" id="phone" class="form-control" required 
           placeholder="+7 (999) 123-45-67" maxlength="18"> 
        
        <div class="form-group">
            <label for="hourly_rate">💰 Ставка за час (руб.)</label>
            <input type="number" name="hourly_rate" id="hourly_rate" class="form-control" 
                   min="0" step="100" value="1000">
        </div>
        
        <div class="form-group">
            <label for="hired_date">📅 Дата приема на работу</label>
            <input type="date" name="hired_date" id="hired_date" class="form-control" 
                   value="<?php echo date('Y-m-d'); ?>">
        </div>
        
        <div class="form-group">
            <label for="status">📊 Статус</label>
            <select name="status" id="status" class="form-control">
                <option value="active">Работает</option>
                <option value="vacation">В отпуске</option>
            </select>
        </div>
        
        <div style="display: flex; gap: 15px; margin-top: 30px;">
            <button type="submit" class="btn" style="flex: 1;">
                ✅ Добавить механика
            </button>
            <a href="../../?tab=mechanics" class="btn" style="background: #666; flex: 1; text-align: center;">
                ↩️ Назад к списку
            </a>
        </div>
    </form>
</div>

<script>
document.getElementById('phone').addEventListener('input', function() {
    let value = this.value.replace(/\D/g, '');
    
    if (value.length > 0) {
        if (!value.startsWith('7') && !value.startsWith('8')) {
            value = '7' + value;
        }
        
        let formatted = '+7';
        if (value.length > 1) {
            formatted += ' (' + value.substring(1, 4);
        }
        if (value.length >= 4) {
            formatted += ') ' + value.substring(4, 7);
        }
        if (value.length >= 7) {
            formatted += '-' + value.substring(7, 9);
        }
        if (value.length >= 9) {
            formatted += '-' + value.substring(9, 11);
        }
        
        this.value = formatted;
    }
});
</script>

</body>
</html>