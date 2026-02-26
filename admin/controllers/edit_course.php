<?php
session_start();
if(!isset($_SESSION['login']) || $_SESSION['role'] != "admin"){
    header("Location: ../../auth/");
    exit;
}

include "../../function/connect.php";

// Получаем ID курса из GET-параметра
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if($id <= 0) {
    header("Location: ../index.php?tab=courses");
    exit;
}

// Получаем данные курса
$sql = "SELECT * FROM courses WHERE id_course = $id";
$result = $connect->query($sql);
$course = $result->fetch_assoc();

if(!$course) {
    header("Location: ../index.php?tab=courses");
    exit;
}

include "../../inc/header.php";
?>

<div class="main-content" style="max-width: 600px;">
    <h1 class="page-title">✏️ Редактирование курса</h1>
    
    <?php if(isset($_GET['error'])): ?>
        <div class="alert alert-error">
            <?php 
            $errors = [
                'empty_fields' => '❌ Название курса обязательно!',
                'database_error' => '❌ Ошибка базы данных.',
                'not_found' => '❌ Курс не найден!'
            ];
            echo $errors[$_GET['error']] ?? '❌ Произошла ошибка';
            ?>
        </div>
    <?php endif; ?>
    
    <?php if(isset($_GET['success'])): ?>
        <div class="alert alert-success">
            ✅ Курс успешно обновлен!
        </div>
    <?php endif; ?>
    
    <form action="process_edit_course.php" method="post">
        <input type="hidden" name="id_course" value="<?php echo $course['id_course']; ?>">
        
        <div class="form-group">
            <label for="name_kurs">🎓 Название курса *</label>
            <input type="text" name="name_kurs" id="name_kurs" class="form-control" required 
                   value="<?php echo htmlspecialchars($course['name_kurs']); ?>">
        </div>
        
        <div class="form-group">
            <label for="data_nachala">📅 Дата начала курса</label>
            <input type="date" name="data_nachala" id="data_nachala" class="form-control" 
                   value="<?php echo $course['data_nachala']; ?>">
        </div>
        
        <div class="form-group">
            <label for="payments">💰 Способ оплаты</label>
            <select name="payments" id="payments" class="form-control">
                <option value="">Выберите способ оплаты</option>
                <option value="Онлайн оплата" <?php echo $course['payments'] == 'Онлайн оплата' ? 'selected' : ''; ?>>Онлайн оплата</option>
                <option value="Банковский перевод" <?php echo $course['payments'] == 'Банковский перевод' ? 'selected' : ''; ?>>Банковский перевод</option>
                <option value="Наличные" <?php echo $course['payments'] == 'Наличные' ? 'selected' : ''; ?>>Наличные</option>
                <option value="Рассрочка" <?php echo $course['payments'] == 'Рассрочка' ? 'selected' : ''; ?>>Рассрочка</option>
                <option value="Бесплатно" <?php echo $course['payments'] == 'Бесплатно' ? 'selected' : ''; ?>>Бесплатно</option>
            </select>
        </div>
        
        <?php 
        // Проверяем, есть ли дополнительные поля в таблице
        $check_fields = $connect->query("SHOW COLUMNS FROM courses");
        $has_description = false;
        $has_price = false;
        $has_duration = false;
        
        while($row = $check_fields->fetch_assoc()) {
            if($row['Field'] == 'description') $has_description = true;
            if($row['Field'] == 'price') $has_price = true;
            if($row['Field'] == 'duration') $has_duration = true;
        }
        ?>
        
        <?php if($has_description): ?>
        <div class="form-group">
            <label for="description">📝 Описание курса</label>
            <textarea name="description" id="description" class="form-control" rows="4"><?php echo htmlspecialchars($course['description'] ?? ''); ?></textarea>
        </div>
        <?php endif; ?>
        
      <div class="form-group">
    <label for="price">💵 Стоимость курса (руб.) *</label>
    <input type="number" name="price" id="price" class="form-control" required 
           value="<?php echo $course['price'] ?? 0; ?>" min="0" step="100">
    <small style="color: #666; font-size: 12px;">Укажите 0 для бесплатного курса</small>
</div>
        
        <?php if($has_duration): ?>
        <div class="form-group">
            <label for="duration">⏱️ Продолжительность (часов)</label>
            <input type="number" name="duration" id="duration" class="form-control" 
                   value="<?php echo $course['duration'] ?? ''; ?>" min="1">
        </div>
        <?php endif; ?>
        
        <div style="display: flex; gap: 15px; margin-top: 30px;">
            <button type="submit" class="btn" style="flex: 1;">
                💾 Сохранить изменения
            </button>
            <a href="../index.php?tab=courses" class="btn" style="background: #666; flex: 1; text-align: center;">
                ↩️ Назад к списку
            </a>
            <a href="delete_course.php?id=<?php echo $course['id_course']; ?>" class="btn" 
               style="background: #c62828; flex: 1; text-align: center;"
               onclick="return confirm('Вы уверены, что хотите удалить этот курс?\\nЭто действие нельзя отменить.')">
               🗑️ Удалить курс
            </a>
        </div>
    </form>
</div>

</body>
</html>
