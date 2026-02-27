<?php 
session_start();
if(isset($_SESSION['login'])){
    header("Location: ../profile/");
    exit;
}

// Используем header_start.php для неавторизованных пользователей
include "../inc/header_start.php";
?>

<div class="main-content" style="max-width: 700px;">
    <h1 class="page-title">🔹 Регистрация</h1>
   
<?php if(isset($_GET['error'])): ?>
    <div class="alert alert-error" style="margin-bottom: 20px;">
        <?php 
        $errors = [
            'password_mismatch' => '❌ Пароли не совпадают!',
            'password_short' => '❌ Пароль должен содержать минимум 6 символов!',
            'login_exists' => '❌ Этот логин уже занят!',
            'email_exists' => '❌ Этот email уже зарегистрирован!',
            'phone_exists' => '❌ Этот номер телефона уже зарегистрирован!',
            'phone_too_long' => '❌ Номер телефона слишком длинный (макс. 13 символов)!',
            'invalid_phone' => '❌ Неверный формат номера телефона!',
            'empty_fields' => '❌ Все поля обязательны для заполнения!',
            'database_error' => '❌ Ошибка базы данных. Попробуйте позже.'
        ];
        echo $errors[$_GET['error']] ?? '❌ Произошла ошибка';
        
        // Показываем детали ошибки, если есть
        if (isset($_GET['details'])) {
            echo '<br><small>' . htmlspecialchars(urldecode($_GET['details'])) . '</small>';
        }
        ?>
    </div>
<?php endif; ?>


    <form action="../admin/controllers/registration.php" method="post">
        <!-- ФИО в одной строке -->
        <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 15px; margin-bottom: 20px;">
            <div class="form-group">
                <label for="surname">Фамилия *</label>
                <input type="text" name="surname" id="surname" class="form-control" required 
                       placeholder="Иванов">
            </div>
            
            <div class="form-group">
                <label for="name">Имя *</label>
                <input type="text" name="name" id="name" class="form-control" required 
                       placeholder="Иван">
            </div>
            
            <div class="form-group">
                <label for="patronymic">Отчество *</label>
                <input type="text" name="patronymic" id="patronymic" class="form-control" required 
                       placeholder="Иванович">
            </div>
        </div>
        
        <!-- Основные данные -->
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
            <div class="form-group">
                <label for="login">👤 Логин *</label>
                <input type="text" name="login" id="login" class="form-control" required 
                       placeholder="Придумайте логин">
                <small style="color: #666; font-size: 12px;">Будет использоваться для входа</small>
            </div>
            
            <div class="form-group">
                <label for="email">📧 Email *</label>
                <input type="email" name="email" id="email" class="form-control" required 
                       placeholder="example@mail.ru">
                <small style="color: #666; font-size: 12px;">На этот email придут уведомления</small>
            </div>
        </div>
        
        <!-- Контактные данные -->
        <div class="form-group" style="margin-bottom: 20px;">
            <label for="phone">📞 Телефон *</label>
            <input type="tel" name="phone" id="phone" class="form-control" required 
                   placeholder="+7 (999) 123-45-67" pattern="\+?[0-9\s\-\(\)]+">
            <small style="color: #666; font-size: 12px;">Формат: +7 (XXX) XXX-XX-XX</small>
        </div>
        
        <!-- Пароли -->
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 30px;">
            <div class="form-group">
                <label for="password">🔒 Пароль *</label>
                <input type="password" name="password" id="password" class="form-control" required 
                       placeholder="Минимум 6 символов" minlength="6">
                <small style="color: #666; font-size: 12px;">Не менее 6 символов</small>
            </div>
            
            <div class="form-group">
                <label for="password-repeat">🔁 Подтверждение пароля *</label>
                <input type="password" name="password-repeat" id="password-repeat" class="form-control" required 
                       placeholder="Повторите пароль">
                <small style="color: #666; font-size: 12px;">Пароли должны совпадать</small>
            </div>
        </div>
        
        <!-- Кнопка отправки -->
        <div style="text-align: center;">
            <button type="submit" class="btn" style="padding: 14px 40px; font-size: 18px;">
                🧿 Зарегистрироваться
            </button>
        </div>
        
        <!-- Валидация паролей -->
        <div id="password-match" style="display: none; margin-top: 10px; padding: 10px; 
             border-radius: 5px; text-align: center;"></div>
    </form>
    
    <!-- Ссылка на авторизацию -->
    <div style="text-align: center; margin-top: 30px; padding-top: 20px; border-top: 1px solid #eee;">
        <p style="color: #666; margin-bottom: 10px;">Уже есть аккаунт?</p>
        <a href="../auth/" class="btn" style="background: #666; min-width: 200px;">
            🔐 Войти в систему
        </a>
    </div>
</div>

<!-- Скрипт для проверки паролей -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const password = document.getElementById('password');
    const passwordRepeat = document.getElementById('password-repeat');
    const matchDiv = document.getElementById('password-match');
    // Добавьте в существующий скрипт
const phoneInput = document.getElementById('phone');

// Проверка телефона
function validatePhone() {
    const phone = phoneInput.value;
    const phoneDigits = phone.replace(/\D/g, '');
    
    if (phoneDigits.length < 10) {
        phoneInput.style.borderColor = '#c62828';
        return false;
    }
    
    if (phone.length > 13) {
        phoneInput.style.borderColor = '#c62828';
        return false;
    }
    
    phoneInput.style.borderColor = '#1a5ddb';
    return true;
}

phoneInput.addEventListener('input', function() {
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
    
    validatePhone();
});

document.querySelector('form').addEventListener('submit', function(e) {
    // ... существующие проверки ...
    
    if (!validatePhone()) {
        e.preventDefault();
        alert('Пожалуйста, введите корректный номер телефона (минимум 10 цифр, максимум 13 символов)');
        phoneInput.focus();
        return false;
    }
    
    return true;
});
    function checkPasswordMatch() {
        if (password.value && passwordRepeat.value) {
            if (password.value === passwordRepeat.value) {
                matchDiv.innerHTML = '<span style="color: #4CAF50;">✅ Пароли совпадают</span>';
                matchDiv.style.backgroundColor = '#e8f5e9';
                matchDiv.style.display = 'block';
            } else {
                matchDiv.innerHTML = '<span style="color: #c62828;">❌ Пароли не совпадают</span>';
                matchDiv.style.backgroundColor = '#ffebee';
                matchDiv.style.display = 'block';
            }
        } else {
            matchDiv.style.display = 'none';
        }
    }
    
    password.addEventListener('input', checkPasswordMatch);
    passwordRepeat.addEventListener('input', checkPasswordMatch);
    
    // Проверка формы при отправке
    document.querySelector('form').addEventListener('submit', function(e) {
        if (password.value !== passwordRepeat.value) {
            e.preventDefault();
            alert('Пароли не совпадают! Пожалуйста, проверьте введенные данные.');
            password.focus();
            return false;
        }
        
        if (password.value.length < 6) {
            e.preventDefault();
            alert('Пароль должен содержать минимум 6 символов!');
            password.focus();
            return false;
        }
        
        return true;
    });
});
</script>

</body>
</html>
