<?php 
include "connect.php";

function fnGetProfile ($login){
    global $connect;
    $sql = sprintf("SELECT surname, name, patronymic, phone, email 
    FROM clients WHERE login='%s'", $login);
    
    if(!$result = $connect->query($sql)){
        return '<div class="alert alert-error">Ошибка получения данных</div>';
    }
    
    $row = $result->fetch_assoc();
    
    if(!$row){
        return '<div class="alert alert-error">Данные пользователя не найдены</div>';
    }
    
    $data = '<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px;">';
    $data .= '<div><strong>👤 ФИО:</strong><br>' . htmlspecialchars($row['surname'] . ' ' . $row['name'] . ' ' . $row['patronymic']) . '</div>';
    $data .= '<div><strong>📞 Телефон:</strong><br>' . htmlspecialchars($row['phone']) . '</div>';
    $data .= '<div><strong>📧 Email:</strong><br>' . htmlspecialchars($row['email']) . '</div>';
    $data .= '<div><strong>👤 Логин:</strong><br>' . htmlspecialchars($login) . '</div>';
    $data .= '</div>';
    
    return $data;
}

function fnGetTablProfile ($login) {
    global $connect;
    
    // Получаем ID пользователя
    $select = "SELECT user_id FROM clients WHERE login='" . $connect->real_escape_string($login) . "'";
    $select_result = $connect->query($select);
    
    if(!$select_result){
        echo "Ошибка получения данных пользователя";
        return null;
    }
    
    $select_row = $select_result->fetch_assoc();
    
    if(!$select_row){
        return "<p>Пользователь не найден</p>";
    }
    
    $id = $select_row['user_id'];
    
    $data = '<table class="table table-striped">
    <thead>
    <tr>
    <th>ID Заявки</th>
    <th>Курс</th>
    <th>Цена</th>
    <th>Сообщение</th>
    <th>Дата подачи</th>
    <th>Статус</th>
    </tr>
    </thead>
    <tbody>';
    
    // Используем таблицу zayavka
    $sql = sprintf("SELECT 
        z.id_zayavka,
        c.name_kurs,
        c.price,
        z.message,
        z.time,
        z.status
    FROM zayavka z
    LEFT JOIN courses c ON z.id_course = c.id_course
    WHERE z.user_id = '%s' 
    ORDER BY z.id_zayavka DESC", $id);
    
    if(!$result = $connect->query($sql)){
        return '<div class="alert alert-warning">У вас пока нет заявок на курсы</div>';
    }
    
    if($result->num_rows == 0){
        return '<div class="alert alert-warning">У вас пока нет заявок на курсы</div>';
    }
    
    while($row = $result->fetch_assoc()){
        $status_class = '';
        if($row['status'] == 'Подтверждена') {
            $status_class = 'style="color: green; font-weight: bold;"';
            $status_icon = '✅';
        } elseif($row['status'] == 'Отменена') {
            $status_class = 'style="color: red;"';
            $status_icon = '❌';
        } else {
            $status_class = 'style="color: #FF9800;"';
            $status_icon = '⏳';
        }
        
        // Форматируем цену
        $price = $row['price'] ?? 0;
        $formatted_price = ($price > 0) ? number_format($price, 0, '', ' ') . ' ₽' : 'Бесплатно';
        $price_class = ($price > 0) ? '' : 'style="color: #1a5ddb; font-weight: bold;"';
        // В цикле while при выводе заявок добавьте:
$message_parts = explode("\n", $row['message']);
$course_details = '';

foreach ($message_parts as $part) {
    if (strpos($part, 'Дата начала:') === 0) {
        $course_details .= '<div style="color: #666; font-size: 12px;">📅 ' . substr($part, 13) . '</div>';
    }
    if (strpos($part, 'Способ оплаты:') === 0) {
        $course_details .= '<div style="color: #666; font-size: 12px;">💰 ' . substr($part, 15) . '</div>';
    }
}
        $data .= sprintf('
        <tr>
        <td>%s</td>
        <td><strong>%s</strong></td>
        <td %s>%s</td>
        <td>%s</td>
        <td>%s</td>
        <td %s>%s %s</td>
        </tr>',
        htmlspecialchars($row['id_zayavka'] ?? ''),
        htmlspecialchars($row['name_kurs'] ?? 'Не указан'),
        $price_class,
        $formatted_price,
        htmlspecialchars(mb_strlen($row['message'] ?? '', 'UTF-8') > 50 ? mb_substr($row['message'], 0, 47, 'UTF-8') . '...' : $row['message'] ?? ''),
        htmlspecialchars($row['time'] ?? 'Не указана'),
        $status_class,
        $status_icon,
        htmlspecialchars($row['status'] ?? 'Новая')
        );
    }
    
    $data .= "</tbody></table>";
    return $data;
}

function fnGetTablAdmin(){
    global $connect;
    
    $data = '<table class="table">
    <thead>
    <tr>
    <th>ID Заявки</th>
    <th>Пользователь</th>
    <th>Курс</th>
    <th>Цена</th>
    <th>Сообщение</th>
    <th>Дата подачи</th>
    <th>Статус</th>
    <th>Действия</th>
    </tr>
    </thead>
    <tbody>';
    
    $sql = "SELECT 
        z.id_zayavka,
        CONCAT(c.surname, ' ', c.name, ' ', c.patronymic) as user_name,
        cr.name_kurs,
        cr.price,
        z.message,
        z.time,
        z.status
    FROM zayavka z
    INNER JOIN clients c ON z.user_id = c.user_id
    INNER JOIN courses cr ON z.id_course = cr.id_course
    ORDER BY z.id_zayavka DESC";
    
    if(!$result = $connect->query($sql)){
        return '<div class="alert alert-error">Ошибка получения данных заявок</div>';
    }
    
    if($result->num_rows == 0){
        return '<div class="alert alert-warning">Нет заявок для обработки</div>';
    }
    
    while($row = $result->fetch_assoc()){
        $status = $row['status'] ?? 'Новая';
        
        // Форматируем цену
        $price = $row['price'] ?? 0;
        $formatted_price = ($price > 0) ? number_format($price, 0, '', ' ') . ' ₽' : '<span style="color: #1a5ddb;">Бесплатно</span>';
        // В цикле while при выводе заявок добавьте:
$message_parts = explode("\n", $row['message']);
$course_details = '';

foreach ($message_parts as $part) {
    if (strpos($part, 'Дата начала:') === 0) {
        $course_details .= '<div style="color: #666; font-size: 12px;">📅 ' . substr($part, 13) . '</div>';
    }
    if (strpos($part, 'Способ оплаты:') === 0) {
        $course_details .= '<div style="color: #666; font-size: 12px;">💰 ' . substr($part, 15) . '</div>';
    }
}
        if($status == 'Отменена' || $status == 'Подтверждена'){
            $status_color = $status == 'Подтверждена' ? 'green' : 'red';
            $data .= sprintf('
            <tr>
            <td>%s</td>
            <td>%s</td>
            <td>%s</td>
            <td>%s</td>
            <td>%s</td>
            <td>%s</td>
            <td style="color: %s; font-weight: bold;">%s</td>
            <td><span style="color: #888;">Обработано</span></td>
            </tr>',
            htmlspecialchars($row['id_zayavka']),
            htmlspecialchars($row['user_name']),
            htmlspecialchars($row['name_kurs']),
            $formatted_price,
            htmlspecialchars(mb_strlen($row['message'] ?? '', 'UTF-8') > 30 ? mb_substr($row['message'], 0, 27, 'UTF-8') . '...' : $row['message'] ?? ''),
            htmlspecialchars($row['time']),
            $status_color,
            htmlspecialchars($status)
            );
        } else {
            $data .= sprintf('
            <tr>
            <td>%s</td>
            <td>%s</td>
            <td>%s</td>
            <td>%s</td>
            <td>%s</td>
            <td>%s</td>
            <td><span class="alert-warning" style="padding: 4px 8px; border-radius: 4px;">%s</span></td>
            <td>
                <div style="display: flex; gap: 10px;">
                    <a href="controllers/update_applicate.php?id=%s&action=success" class="btn btn-sm" style="background: green;">✓ Подтвердить</a>
                    <a href="controllers/update_applicate.php?id=%s&action=cancel" class="btn btn-sm" style="background: #c62828;">✗ Отменить</a>
                </div>
            </td>
            </tr>',
            htmlspecialchars($row['id_zayavka']),
            htmlspecialchars($row['user_name']),
            htmlspecialchars($row['name_kurs']),
            $formatted_price,
            htmlspecialchars(mb_strlen($row['message'] ?? '', 'UTF-8') > 30 ? mb_substr($row['message'], 0, 27, 'UTF-8') . '...' : $row['message'] ?? ''),
            htmlspecialchars($row['time']),
            htmlspecialchars($status),
            htmlspecialchars($row['id_zayavka']),
            htmlspecialchars($row['id_zayavka'])
            );
        }
    }
    
    $data .= "</tbody></table>";
    return $data;
}

// Функция для получения списка курсов
  function fnGetCourses() {
    global $connect;
    
    $sql = "SELECT id_course, name_kurs, price, data_nachala, payments, duration, description 
            FROM courses 
            ORDER BY id_course";
    
    $result = $connect->query($sql);
    
    $courses = [];
    if($result) {
        while($row = $result->fetch_assoc()) {
            $courses[] = $row;
        }
    }
    
    return $courses;
}
function fnGetAllCourses() {
    global $connect;
    
    $sql = "SELECT * FROM courses ORDER BY id_course";
    
    if(!$result = $connect->query($sql)){
        return '<div class="alert alert-error">Ошибка получения данных курсов</div>';
    }
    
    return $result;
}

// Функция для получения курса по ID
function fnGetCourseById($id) {
    global $connect;
    
    $id = intval($id);
    $sql = "SELECT * FROM courses WHERE id_course = $id";
    
    if(!$result = $connect->query($sql)){
        return null;
    }
    
    return $result->fetch_assoc();
}

// Функция для отображения таблицы курсов в админке
function fnGetCoursesTableAdmin() {
    global $connect;
    
    $data = '<table class="table table-striped">
    <thead>
    <tr>
    <th>ID</th>
    <th>Название курса</th>
    <th>Цена</th>
    <th>Дата начала</th>
    <th>Способ оплаты</th>
    <th>Количество заявок</th>
    <th>Действия</th>
    </tr>
    </thead>
    <tbody>';
    
    $sql = "SELECT 
        c.*,
        COUNT(z.id_zayavka) as applications_count
    FROM courses c
    LEFT JOIN zayavka z ON c.id_course = z.id_course
    GROUP BY c.id_course
    ORDER BY c.id_course";
    
    if(!$result = $connect->query($sql)){
        return '<div class="alert alert-error">Ошибка получения данных курсов</div>';
    }
    
    if($result->num_rows == 0){
        return '<div class="alert alert-warning">Нет доступных курсов</div>';
    }
    
    while($row = $result->fetch_assoc()){
        // Форматируем цену
        $price = $row['price'] ?? 0;
        $formatted_price = ($price > 0) ? number_format($price, 0, '', ' ') . ' ₽' : '<span style="color: #1a5ddb; font-weight: bold;">Бесплатно</span>';
        
        $data .= sprintf('
        <tr>
        <td>%s</td>
        <td><strong>%s</strong></td>
        <td>%s</td>
        <td>%s</td>
        <td>%s</td>
        <td><span class="badge">%s</span></td>
        <td>
            <div style="display: flex; gap: 10px;">
                <a href="controllers/edit_course.php?id=%s" class="btn btn-sm" style="background: #2196F3;">✏️ Редактировать</a>
                <a href="controllers/delete_course.php?id=%s" class="btn btn-sm" style="background: #c62828;" 
                   onclick="return confirm(\'Вы уверены, что хотите удалить курс?\\nЭто действие нельзя отменить.\')">🗑️ Удалить</a>
            </div>
        </td>
        </tr>',
        htmlspecialchars($row['id_course']),
        htmlspecialchars($row['name_kurs']),
        $formatted_price,
        htmlspecialchars($row['data_nachala'] ? $row['data_nachala'] : 'Не указана'),
        htmlspecialchars($row['payments'] ? $row['payments'] : 'Не указан'),
        htmlspecialchars($row['applications_count']),
        htmlspecialchars($row['id_course']),
        htmlspecialchars($row['id_course'])
        );
    }
    
    $data .= "</tbody></table>";
    
    // Кнопка добавления нового курса
    $data .= '<div style="margin-top: 30px; text-align: center;">
        <a href="controllers/add_course.php" class="btn" style="padding: 12px 30px;">
            ➕ Добавить новый курс
        </a>
    </div>';
    
    return $data;
}
?>