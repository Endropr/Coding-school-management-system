<?php
session_start();
if(!isset($_SESSION['login']) || $_SESSION['role'] != "admin"){
    header("Location: ../../../auth/");
    exit;
}

include "../../../function/connect.php";

// Получаем ID механика
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if($id <= 0) {
    header("Location: ../../?tab=mechanics");
    exit;
}

// Получаем данные механика
$sql = "SELECT * FROM mechanics WHERE mechanic_id = $id";
$result = $connect->query($sql);
$mechanic = $result->fetch_assoc();

if(!$mechanic) {
    header("Location: ../../?tab=mechanics");
    exit;
}

include "../../../inc/header.php";
?>

<div class="main-content" style="max-width: 600px;">
    <h1 class="page-title">✏️ Редактирование механика</h1>
    
    <form action="process_mechanic.php?action=edit" method="post">
        <input type="hidden" name="mechanic_id" value="<?php echo $mechanic['mechanic_id']; ?>">
        
        <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 15px; margin-bottom: 20px;">
            <div class="form-group">
                <label for="surname">Фамилия *</label>
                <input type="text" name="surname" id="surname" class="form-control" required 
                       value="<?php echo htmlspecialchars($mechanic['surname']); ?>">
            </div>
            
            <div class="form-group">
                <label for="name">Имя *</label>
                <input type="text" name="name" id="name" class="form-control" required 
                       value="<?php echo htmlspecialchars($mechanic['name']); ?>">
            </div>
            
            <div class="form-group">
                <label for="patronymic">Отчество *</label>
                <input type="text" name="patronymic" id="patronymic" class="form-control" required 
                       value="<?php echo htmlspecialchars($mechanic['patronymic']); ?>">
            </div>
        </div>
        
        <div class="form-group">
            <label for="specialization">🔧 Специализация</label>
            <input type="text" name="specialization" id="specialization" class="form-control" 
                   value="<?php echo htmlspecialchars($mechanic['specialization']); ?>">
        </div>
        
        <div class="form-group">
            <label for="phone">📞 Телефон *</label>
            <input type="tel" name="phone" id="phone" class="form-control" required 
                   value="<?php echo htmlspecialchars($mechanic['phone']); ?>">
        </div>
        
        <div class="form-group">
            <label for="hourly_rate">💰 Ставка за час (руб.)</label>
            <input type="number" name="hourly_rate" id="hourly_rate" class="form-control" 
                   min="0" step="100" value="<?php echo $mechanic['hourly_rate']; ?>">
        </div>
        
        <div class="form-group">
            <label for="status">📊 Статус</label>
            <select name="status" id="status" class="form-control">
                <option value="active" <?php echo $mechanic['status'] == 'active' ? 'selected' : ''; ?>>Работает</option>
                <option value="vacation" <?php echo $mechanic['status'] == 'vacation' ? 'selected' : ''; ?>>В отпуске</option>
                <option value="fired" <?php echo $mechanic['status'] == 'fired' ? 'selected' : ''; ?>>Уволен</option>
            </select>
        </div>
        
        <div style="display: flex; gap: 15px; margin-top: 30px;">
            <button type="submit" class="btn" style="flex: 1;">
                💾 Сохранить изменения
            </button>
            <a href="../../?tab=mechanics" class="btn" style="background: #666; flex: 1; text-align: center;">
                ↩️ Назад к списку
            </a>
        </div>
    </form>
</div>

</body>
</html>