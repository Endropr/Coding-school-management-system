<?php
session_start();

// Простая проверка авторизации без редиректов
if(!isset($_SESSION['login']) || $_SESSION['role'] != "admin"){
    // Просто завершаем с сообщением
    die("Доступ запрещен. <a href='../../auth/'>Войдите</a> как администратор.");
}

include "../../inc/header.php";
?>

<div class="main-content" style="max-width: 600px;">
    <h1 class="page-title">➕ Добавление нового курса</h1>
    
    <?php if(isset($_GET['error'])): ?>
        <div class="alert alert-error">
            <?php 
            $errors = [
                'empty_fields' => '❌ Все поля обязательны для заполнения!',
                'database_error' => '❌ Ошибка базы данных.',
                'course_exists' => '❌ Курс с таким названием уже существует!'
            ];
            echo $errors[$_GET['error']] ?? '❌ Произошла ошибка';
            ?>
        </div>
    <?php endif; ?>
    
    <?php if(isset($_GET['success'])): ?>
        <div class="alert alert-success">
            ✅ Курс успешно добавлен!
        </div>
    <?php endif; ?>
    
    <form action="process_add_course.php" method="post">
        <div class="form-group">
            <label for="name_kurs">🎓 Название курса *</label>
            <input type="text" name="name_kurs" id="name_kurs" class="form-control" required 
                   placeholder="Введите название курса">
        </div>
        
        <div class="form-group">
            <label for="data_nachala">📅 Дата начала курса</label>
            <input type="date" name="data_nachala" id="data_nachala" class="form-control">
        </div>
        
        <div class="form-group">
            <label for="payments">💰 Способ оплаты</label>
            <select name="payments" id="payments" class="form-control">
                <option value="">Выберите способ оплаты</option>
                <option value="Онлайн оплата">Онлайн оплата</option>
                <option value="Банковский перевод">Банковский перевод</option>
                <option value="Наличные">Наличные</option>
                <option value="Рассрочка">Рассрочка</option>
                <option value="Бесплатно">Бесплатно</option>
            </select>
        </div>
 <div class="form-group">
    <label for="price">💵 Стоимость курса (руб.) *</label>
    <input type="number" name="price" id="price" class="form-control" required 
           value="<?php echo $course['price'] ?? 0; ?>" min="0" step="100">
    <small style="color: #666; font-size: 12px;">Укажите 0 для бесплатного курса</small>
</div>
        <div style="display: flex; gap: 15px; margin-top: 30px;">
            <button type="submit" class="btn" style="flex: 1;">
                ✅ Добавить курс
            </button>
            <a href="../?tab=courses" class="btn" style="background: #666; flex: 1; text-align: center;">
                ↩️ Назад к списку
            </a>
        </div>
    </form>
</div>

</body>
</html>
