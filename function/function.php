<?php 
include "connect.php";

function fnGetClientProfile($login){
    global $connect;
    $sql = sprintf("SELECT surname, name, patronymic, phone, email, discount_percent, registration_date 
    FROM clients WHERE login='%s'", $login);
    
    if(!$result = $connect->query($sql)){
        return '<div class="alert alert-error">Ошибка получения данных</div>';
    }
    
    $row = $result->fetch_assoc();
    
    if(!$row){
        return '<div class="alert alert-error">Данные клиента не найдены</div>';
    }
    
    $data = '<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px;">';
    $data .= '<div><strong>👤 ФИО:</strong><br>' . htmlspecialchars($row['surname'] . ' ' . $row['name'] . ' ' . $row['patronymic']) . '</div>';
    $data .= '<div><strong>📞 Телефон:</strong><br>' . htmlspecialchars($row['phone']) . '</div>';
    $data .= '<div><strong>📧 Email:</strong><br>' . htmlspecialchars($row['email']) . '</div>';
    $data .= '<div><strong>👤 Логин:</strong><br>' . htmlspecialchars($login) . '</div>';
    $data .= '<div><strong>🎫 Скидка:</strong><br>' . htmlspecialchars($row['discount_percent']) . '%</div>';
    $data .= '<div><strong>📅 Дата регистрации:</strong><br>' . htmlspecialchars($row['registration_date']) . '</div>';
    $data .= '</div>';
    
    return $data;
}

function fnGetClientVehicles($client_id){
    global $connect;
    
    $data = '<table class="table table-striped">
    <thead>
    <tr>
    <th>ID</th>
    <th>Марка</th>
    <th>Модель</th>
    <th>Год</th>
    <th>Госномер</th>
    <th>Цвет</th>
    <th>Пробег</th>
    <th>VIN</th>
    </tr>
    </thead>
    <tbody>';
    
    $sql = sprintf("SELECT * FROM vehicles WHERE client_id='%s' ORDER BY vehicle_id", $client_id);
    
    if(!$result = $connect->query($sql)){
        return '<div class="alert alert-warning">У вас пока нет зарегистрированных автомобилей</div>';
    }
    
    if($result->num_rows == 0){
        return '<div class="alert alert-warning">У вас пока нет зарегистрированных автомобилей</div>';
    }
    
    while($row = $result->fetch_assoc()){
        $data .= sprintf('
        <tr>
        <td>%s</td>
        <td><strong>%s</strong></td>
        <td>%s</td>
        <td>%s</td>
        <td>%s</td>
        <td>%s</td>
        <td>%s км</td>
        <td><small>%s</small></td>
        </tr>',
        htmlspecialchars($row['vehicle_id']),
        htmlspecialchars($row['brand']),
        htmlspecialchars($row['model']),
        htmlspecialchars($row['year']),
        htmlspecialchars($row['license_plate']),
        htmlspecialchars($row['color']),
        number_format($row['mileage'], 0, '', ' '),
        htmlspecialchars($row['vin'])
        );
    }
    
    $data .= "</tbody></table>";
    return $data;
}

// Функция для получения заказов клиента
function fnGetClientOrders($client_id) {
    global $connect;
    
    $data = '<table class="table table-striped">
    <thead>
    <tr>
    <th>ID Заказа</th>
    <th>Дата</th>
    <th>Автомобиль</th>
    <th>Услуга</th>
    <th>Механик</th>
    <th>Стоимость</th>
    <th>Статус</th>
    </tr>
    </thead>
    <tbody>';
    
    $sql = sprintf("SELECT wo.order_id, wo.order_date, CONCAT(v.brand, ' ', v.model) as vehicle,
     s.name_service as service, CONCAT(m.surname, ' ', m.name) as mechanic, wo.total_price, wo.status
    FROM work_orders wo INNER JOIN vehicles v ON wo.vehicle_id = v.vehicle_id INNER JOIN services s ON wo.service_id = s.service_id
    INNER JOIN mechanics m ON wo.mechanic_id = m.mechanic_id WHERE wo.client_id = '%s' ORDER BY wo.order_date DESC", $client_id);
    
    if(!$result = $connect->query($sql)){
        return '<div class="alert alert-warning">У вас пока нет заказов</div>';
    }
    
    if($result->num_rows == 0){
        return '<div class="alert alert-warning">У вас пока нет заказов</div>';
    }
    
    while($row = $result->fetch_assoc()){
        $status_class = '';
        $status_icon = '';
        
        switch($row['status']) {
            case 'completed':
                $status_class = 'style="color: green; font-weight: bold;"';
                $status_icon = '✅';
                break;
            case 'cancelled':
                $status_class = 'style="color: red;"';
                $status_icon = '❌';
                break;
            case 'in_progress':
                $status_class = 'style="color: #FF9800;"';
                $status_icon = '🔧';
                break;
            case 'waiting_parts':
                $status_class = 'style="color: #9C27B0;"';
                $status_icon = '⏳';
                break;
            default:
                $status_class = 'style="color: #2196F3;"';
                $status_icon = '📋';
        }
        
        $data .= sprintf('
        <tr>
        <td>%s</td>
        <td>%s</td>
        <td>%s</td>
        <td>%s</td>
        <td>%s</td>
        <td>%s ₽</td>
        <td %s>%s %s</td>
        </tr>',
        htmlspecialchars($row['order_id']),
        htmlspecialchars($row['order_date']),
        htmlspecialchars($row['vehicle']),
        htmlspecialchars($row['service']),
        htmlspecialchars($row['mechanic']),
        number_format($row['total_price'], 0, '', ' '),
        $status_class,
        $status_icon,
        htmlspecialchars($row['status'] == 'in_progress' ? 'В работе' : 
                         ($row['status'] == 'waiting_parts' ? 'Ожидает запчасти' : 
                         ($row['status'] == 'completed' ? 'Завершен' : 
                         ($row['status'] == 'cancelled' ? 'Отменен' : 'Новый'))))
        );
    }
    
    $data .= "</tbody></table>";
    return $data;
}

// Функция для получения ID клиента по логину
function fnGetClientId($login){
    global $connect;
    $sql = sprintf("SELECT client_id FROM clients WHERE login='%s'", $login);
    $result = $connect->query($sql);
    if($result && $result->num_rows > 0){
        $row = $result->fetch_assoc();
        return $row['client_id'];
    }
    return 0;
}

// Функция для получения списка услуг (для админки)
function fnGetServicesAdmin(){
    global $connect;
    
    $data = '<table class="table table-striped">
    <thead>
    <tr>
    <th>ID</th>
    <th>Услуга</th>
    <th>Категория</th>
    <th>Базовая цена</th>
    <th>Время</th>
    <th>Статус</th>
    <th>Действия</th>
    </tr>
    </thead>
    <tbody>';
    
    $sql = "SELECT * FROM services ORDER BY service_id";
    
    if(!$result = $connect->query($sql)){
        return '<div class="alert alert-error">Ошибка получения данных услуг</div>';
    }
    
    if($result->num_rows == 0){
        return '<div class="alert alert-warning">Нет доступных услуг</div>';
    }
    
    while($row = $result->fetch_assoc()){
        $data .= sprintf('
        <tr>
        <td>%s</td>
        <td><strong>%s</strong></td>
        <td>%s</td>
        <td>%s ₽</td>
        <td>%s мин</td>
        <td>%s</td>
        <td>
            <div style="display: flex; gap: 10px;">
                <a href="controllers/services/edit_service.php?id=%s" class="btn btn-sm" style="background: #2196F3;">✏️ Редактировать</a>
                <a href="controllers/services/delete_service.php?id=%s" class="btn btn-sm" style="background: #c62828;" 
                   onclick="return confirm(\'Вы уверены, что хотите удалить услугу?\')">🗑️ Удалить</a>
            </div>
        </td>
        </tr>',
        htmlspecialchars($row['service_id']),
        htmlspecialchars($row['name_service']),
        htmlspecialchars($row['category']),
        number_format($row['base_price'], 0, '', ' '),
        htmlspecialchars($row['estimated_time']),
        htmlspecialchars($row['status'] == 'available' ? 'Доступна' : 'Недоступна'),
        htmlspecialchars($row['service_id']),
        htmlspecialchars($row['service_id'])
        );
    }
    
    $data .= "</tbody></table>";
    
    $data .= '<div style="margin-top: 30px; text-align: center;">
        <a href="controllers/services/add_service.php" class="btn" style="padding: 12px 30px;">
            ➕ Добавить новую услугу
        </a>
    </div>';
    
    return $data;
}

// Функция для получения заказов в админке
function fnGetOrdersAdmin(){
    global $connect;
    
    $data = '<table class="table">
    <thead>
    <tr>
    <th>ID Заказа</th>
    <th>Клиент</th>
    <th>Автомобиль</th>
    <th>Услуга</th>
    <th>Механик</th>
    <th>Дата</th>
    <th>Сумма</th>
    <th>Статус</th>
    <th>Действия</th>
    </tr>
    </thead>
    <tbody>';
    
    $sql = "SELECT 
        wo.order_id,
        CONCAT(c.surname, ' ', c.name) as client_name,
        CONCAT(v.brand, ' ', v.model, ' (', v.license_plate, ')') as vehicle,
        s.name_service,
        CONCAT(m.surname, ' ', m.name) as mechanic_name,
        wo.order_date,
        wo.total_price,
        wo.status
    FROM work_orders wo
    INNER JOIN clients c ON wo.client_id = c.client_id
    INNER JOIN vehicles v ON wo.vehicle_id = v.vehicle_id
    INNER JOIN services s ON wo.service_id = s.service_id
    INNER JOIN mechanics m ON wo.mechanic_id = m.mechanic_id
    ORDER BY wo.order_date DESC";
    
    if(!$result = $connect->query($sql)){
        return '<div class="alert alert-error">Ошибка получения данных заказов</div>';
    }
    
    if($result->num_rows == 0){
        return '<div class="alert alert-warning">Нет заказов</div>';
    }
    
    while($row = $result->fetch_assoc()){
        $status = $row['status'];
        
        if($status == 'completed' || $status == 'cancelled'){
            $status_color = $status == 'completed' ? 'green' : 'red';
            $status_text = $status == 'completed' ? 'Завершен' : 'Отменен';
            $data .= sprintf('
            <tr>
            <td>%s</td>
            <td>%s</td>
            <td>%s</td>
            <td>%s</td>
            <td>%s</td>
            <td>%s</td>
            <td>%s ₽</td>
            <td style="color: %s; font-weight: bold;">%s</td>
            <td><span style="color: #888;">Обработано</span></td>
            </tr>',
            htmlspecialchars($row['order_id']),
            htmlspecialchars($row['client_name']),
            htmlspecialchars($row['vehicle']),
            htmlspecialchars($row['name_service']),
            htmlspecialchars($row['mechanic_name']),
            htmlspecialchars($row['order_date']),
            number_format($row['total_price'], 0, '', ' '),
            $status_color,
            htmlspecialchars($status_text)
            );
        } else {
            $status_text = $status == 'in_progress' ? 'В работе' : 
                          ($status == 'waiting_parts' ? 'Ожидает запчасти' : 'Новый');
            
            $data .= sprintf('
            <tr>
            <td>%s</td>
            <td>%s</td>
            <td>%s</td>
            <td>%s</td>
            <td>%s</td>
            <td>%s</td>
            <td>%s ₽</td>
            <td><span class="alert-warning" style="padding: 4px 8px; border-radius: 4px;">%s</span></td>
            <td>
                <div style="display: flex; gap: 10px;">
                    <a href="controllers/update_order.php?id=%s&action=complete" class="btn btn-sm" style="background: green;">✓ Завершить</a>
                    <a href="controllers/update_order.php?id=%s&action=progress" class="btn btn-sm" style="background: #FF9800;">🔧 В работу</a>
                    <a href="controllers/update_order.php?id=%s&action=cancel" class="btn btn-sm" style="background: #c62828;">✗ Отменить</a>
                </div>
            </td>
            </tr>',
            htmlspecialchars($row['order_id']),
            htmlspecialchars($row['client_name']),
            htmlspecialchars($row['vehicle']),
            htmlspecialchars($row['name_service']),
            htmlspecialchars($row['mechanic_name']),
            htmlspecialchars($row['order_date']),
            number_format($row['total_price'], 0, '', ' '),
            htmlspecialchars($status_text),
            htmlspecialchars($row['order_id']),
            htmlspecialchars($row['order_id']),
            htmlspecialchars($row['order_id'])
            );
        }
    }
    
    $data .= "</tbody></table>";
    return $data;
}

// Функция для получения механиков в админке
function fnGetMechanicsAdmin(){
    global $connect;
    
    $sql = "SELECT * FROM mechanics ORDER BY mechanic_id";
    $result = $connect->query($sql);
    
    if($result->num_rows > 0) {
        $data = '<table class="table table-striped">
        <thead>
        <tr>
        <th>ID</th>
        <th>ФИО</th>
        <th>Специализация</th>
        <th>Телефон</th>
        <th>Ставка/час</th>
        <th>Статус</th>
        <th>Действия</th>
        </tr>
        </thead>
        <tbody>';
        
        while($row = $result->fetch_assoc()) {
            $status_text = $row['status'] == 'active' ? 'Работает' : 
                          ($row['status'] == 'vacation' ? 'Отпуск' : 'Уволен');
            $status_color = $row['status'] == 'active' ? 'green' : 
                           ($row['status'] == 'vacation' ? '#FF9800' : 'red');
            
            $data .= sprintf('
            <tr>
            <td>%s</td>
            <td>%s %s %s</td>
            <td>%s</td>
            <td>%s</td>
            <td>%s ₽</td>
            <td style="color: %s; font-weight: bold;">%s</td>
            <td>
                <a href="controllers/mechanics/edit_mechanic.php?id=%s" class="btn btn-sm" style="background: #2196F3;">✏️</a>
            </td>
            </tr>',
            $row['mechanic_id'],
            htmlspecialchars($row['surname']),
            htmlspecialchars($row['name']),
            htmlspecialchars($row['patronymic']),
            htmlspecialchars($row['specialization']),
            htmlspecialchars($row['phone']),
            number_format($row['hourly_rate'], 0, '', ' '),
            $status_color,
            htmlspecialchars($status_text),
            $row['mechanic_id']
            );
        }
        
        $data .= '</tbody></table>';
        
        $data .= '<div style="margin-top: 30px; text-align: center;">
            <a href="controllers/mechanics/add_mechanic.php" class="btn" style="padding: 12px 30px;">
                👨‍🔧 Добавить механика
            </a>
        </div>';
        
    } else {
        $data = '<div class="alert alert-warning">Нет зарегистрированных механиков</div>';
    }
    
    return $data;
}

// Функция для получения статистики
function fnGetStatistics(){
    global $connect;
    
    $clients_count = $connect->query("SELECT COUNT(*) as count FROM clients WHERE role = 'client'")->fetch_assoc()['count'];
    $mechanics_count = $connect->query("SELECT COUNT(*) as count FROM mechanics WHERE status = 'active'")->fetch_assoc()['count'];
    $orders_count = $connect->query("SELECT COUNT(*) as count FROM work_orders")->fetch_assoc()['count'];
    $active_orders = $connect->query("SELECT COUNT(*) as count FROM work_orders WHERE status IN ('new', 'in_progress', 'waiting_parts')")->fetch_assoc()['count'];
    $total_revenue = $connect->query("SELECT SUM(total_price) as total FROM work_orders WHERE status = 'completed'")->fetch_assoc()['total'];
    
    return [
        'clients' => $clients_count,
        'mechanics' => $mechanics_count,
        'orders' => $orders_count,
        'active_orders' => $active_orders,
        'revenue' => $total_revenue ? number_format($total_revenue, 0, '', ' ') . ' ₽' : '0 ₽'
    ];
}

// Функция для получения списка услуг (для формы заказа)
function fnGetServices() {
    global $connect;
    
    $sql = "SELECT service_id, name_service, base_price, description, estimated_time, category 
            FROM services 
            WHERE status = 'available'
            ORDER BY category, name_service";
    
    $result = $connect->query($sql);
    
    $services = [];
    if($result) {
        while($row = $result->fetch_assoc()) {
            $services[] = $row;
        }
    }
    
    return $services;
}

// Функция для получения списка механиков
function fnGetMechanics() {
    global $connect;
    
    $sql = "SELECT mechanic_id, CONCAT(surname, ' ', name) as full_name, specialization 
            FROM mechanics 
            WHERE status = 'active'
            ORDER BY surname";
    
    $result = $connect->query($sql);
    
    $mechanics = [];
    if($result) {
        while($row = $result->fetch_assoc()) {
            $mechanics[] = $row;
        }
    }
    
    return $mechanics;
}

// Функция для получения списка автомобилей клиента
function fnGetClientVehiclesList($client_id) {
    global $connect;
    
    $sql = sprintf("SELECT vehicle_id, CONCAT(brand, ' ', model, ' (', license_plate, ')') as vehicle_info 
            FROM vehicles 
            WHERE client_id = '%s'
            ORDER BY brand, model", $client_id);
    
    $result = $connect->query($sql);
    
    $vehicles = [];
    if($result) {
        while($row = $result->fetch_assoc()) {
            $vehicles[] = $row;
        }
    }
    
    return $vehicles;
}
?>
