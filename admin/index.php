<?php 
session_start();
if($_SESSION['role']!="admin"){
    header("Location: /profile/");
    exit;
}
include "../inc/header.php";
include "../function/function.php";

// Определяем, какую вкладку показать
$tab = isset($_GET['tab']) ? $_GET['tab'] : 'applications';
?>
<div class="main-content">
    <h1 class="page-title">🧿 Панель администратора</h1>
    <?php if(isset($_GET['success'])): ?>
    <div class="alert alert-success">
        <?php 
        $success_messages = [
            'deleted' => '✅ Курс успешно удален!',
            'added' => '✅ Курс успешно добавлен!',
            'updated' => '✅ Курс успешно обновлен!'
        ];
        echo $success_messages[$_GET['success']] ?? '✅ Операция выполнена успешно!';
        ?>
    </div>
<?php endif; ?>

<?php if(isset($_GET['error'])): ?>
    <div class="alert alert-error">
        <?php 
        $error_messages = [
            'course_has_applications' => '❌ Нельзя удалить курс, на который есть заявки!',
            'delete_failed' => '❌ Ошибка при удалении курса!',
            'not_found' => '❌ Курс не найден!'
        ];
        echo $error_messages[$_GET['error']] ?? '❌ Произошла ошибка!';
        ?>
    </div>
<?php endif; ?>
    <!-- Навигация по вкладкам -->
    <div class="tabs" style="margin-bottom: 30px; border-bottom: 2px solid #eee;">
        <div style="display: flex; gap: 10px;">
            <a href="?tab=applications" class="btn btn-sm <?php echo $tab == 'applications' ? 'selected' : ''; ?>" 
               style="background: <?php echo $tab == 'applications' ? '#1a5ddb' : '#666'; ?>;">
               🔹 Заявки
            </a>
            <a href="?tab=courses" class="btn btn-sm <?php echo $tab == 'courses' ? 'selected' : ''; ?>" 
               style="background: <?php echo $tab == 'courses' ? '#1a5ddb' : '#666'; ?>;">
               🔹 Курсы
            </a>
            <a href="?tab=users" class="btn btn-sm <?php echo $tab == 'users' ? 'selected' : ''; ?>" 
               style="background: <?php echo $tab == 'users' ? '#1a5ddb' : '#666'; ?>;">
               🔹 Пользователи
            </a>
        </div>
    </div>
    
    <!-- Содержимое вкладок -->
    <div class="tab-content">
        <?php if($tab == 'applications'): ?>
            <!-- Заявки -->
            <div class="card">
                <h2 class="card-title">🧿 Управление заявками</h2>
                <?php echo fnGetTablAdmin(); ?>
            </div>
            
        <?php elseif($tab == 'courses'): ?>
            <!-- Курсы -->
            <div class="card">
                <h2 class="card-title">🧿 Управление курсами</h2>
                <?php echo fnGetCoursesTableAdmin(); ?>
            </div>
            
        <?php elseif($tab == 'users'): ?>
            <!-- Пользователи -->
            <div class="card">
                <h2 class="card-title">🧿 Управление пользователями</h2>
                <?php 
                // Функция для таблицы пользователей
                $sql = "SELECT * FROM clients WHERE role = 'Пользователь' ORDER BY user_id DESC";
                $result = $connect->query($sql);
                
                if($result->num_rows > 0) {
                    echo '<table class="table table-striped">
                    <thead>
                    <tr>
                    <th>ID</th>
                    <th>ФИО</th>
                    <th>Логин</th>
                    <th>Email</th>
                    <th>Телефон</th>
                    <th>Дата регистрации</th>
                    <th>Действия</th>
                    </tr>
                    </thead>
                    <tbody>';
                    
                    while($row = $result->fetch_assoc()) {
                        echo sprintf('
                        <tr>
                        <td>%s</td>
                        <td>%s %s %s</td>
                        <td>%s</td>
                        <td>%s</td>
                        <td>%s</td>
                        <td>Зарегистрирован</td>
                        <td>
                            <a href="controllers/edit_user.php?id=%s" class="btn btn-sm" style="background: #2196F3;">✏️</a>
                        </td>
                        </tr>',
                        $row['user_id'],
                        htmlspecialchars($row['surname']),
                        htmlspecialchars($row['name']),
                        htmlspecialchars($row['patronymic']),
                        htmlspecialchars($row['login']),
                        htmlspecialchars($row['email']),
                        htmlspecialchars($row['phone']),
                        $row['user_id']
                        );
                    }
                    
                    echo '</tbody></table>';
                } else {
                    echo '<div class="alert alert-warning">Нет зарегистрированных пользователей</div>';
                }
                ?>
            </div>
        <?php endif; ?>
    </div>
    
    <!-- Статистика -->
    <div class="card" style="margin-top: 30px;">
        <h2 class="card-title">📊 Статистика системы</h2>
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px;">
            <?php
            // Получаем статистику
            $users_count = $connect->query("SELECT COUNT(*) as count FROM clients WHERE role = 'Пользователь'")->fetch_assoc()['count'];
            $applications_count = $connect->query("SELECT COUNT(*) as count FROM zayavka")->fetch_assoc()['count'];
            $courses_count = $connect->query("SELECT COUNT(*) as count FROM courses")->fetch_assoc()['count'];
            $active_applications = $connect->query("SELECT COUNT(*) as count FROM zayavka WHERE status = 'Новая'")->fetch_assoc()['count'];
            ?>
            
            <div class="card" style="text-align: center;">
                <h3 style="color: #1a5ddb; font-size: 32px;"><?php echo $users_count; ?></h3>
                <p>Всего пользователей</p>
            </div>
            
            <div class="card" style="text-align: center;">
                <h3 style="color: #1a5ddb; font-size: 32px;"><?php echo $applications_count; ?></h3>
                <p>Всего заявок</p>
            </div>
            
            <div class="card" style="text-align: center;">
                <h3 style="color: #1a5ddb; font-size: 32px;"><?php echo $courses_count; ?></h3>
                <p>Доступных курсов</p>
            </div>
            
            <div class="card" style="text-align: center;">
                <h3 style="color: #FF9800; font-size: 32px;"><?php echo $active_applications; ?></h3>
                <p>Новых заявок</p>
            </div>
        </div>
    </div>
</div>

<style>
    .badge {
        display: inline-block;
        padding: 4px 8px;
        background: #e3f2fd;
        color: #1976d2;
        border-radius: 12px;
        font-size: 12px;
        font-weight: bold;
    }
    
    .tabs .btn-sm.selected {
        box-shadow: 0 0 0 2px rgba(76, 175, 80, 0.3);
    }
</style>
</body>
</html>
