<?php 
session_start();
if(!isset($_SESSION['login']) || $_SESSION['role'] != "client"){
    header ("Location: ../../auth/");
    exit;
}

include "../../function/function.php";


$services = fnGetServices();

$mechanics = fnGetMechanics();

$vehicles = fnGetClientVehiclesList($_SESSION['client_id']);


if(empty($vehicles)) {
    die("<div style='text-align: center; padding: 50px;'>
            <h2>У вас нет зарегистрированных автомобилей</h2>
            <p>Прежде чем создать заказ, добавьте автомобиль в свой профиль</p>
            <a href='../../profile/'>Вернуться в личный кабинет</a>
         </div>");
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Создание заказа - Автосервис</title>
    <link rel="stylesheet" href="../../assets/style/style.css">
    <style>
        .service-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
            margin-top: 30px;
        }
        
        .service-card {
            background: white;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            border-left: 4px solid #1a5ddb;
            cursor: pointer;
            transition: transform 0.3s, box-shadow 0.3s;
        }
        
        .service-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 20px rgba(0,0,0,0.15);
        }
        
        .service-card.selected {
            border-left: 4px solid #2196F3;
            background: #e3f2fd;
        }
        
        .service-price {
            font-size: 24px;
            color: #1a5ddb;
            font-weight: bold;
            margin: 10px 0;
        }
        
        .category-badge {
            display: inline-block;
            padding: 4px 8px;
            background: #e8f5e9;
            color: #0c3788;
            border-radius: 12px;
            font-size: 12px;
            font-weight: bold;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <?php include "../../inc/header.php"; ?>
    
    <div class="main-content">
        <h1 class="page-title">🔧 Создание нового заказа</h1>
        
        <?php if(isset($_GET['error'])): ?>
            <div class="alert alert-error">
                <?php 
                $errors = [
                    'no_service' => '❌ Пожалуйста, выберите услугу!',
                    'no_vehicle' => '❌ Пожалуйста, выберите автомобиль!',
                    'no_mechanic' => '❌ Пожалуйста, выберите механика!',
                    'database_error' => '❌ Ошибка базы данных. Попробуйте позже.'
                ];
                echo $errors[$_GET['error']] ?? '❌ Произошла ошибка';
                ?>
            </div>
        <?php endif; ?>
        
        <form action="process_create_order.php" method="post" id="orderForm">
            <!-- Шаг 1: Выбор автомобиля -->
            <div class="card" style="margin-bottom: 30px;">
                <h2 class="card-title">🚗 Шаг 1: Выберите автомобиль</h2>
                <div class="form-group">
                    <label for="vehicle_id">Ваш автомобиль:</label>
                    <select name="vehicle_id" id="vehicle_id" class="form-control" required>
                        <option value="">-- Выберите автомобиль --</option>
                        <?php foreach($vehicles as $vehicle): ?>
                            <option value="<?php echo $vehicle['vehicle_id']; ?>">
                                <?php echo htmlspecialchars($vehicle['vehicle_info']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            
            <!-- Шаг 2: Выбор услуги -->
            <div class="card" style="margin-bottom: 30px;">
                <h2 class="card-title">🔧 Шаг 2: Выберите услугу</h2>
                <div class="service-grid" id="servicesGrid">
                    <?php foreach($services as $service): 
                        $service_id = $service['service_id'];
                        $service_name = htmlspecialchars($service['name_service']);
                        $description = $service['description'] ?? 'Описание услуги';
                        $price = $service['base_price'];
                        $time = $service['estimated_time'];
                        $category = $service['category'];
                        
                        $category_names = [
                            'diagnostics' => 'Диагностика',
                            'engine' => 'Двигатель',
                            'transmission' => 'КПП',
                            'brakes' => 'Тормоза',
                            'suspension' => 'Ходовая',
                            'electrical' => 'Электрика',
                            'bodywork' => 'Кузовные работы',
                            'maintenance' => 'ТО'
                        ];
                    ?>
                    <div class="service-card" data-service-id="<?php echo $service_id; ?>" 
                         data-price="<?php echo $price; ?>" 
                         onclick="selectService(<?php echo $service_id; ?>, '<?php echo addslashes($service_name); ?>', <?php echo $price; ?>)">
                        <div class="category-badge">
                            <?php echo $category_names[$category] ?? $category; ?>
                        </div>
                        <h3 style="margin: 0 0 10px 0; color: #333;"><?php echo $service_name; ?></h3>
                        <div class="service-price">
                            <?php echo number_format($price, 0, '', ' '); ?> ₽
                        </div>
                        <p style="color: #666; font-size: 14px; margin-bottom: 10px;">
                            <?php echo $description; ?>
                        </p>
                        <div style="color: #888; font-size: 12px;">
                            ⏱️ Время работы: <?php echo $time; ?> минут
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                <input type="hidden" name="service_id" id="selectedService">
                <input type="hidden" name="service_price" id="selectedServicePrice">
            </div>
            
            <!-- Шаг 3: Выбор механика -->
            <div class="card" style="margin-bottom: 30px;">
                <h2 class="card-title">👨‍🔧 Шаг 3: Выберите механика</h2>
                <div class="form-group">
                    <label for="mechanic_id">Механик:</label>
                    <select name="mechanic_id" id="mechanic_id" class="form-control" required>
                        <option value="">-- Выберите механика --</option>
                        <?php foreach($mechanics as $mechanic): ?>
                            <option value="<?php echo $mechanic['mechanic_id']; ?>">
                                <?php echo htmlspecialchars($mechanic['full_name']); ?> 
                                (<?php echo htmlspecialchars($mechanic['specialization']); ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            
            <!-- Шаг 4: Дополнительная информация -->
            <div class="card" style="margin-bottom: 30px;">
                <h2 class="card-title">📝 Шаг 4: Дополнительная информация</h2>
                <div class="form-group">
                    <label for="notes">Комментарии к заказу:</label>
                    <textarea name="notes" id="notes" class="form-control" rows="4" 
                              placeholder="Опишите проблему, дополнительные пожелания..."></textarea>
                </div>
            </div>
            
            <!-- Сводка -->
            <div class="card" style="margin-bottom: 30px; background: #e8f5e9;">
                <h2 class="card-title">💰 Итоговая информация</h2>
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px;">
                    <div>
                        <strong>Выбранная услуга:</strong><br>
                        <span id="summaryService">Не выбрана</span>
                    </div>
                    <div>
                        <strong>Стоимость услуги:</strong><br>
                        <span id="summaryPrice">0 ₽</span>
                    </div>
                    <div>
                        <strong>Ваша скидка:</strong><br>
                        <span id="discountInfo">Загрузка...</span>
                    </div>
                    <div>
                        <strong>Итоговая стоимость:</strong><br>
                        <span id="totalPrice" style="font-size: 24px; color: #1a5ddb; font-weight: bold;">0 ₽</span>
                    </div>
                </div>
            </div>
            
            
            <div style="display: flex; gap: 20px; justify-content: center; margin-top: 30px;">
                <a href="../../profile/" class="btn" style="background: #666;">
                    ↩️ Назад в профиль
                </a>
                <button type="submit" class="btn" id="submitBtn" disabled>
                    📄 Создать заказ
                </button>
            </div>
        </form>
    </div>
    
    <script>
        let selectedServiceId = null;
        let selectedServiceName = '';
        let selectedServicePrice = 0;
        let clientDiscount = 0;
        
       
        fetch('../../function/get_discount.php')
            .then(response => response.json())
            .then(data => {
                clientDiscount = data.discount_percent || 0;
                document.getElementById('discountInfo').textContent = clientDiscount + '%';
                updateTotalPrice();
            });
        
        function selectService(serviceId, serviceName, servicePrice) {
            selectedServiceId = serviceId;
            selectedServiceName = serviceName;
            selectedServicePrice = servicePrice;
            
            document.querySelectorAll('.service-card').forEach(card => {
                card.classList.remove('selected');
            });
            
            const selectedCard = document.querySelector(`.service-card[data-service-id="${serviceId}"]`);
            if (selectedCard) {
                selectedCard.classList.add('selected');
            }
            
            document.getElementById('selectedService').value = serviceId;
            document.getElementById('selectedServicePrice').value = servicePrice;
            
            document.getElementById('summaryService').textContent = serviceName;
            document.getElementById('summaryPrice').textContent = formatPrice(servicePrice);
            
            updateTotalPrice();
            checkSubmitButton();
        }
        
        function updateTotalPrice() {
            let discountAmount = selectedServicePrice * clientDiscount / 100;
            let totalPrice = selectedServicePrice - discountAmount;
            
            document.getElementById('totalPrice').textContent = formatPrice(totalPrice);
        }
        
        function formatPrice(price) {
            return price.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ' ') + ' ₽';
        }
        
        function checkSubmitButton() {
            const submitBtn = document.getElementById('submitBtn');
            const vehicleSelect = document.getElementById('vehicle_id');
            const mechanicSelect = document.getElementById('mechanic_id');
            
            if (selectedServiceId && vehicleSelect.value && mechanicSelect.value) {
                submitBtn.disabled = false;
            } else {
                submitBtn.disabled = true;
            }
        }
        

        document.getElementById('vehicle_id').addEventListener('change', checkSubmitButton);
        document.getElementById('mechanic_id').addEventListener('change', checkSubmitButton);
        
 
        document.getElementById('orderForm').addEventListener('submit', function(e) {
            if (!selectedServiceId) {
                e.preventDefault();
                alert('Пожалуйста, выберите услугу!');
                return false;
            }
            
            if (!document.getElementById('vehicle_id').value) {
                e.preventDefault();
                alert('Пожалуйста, выберите автомобиль!');
                return false;
            }
            
            if (!document.getElementById('mechanic_id').value) {
                e.preventDefault();
                alert('Пожалуйста, выберите механика!');
                return false;
            }
            
            return true;
        });
    </script>
</body>
</html>