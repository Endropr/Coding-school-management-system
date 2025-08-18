<?php
session_start();
if(!isset($_SESSION['login']) || $_SESSION['role'] != "admin"){
    header("Location: ../../../auth/");
    exit;
}

include "../../../function/connect.php";

// Получаем ID клиента
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if($id <= 0) {
    header("Location: ../../?tab=clients");
    exit;
}

// Получаем данные клиента
$sql = "SELECT * FROM clients WHERE client_id = $id AND role = 'client'";
$result = $connect->query($sql);
$client = $result->fetch_assoc();

if(!$client) {
    header("Location: ../../?tab=clients");
    exit;
}

include "../../../inc/header.php";
?>

<div class="main-content" style="max-width: 600px;">
    <h1 class="page-title">✏️ Редактирование клиента</h1>
    
    <form action="process_client.php" method="post">
        <input type="hidden" name="client_id" value="<?php echo $client['client_id']; ?>">
        
        <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 15px; margin-bottom: 20px;">
            <div class="form-group">
                <label for="surname">Фамилия</label>
                <input type="text" name="surname" id="surname" class="form-control" 
                       value="<?php echo htmlspecialchars($client['surname']); ?>" readonly>
            </div>
            
            <div class="form-group">
                <label for="name">Имя</label>
                <input type="text" name="name" id="name" class="form-control" 
                       value="<?php echo htmlspecialchars($client['name']); ?>" readonly>
            </div>
            
            <div class="form-group">
                <label for="patronymic">Отчество</label>
                <input type="text" name="patronymic" id="patronymic" class="form-control" 
                       value="<?php echo htmlspecialchars($client['patronymic']); ?>" readonly>
            </div>
        </div>
        
        <div class="form-group">
            <label for="login">👤 Логин</label>
            <input type="text" name="login" id="login" class="form-control" 
                   value="<?php echo htmlspecialchars($client['login']); ?>" readonly>
        </div>
        
        <div class="form-group">
            <label for="email">📧 Email</label>
            <input type="email" name="email" id="email" class="form-control" 
                   value="<?php echo htmlspecialchars($client['email']); ?>">
        </div>
        
        <div class="form-group">
            <label for="phone">📞 Телефон</label>
            <input type="tel" name="phone" id="phone" class="form-control" 
                   value="<?php echo htmlspecialchars($client['phone']); ?>">
        </div>
        
        <div class="form-group">
            <label for="discount_percent">🎫 Скидка (%)</label>
            <input type="number" name="discount_percent" id="discount_percent" class="form-control" 
                   min="0" max="50" value="<?php echo $client['discount_percent']; ?>">
        </div>
        
        <div class="form-group">
            <label for="status">📊 Статус</label>
            <select name="status" id="status" class="form-control">
                <option value="active" <?php echo $client['status'] == 'active' ? 'selected' : ''; ?>>Активен</option>
                <option value="inactive" <?php echo $client['status'] == 'inactive' ? 'selected' : ''; ?>>Неактивен</option>
            </select>
        </div>
        
        <div style="display: flex; gap: 15px; margin-top: 30px;">
            <button type="submit" class="btn" style="flex: 1;">
                💾 Сохранить изменения
            </button>
            <a href="../../?tab=clients" class="btn" style="background: #666; flex: 1; text-align: center;">
                ↩️ Назад к списку
            </a>
        </div>
    </form>
</div>

</body>
</html>
