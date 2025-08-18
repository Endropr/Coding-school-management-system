<?php 
session_start();

if(isset($_SESSION['login'])){
    if($_SESSION['role'] == 'admin'){
        header("Location: ../admin/"); 
    } else {
        header("Location: ../profile/");
    }
    exit;
}


include "../inc/header_start.php";
?>

<div class="main-content" style="max-width: 500px;">
    <h1 class="page-title">🔐 Вход в систему автосервиса</h1>
    
    <?php if(isset($_GET['message'])): ?>
        <div class="alert alert-error">
            <?php echo htmlspecialchars($_GET['message']); ?>
        </div>
    <?php endif; ?>
    
    <form action="../controllers/login.php" method="post">
        <div class="form-group">
            <label for="login">👤 Ваш логин</label>
            <input type="text" name="login" id="login" class="form-control" required
                   placeholder="Введите логин">
        </div>
        
        <div class="form-group">
            <label for="password">🔒 Пароль</label>
            <input type="password" name="password" id="password" class="form-control" required
                   placeholder="Введите пароль">
        </div>
        
        <button type="submit" class="btn btn-block">Войти в систему</button>
    </form>
    
    <div style="text-align: center; margin-top: 20px;">
        <p>Еще нет аккаунта? <a href="../register/" class="link">Зарегистрируйтесь</a></p>
        <p style="margin-top: 10px; color: #666; font-size: 14px;">
            Тестовый доступ:<br>
            Клиент: логин <strong>ivanov</strong>, пароль <strong>123456</strong><br>
            Админ: логин <strong>admin</strong>, пароль <strong>admin123</strong>
        </p>
    </div>
</div>

</body>
</html>