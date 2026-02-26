<?php 
session_start();

// Если пользователь авторизован - редирект в профиль
if(isset($_SESSION['login'])){
    header("Location: profile/");
    exit;
}

// Для неавторизованных используем header_start.php
include "inc/header_start.php";
?>

<div class="main-content" style="text-align: center;">
    <h1 class="page-title" style="font-size: 48px; margin-bottom: 40px;">
        Добро пожаловать в CodeAcademy!
    </h1>
    
    <div style="max-width: 800px; margin: 0 auto;">
        <div class="card" style="margin-bottom: 30px;">
            <h2 class="card-title">🔹 Онлайн-курсы для всех</h2>
            <p style="font-size: 18px; margin-bottom: 20px;">
                Получите новые знания и навыки на наших курсах. 
                Выбирайте из трех направлений и начинайте обучение уже сегодня!
            </p>
            
            <!-- В блоке с курсами на главной -->
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin-top: 30px;">
    <div class="card">
        <h3 style="color: #1a5ddb;">🧿 Программирование</h3>
        <p>Основы алгоритмизации и программирования</p>
        <div style="margin-top: 10px;">
            <div style="color: #666; font-size: 14px;">📅 Ближайший старт: 1 апреля</div>
            <div style="color: #1a5ddb; font-weight: bold; margin-top: 5px;">от 15 000 ₽</div>
        </div>
    </div>
    <div class="card">
                    <h3 style="color: #1a5ddb;">🧿 Веб-дизайн</h3>
                    <p>Создание современных веб-интерфейсов</p>
                     <div style="color: #666; font-size: 14px;">📅 Ближайший старт: 1 апреля</div>
            <div style="color: #1a5ddb; font-weight: bold; margin-top: 5px;">от 15 000 ₽</div>
                </div>
                
                <div class="card">
                    <h3 style="color: #1a5ddb;">🧿 Базы данных</h3>
                    <p>Проектирование и работа с базами данных</p>
                     <div style="color: #666; font-size: 14px;">📅 Ближайший старт: 1 апреля</div>
            <div style="color: #1a5ddb; font-weight: bold; margin-top: 5px;">от 15 000 ₽</div>
                </div>
    <div class="card">
            <h2 class="card-title"> Начните прямо сейчас!</h2>
            <p style="margin-bottom: 30px; font-size: 18px;">
                Присоединяйтесь к сообществу учащихся и откройте для себя новые возможности 
            </p>  
               
    </div>
</div>
</div>
        
       
            
            <div style="display: flex; gap: 20px; justify-content: center; flex-wrap: wrap;">
                <a href="auth/" class="btn" style="min-width: 200px;">🧿Войти в систему</a>
                <a href="register/" class="btn" style="min-width: 200px;">🧿Зарегистрироваться</a>
            </div>
        </div>
    </div>
</div>
</body>
</html>
