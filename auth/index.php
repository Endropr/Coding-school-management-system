<?php 
session_start();
if(isset($_SESSION['login'])){
    header("Location: ../profile/");
    exit;
}

// Используем header_start.php для неавторизованных пользователей
include "../inc/header_start.php";
?>

<div class="main-content" style="max-width: 500px;">
    <h1 class="page-title">Вход в систему</h1>
    
    <?php if(isset($_GET['message'])): ?>
        <div class="alert alert-error">
            <?php echo htmlspecialchars($_GET['message']); ?>
        </div>
    <?php endif; ?>
    
    <form action="../admin/controllers/login.php" method="post">
        <div class="form-group">
            <label for="login">👤 Ваш логин</label>
            <input type="text" name="login" id="login" class="form-control" required>
        </div>
        
        <div class="form-group">
            <label for="password">🔒 Пароль</label>
            <input type="password" name="password" id="password" class="form-control" required>
        </div>
        
        <button type="submit" class="btn btn-block">Войти</button>
    </form>
    
    <div style="text-align: center; margin-top: 20px;">
        <p>Еще нет аккаунта? <a href="../register/" class="link">Зарегистрируйтесь</a></p>
    </div>
</div>
</body>
</html>
