<?php 
session_start();
if(!isset($_SESSION['login'])){
    header ("Location: ../../auth/");
    exit;
}

include "../../function/function.php";

// Получаем список курсов С ЦЕНАМИ из БД
$courses = fnGetCourses();

// Если курсов нет, покажем сообщение
if(empty($courses)) {
    die("<div style='text-align: center; padding: 50px;'>
            <h2>Нет доступных курсов</h2>
            <p>Обратитесь к администратору для добавления курсов</p>
            <a href='../../profile/'>Вернуться в личный кабинет</a>
         </div>");
}

// Варианты дат начала (можно сделать динамическими)
$available_dates = [
    date('Y-m-d', strtotime('+1 week')) => date('d.m.Y', strtotime('+1 week')) . ' (через неделю)',
    date('Y-m-d', strtotime('+2 weeks')) => date('d.m.Y', strtotime('+2 weeks')) . ' (через 2 недели)',
    date('Y-m-d', strtotime('+1 month')) => date('d.m.Y', strtotime('+1 month')) . ' (через месяц)'
];

// Варианты способов оплаты
$payment_methods = [
    'Онлайн оплата' => ' Онлайн оплата (картой)',
    'Банковский перевод' => ' Банковский перевод',
    'Рассрочка' => ' Рассрочка (без % на 6 мес.)',
    'Наличные' => ' Наличные в офисе'
];
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Подача заявки на курс - CodeAcademy</title>
    <style>
        /* Все стили из предыдущего ответа остаются */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Franklin Gothic Medium', 'Arial Narrow', Arial, sans-serif;
            background-color: #f5f5f5;
            color: #333;
            line-height: 1.6;
        }
        
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 50px;
            background: linear-gradient(135deg, #1a5ddb, #002873);
            margin-bottom: 40px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .header a {
            text-decoration: none;
            color: white;
            font-size: 24px;
            font-weight: bold;
        }
        
        .header ul {
            display: flex;
            list-style: none;
            gap: 25px;
            margin: 0;
            padding: 0;
        }
        
        .header ul a {
            font-size: 16px;
            padding: 8px 16px;
            border-radius: 4px;
            transition: background-color 0.3s;
        }
        
        .header ul a:hover {
            background-color: rgba(255,255,255,0.2);
        }
        
        .main-content {
            max-width: 1200px;
            margin: 0 auto 30px;
            background-color: white;
            border-radius: 12px;
            padding: 40px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
        }
        
        .page-title {
            font-size: 36px;
            text-align: center;
            margin: 0 0 40px 0;
            color: #333;
            position: relative;
            padding-bottom: 15px;
        }
        
        .page-title:after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 100px;
            height: 3px;
            background: linear-gradient(to right, #1a5ddb, #0e47b1);
        }
        
        /* Стили для слайдера остаются теми же */
        .slider-container {
            position: relative;
            max-width: 1000px;
            height: 500px;
            margin: 0 auto 40px;
            overflow: hidden;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.15);
            background: white;
        }
        
        .slider {
            display: flex;
            height: 100%;
            transition: transform 0.5s ease;
        }
        
        .slide {
            min-width: 100%;
            height: 100%;
            display: flex;
        }
        
        .image-container {
            flex: 0 0 55%;
            height: 100%;
            background: linear-gradient(45deg, #f5f5f5, #e0e0e0);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #888;
            font-size: 18px;
        }
        
        .info-container {
            flex: 0 0 45%;
            padding: 40px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
        
        .course-title {
            font-size: 28px;
            color: #333;
            margin: 0 0 20px 0;
            line-height: 1.3;
        }
        
        .course-price {
            font-size: 32px;
            color: #1a5ddb;
            font-weight: bold;
            margin: 0 0 10px 0;
        }
        
        .course-description {
            font-size: 16px;
            color: #666;
            line-height: 1.6;
            margin: 0 0 25px 0;
            flex-grow: 1;
        }
        
        .btn {
            display: inline-block;
            padding: 14px 30px;
            background: linear-gradient(135deg, #1a5ddb, #002873);
            color: white;
            text-decoration: none;
            border: none;
            border-radius: 25px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.3s;
            box-shadow: 0 4px 15px rgba(76, 175, 80, 0.25);
            text-align: center;
        }
        
        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(76, 175, 80, 0.35);
        }
        
        .btn:disabled {
            background: #cccccc;
            cursor: not-allowed;
            transform: none;
            box-shadow: none;
        }
        
        /* Остальные стили такие же как в предыдущем ответе */
        .slider-arrow {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            width: 50px;
            height: 50px;
            background-color: rgba(255, 255, 255, 0.95);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            font-size: 24px;
            color: #1a5ddb;
            box-shadow: 0 3px 10px rgba(0,0,0,0.2);
            z-index: 10;
            transition: all 0.3s;
            border: none;
        }
        
        .slider-arrow:hover {
            background-color: #1a5ddb;
            color: white;
            transform: translateY(-50%) scale(1.1);
        }
        
        .slider-arrow.prev {
            left: 20px;
        }
        
        .slider-arrow.next {
            right: 20px;
        }
        
        .slider-indicators {
            display: flex;
            justify-content: center;
            gap: 10px;
            margin-top: 20px;
        }
        
        .indicator {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            background-color: #ddd;
            cursor: pointer;
            transition: all 0.3s;
        }
        
        .indicator.active {
            background-color: #1a5ddb;
            transform: scale(1.3);
        }
        
        /* Стили для шага 2 */
        .course-options {
            display: none;
            max-width: 800px;
            margin: 40px auto;
            padding: 30px;
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
        }
        
        .course-options.show {
            display: block;
            animation: fadeIn 0.5s;
        }
        
        .options-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
            margin: 25px 0;
        }
        
        @media (max-width: 768px) {
            .options-grid {
                grid-template-columns: 1fr;
            }
        }
        
        .option-group {
            margin-bottom: 25px;
        }
        
        .option-group label {
            display: block;
            margin-bottom: 12px;
            font-weight: bold;
            font-size: 18px;
            color: #333;
        }
        
        .date-options, .payment-options {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }
        
        .date-option, .payment-option {
            padding: 15px;
            border: 2px solid #ddd;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s;
            background: white;
            text-align: center;
        }
        
        .date-option:hover, .payment-option:hover {
            border-color: #1a5ddb;
            background: #f0fff0;
        }
        
        .date-option.selected, .payment-option.selected {
            border-color: #1a5ddb;
            background: #e8f5e9;
            font-weight: bold;
        }
        
        .selected-course-summary {
            background: #e8f5e9;
            padding: 25px;
            border-radius: 10px;
            margin: 30px 0;
            border-left: 4px solid #1a5ddb;
        }
        
        .summary-item {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            padding-bottom: 10px;
            border-bottom: 1px dashed #ddd;
        }
        
        .summary-item:last-child {
            border-bottom: none;
        }
        
        .summary-label {
            font-weight: bold;
            color: #555;
        }
        
        .summary-value {
            color: #333;
            font-weight: bold;
        }
        
        .total-price {
            font-size: 24px;
            color: #1a5ddb;
            font-weight: bold;
            text-align: right;
            margin-top: 10px;
        }
        
        /* Форма */
        .application-form {
            display: none;
            max-width: 800px;
            margin: 40px auto;
            padding: 30px;
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
        }
        
        .application-form.show {
            display: block;
            animation: fadeIn 0.5s;
        }
        
        .form-group {
            margin-bottom: 25px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 10px;
            font-weight: bold;
            color: #333;
        }
        
        textarea {
            width: 100%;
            padding: 15px;
            font-size: 16px;
            border: 2px solid #ddd;
            border-radius: 8px;
            resize: vertical;
            font-family: inherit;
            min-height: 150px;
        }
        
        textarea:focus {
            outline: none;
            border-color: #1a5ddb;
        }
        
        .error-message {
            background-color: #ffebee;
            color: #c62828;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 30px;
            text-align: center;
            border-left: 4px solid #c62828;
        }
        
        .back-link {
            display: block;
            text-align: center;
            color: #1a5ddb;
            text-decoration: none;
            font-size: 16px;
            margin-top: 30px;
        }
        
        .back-link:hover {
            text-decoration: underline;
        }
        
        .step-buttons {
            display: flex;
            gap: 20px;
            margin-top: 30px;
        }
        
        .step-buttons .btn {
            flex: 1;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        @media (max-width: 768px) {
            .header {
                padding: 10px 20px;
                margin-bottom: 20px;
                flex-direction: column;
                gap: 10px;
            }
            
            .header ul {
                justify-content: center;
                flex-wrap: wrap;
                gap: 10px;
            }
            
            .main-content {
                padding: 20px;
                margin: 0 10px 20px;
            }
            
            .page-title {
                font-size: 28px;
                margin-bottom: 25px;
            }
            
            .slider-container {
                height: 400px;
            }
            
            .slide {
                flex-direction: column;
            }
            
            .image-container {
                flex: 0 0 40%;
            }
            
            .info-container {
                flex: 0 0 60%;
                padding: 25px;
            }
            
            .course-title {
                font-size: 22px;
            }
            
            .course-price {
                font-size: 26px;
            }
        }
    </style>
</head>
<body>
    <div class="header">
        <a href="../../index.php">CodeAcademy</a>
        <ul>
            <li><a href="../../profile/"> Личный кабинет</a></li>
            <li><a href="../../admin/controllers/logout.php"> Выйти</a></li>
        </ul>
    </div>

    <div class="main-content">
        <h1 class="page-title">Подача заявки на курс</h1>
        
        <?php if(isset($_GET['error'])): ?>
            <div class="error-message">
                <?php 
                $errors = [
                    'no_course' => '❌ Пожалуйста, выберите курс!',
                    'no_date' => '❌ Пожалуйста, выберите дату начала!',
                    'no_payment' => '❌ Пожалуйста, выберите способ оплаты!',
                    'invalid_course' => '❌ Выбран неверный курс!',
                    'already_applied' => '❌ Вы уже подали заявку на этот курс!',
                    'database_error' => '❌ Ошибка базы данных. Попробуйте позже.'
                ];
                echo $errors[$_GET['error']] ?? '❌ Произошла ошибка';
                ?>
            </div>
        <?php endif; ?>
        
        <!-- Шаг 1: Выбор курса -->
        <div id="step1">
            <div class="slider-container">
                <button class="slider-arrow prev" onclick="prevSlide()">‹</button>
                <button class="slider-arrow next" onclick="nextSlide()">›</button>
                
                <div class="slider" id="slider">
                    <?php foreach($courses as $index => $course): 
                        $course_id = $course['id_course'];
                        $course_name = htmlspecialchars($course['name_kurs']);
                        $description = $course['description'] ?? 'Описание курса будет добавлено позже';
                        $price = $course['price'] ?? 0;
                        $duration = $course['duration'] ?? 40;
                        
                        // Форматируем цену
                        $formatted_price = ($price > 0) ? number_format($price, 0, '', ' ') . ' ₽' : 'Бесплатно';
                        $monthly_payment = ($price > 0) ? ceil($price / 6) : 0;
                    ?>
                    <div class="slide" data-course-id="<?php echo $course_id; ?>" data-price="<?php echo $price; ?>">
                       <div class="image-container">
                               <img src="../../assets/imagess/course<?php echo $course_id; ?>_small.jpg" width="400px" height="400px" alt="">
                            </div>
                        
                        <div class="info-container">
                            <h2 class="course-title"><?php echo $course_name; ?></h2>
                            
                            <div class="course-price">
                                <?php echo $formatted_price; ?>
                                <?php if($price > 0): ?>
                                    <div style="font-size: 16px; color: #666; margin-top: 5px;">
                                        или <?php echo number_format($monthly_payment, 0, '', ' '); ?> ₽/мес на 6 месяцев
                                    </div>
                                <?php endif; ?>
                            </div>
                            
                            <p class="course-description"><?php echo $description; ?></p>
                            
                            <div style="margin-bottom: 25px;">
                                <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 8px;">
                                    <span style="color: #666;">⏱️</span>
                                    <span style="color: #666;"><?php echo $duration; ?> академических часов</span>
                                </div>
                                <div style="display: flex; align-items: center; gap: 10px;">
                                    <span style="color: #666;">🎯</span>
                                    <span style="color: #666;">Сертификат по окончании</span>
                                </div>
                            </div>
                            
                            <button class="btn" onclick="selectCourse(<?php echo $course_id; ?>, '<?php echo addslashes($course_name); ?>', <?php echo $price; ?>)">
                                Выбрать этот курс
                            </button>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                
                <div class="slider-indicators" id="indicators">
                    <?php for($i = 0; $i < count($courses); $i++): ?>
                        <div class="indicator <?php echo $i === 0 ? 'active' : ''; ?>" onclick="goToSlide(<?php echo $i; ?>)"></div>
                    <?php endfor; ?>
                </div>
            </div>
        </div>
        
        <!-- Шаг 2: Выбор даты и оплаты -->
        <div id="step2" class="course-options">
            <h2 style="margin-bottom: 25px; color: #333;">Настройте параметры курса</h2>
            
            <div class="options-grid">
                <div class="option-group">
                    <label>📅 Выберите дату начала обучения:</label>
                    <div class="date-options" id="dateOptions">
                        <?php foreach($available_dates as $date_value => $date_label): ?>
                            <div class="date-option" data-date="<?php echo $date_value; ?>" onclick="selectDate('<?php echo $date_value; ?>', '<?php echo $date_label; ?>')">
                                <?php echo $date_label; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <p style="color: #666; font-size: 14px; margin-top: 8px;">Занятия проходят 2 раза в неделю по 3 часа</p>
                </div>
                
                <div class="option-group">
                    <label>💰 Выберите способ оплаты:</label>
                    <div class="payment-options" id="paymentOptions">
                        <?php foreach($payment_methods as $method_value => $method_label): ?>
                            <div class="payment-option" data-method="<?php echo $method_value; ?>" onclick="selectPaymentMethod('<?php echo $method_value; ?>', '<?php echo addslashes($method_label); ?>')">
                                <?php echo $method_label; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <p style="color: #666; font-size: 14px; margin-top: 8px;">Можно изменить после подтверждения заявки</p>
                </div>
            </div>
            
            <!-- Сводка выбора -->
            <div class="selected-course-summary" id="courseSummary">
                <h3 style="margin-bottom: 20px; color: #333;">Ваш выбор:</h3>
                
                <div class="summary-item">
                    <span class="summary-label">Курс:</span>
                    <span class="summary-value" id="summaryCourseName">Не выбран</span>
                </div>
                
                <div class="summary-item">
                    <span class="summary-label">Дата начала:</span>
                    <span class="summary-value" id="summaryDate">Не выбрана</span>
                </div>
                
                <div class="summary-item">
                    <span class="summary-label">Способ оплаты:</span>
                    <span class="summary-value" id="summaryPayment">Не выбран</span>
                </div>
                
                <div class="summary-item">
                    <span class="summary-label">Стоимость:</span>
                    <span class="summary-value" id="summaryPrice">0 ₽</span>
                </div>
                
                <div class="total-price" id="totalPrice"></div>
            </div>
            
            <div class="step-buttons">
                <button class="btn" onclick="goToStep(1)" style="background: #666;">
                    ← Назад к выбору курса
                </button>
                <button class="btn" onclick="goToStep(3)" id="continueBtn" disabled>
                    Продолжить заполнение заявки →
                </button>
            </div>
        </div>
        
        <!-- Шаг 3: Форма заявки -->
        <form action="add_application.php" method="post" id="applicationForm" class="application-form">
            <input type="hidden" name="id_course" id="selectedCourse">
            <input type="hidden" name="selected_date" id="selectedDate">
            <input type="hidden" name="payment_method" id="selectedPayment">
            
            <h2 style="margin-bottom: 25px; color: #333;">📝 Заполните информацию для заявки</h2>
            
            <div class="form-group">
                <label for="message">Расскажите о себе и своих целях:</label>
                <textarea name="message" id="message" placeholder="Напишите здесь о вашей мотивации, опыте (если есть), образовании и целях обучения..."></textarea>
                <p style="color: #666; font-size: 14px; margin-top: 8px;">Эта информация поможет нам лучше понять ваши потребности</p>
            </div>
            
            <div class="selected-course-summary" style="margin: 30px 0;">
                <h3 style="margin-bottom: 20px; color: #333;">Итоговая информация:</h3>
                
                <div class="summary-item">
                    <span class="summary-label">Выбранный курс:</span>
                    <span class="summary-value" id="finalCourseName">Не выбран</span>
                </div>
                
                <div class="summary-item">
                    <span class="summary-label">Дата начала:</span>
                    <span class="summary-value" id="finalDate">Не выбрана</span>
                </div>
                
                <div class="summary-item">
                    <span class="summary-label">Способ оплата:</span>
                    <span class="summary-value" id="finalPayment">Не выбран</span>
                </div>
                
                <div class="summary-item">
                    <span class="summary-label">Стоимость обучения:</span>
                    <span class="summary-value" id="finalPrice">0 ₽</span>
                </div>
            </div>
            
            <div class="step-buttons">
                <button type="button" class="btn" onclick="goToStep(2)" style="background: #666;">
                    ← Назад
                </button>
                <button type="submit" class="btn" id="submitBtn">
                    📄 Отправить заявку
                </button>
            </div>
        </form>
        
        <a href="../../profile/" class="back-link">← Вернуться в личный кабинет</a>
    </div>
    
    <script>
        // Глобальные переменные
        let currentSlide = 0;
        const slides = document.querySelectorAll('.slide');
        const totalSlides = slides.length;
        const slider = document.getElementById('slider');
        const indicators = document.querySelectorAll('.indicator');
        
        let selectedCourseId = null;
        let selectedCourseName = '';
        let selectedCoursePrice = 0;
        let selectedDate = '';
        let selectedDateLabel = '';
        let selectedPayment = '';
        let selectedPaymentLabel = '';
        let currentStep = 1;
        
        // Функции слайдера
        function updateSlider() {
            if (slider && totalSlides > 0) {
                slider.style.transform = `translateX(-${currentSlide * 100}%)`;
            }
            
            indicators.forEach((indicator, index) => {
                indicator.classList.toggle('active', index === currentSlide);
            });
        }
        
        function nextSlide() {
            if (totalSlides > 0) {
                currentSlide = (currentSlide + 1) % totalSlides;
                updateSlider();
            }
        }
        
        function prevSlide() {
            if (totalSlides > 0) {
                currentSlide = (currentSlide - 1 + totalSlides) % totalSlides;
                updateSlider();
            }
        }
        
        function goToSlide(index) {
            if (index >= 0 && index < totalSlides) {
                currentSlide = index;
                updateSlider();
            }
        }
        
        // Навигация по шагам
        function goToStep(step) {
            currentStep = step;
            
            // Скрываем все шаги
            document.getElementById('step1').style.display = 'none';
            document.getElementById('step2').style.display = 'none';
            document.getElementById('applicationForm').style.display = 'none';
            
            // Показываем нужный шаг
            if (step === 1) {
                document.getElementById('step1').style.display = 'block';
            } else if (step === 2) {
                document.getElementById('step2').style.display = 'block';
                document.getElementById('step2').classList.add('show');
                updateSummary();
            } else if (step === 3) {
                document.getElementById('applicationForm').style.display = 'block';
                document.getElementById('applicationForm').classList.add('show');
                updateFinalSummary();
            }
            
            // Прокрутка вверх
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }
        
        // Выбор курса
        function selectCourse(courseId, courseName, coursePrice) {
            selectedCourseId = courseId;
            selectedCourseName = courseName;
            selectedCoursePrice = coursePrice;
            
            // Сбрасываем выбор даты и оплаты
            selectedDate = '';
            selectedDateLabel = '';
            selectedPayment = '';
            selectedPaymentLabel = '';
            
            // Сбрасываем выделение опций
            document.querySelectorAll('.date-option.selected').forEach(el => el.classList.remove('selected'));
            document.querySelectorAll('.payment-option.selected').forEach(el => el.classList.remove('selected'));
            
            // Переходим к шагу 2
            goToStep(2);
            
            // Деактивируем кнопку продолжения
            document.getElementById('continueBtn').disabled = true;
        }
        
        // Выбор даты
        function selectDate(dateValue, dateLabel) {
            selectedDate = dateValue;
            selectedDateLabel = dateLabel;
            
            // Снимаем выделение со всех дат
            document.querySelectorAll('.date-option').forEach(el => {
                el.classList.remove('selected');
            });
            
            // Выделяем выбранную дату
            const dateElement = document.querySelector(`.date-option[data-date="${dateValue}"]`);
            if (dateElement) {
                dateElement.classList.add('selected');
            }
            
            updateSummary();
            checkContinueButton();
        }
        
        // Выбор способа оплаты
        function selectPaymentMethod(methodValue, methodLabel) {
            selectedPayment = methodValue;
            selectedPaymentLabel = methodLabel;
            
            // Снимаем выделение со всех способов оплаты
            document.querySelectorAll('.payment-option').forEach(el => {
                el.classList.remove('selected');
            });
            
            // Выделяем выбранный способ
            const paymentElement = document.querySelector(`.payment-option[data-method="${methodValue}"]`);
            if (paymentElement) {
                paymentElement.classList.add('selected');
            }
            
            updateSummary();
            checkContinueButton();
        }
        
        // Обновление сводки на шаге 2
        function updateSummary() {
            // Курс
            document.getElementById('summaryCourseName').textContent = selectedCourseName || 'Не выбран';
            document.getElementById('summaryPrice').textContent = formatPrice(selectedCoursePrice);
            
            // Дата
            document.getElementById('summaryDate').textContent = selectedDateLabel || 'Не выбрана';
            
            // Способ оплаты
            document.getElementById('summaryPayment').textContent = selectedPaymentLabel || 'Не выбран';
            
            // Итоговая цена
            const totalPriceElement = document.getElementById('totalPrice');
            if (selectedCoursePrice > 0) {
                totalPriceElement.textContent = 'Итого: ' + formatPrice(selectedCoursePrice);
            } else {
                totalPriceElement.textContent = 'Итого: Бесплатно';
            }
        }
        
        // Обновление финальной сводки
        function updateFinalSummary() {
            document.getElementById('finalCourseName').textContent = selectedCourseName || 'Не выбран';
            document.getElementById('finalDate').textContent = selectedDateLabel || 'Не выбрана';
            document.getElementById('finalPayment').textContent = selectedPaymentLabel || 'Не выбран';
            document.getElementById('finalPrice').textContent = formatPrice(selectedCoursePrice);
            
            // Заполняем скрытые поля формы
            document.getElementById('selectedCourse').value = selectedCourseId || '';
            document.getElementById('selectedDate').value = selectedDate || '';
            document.getElementById('selectedPayment').value = selectedPayment || '';
        }
        
        // Проверка, можно ли продолжить
        function checkContinueButton() {
            const continueBtn = document.getElementById('continueBtn');
            if (selectedDate && selectedPayment) {
                continueBtn.disabled = false;
            } else {
                continueBtn.disabled = true;
            }
        }
        
        // Форматирование цены
        function formatPrice(price) {
            if (price === 0) return 'Бесплатно';
            return price.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ' ') + ' ₽';
        }
        
        // Валидация формы
        document.getElementById('applicationForm').addEventListener('submit', function(e) {
            const message = document.getElementById('message').value.trim();
            
            if (!selectedCourseId) {
                e.preventDefault();
                alert('Пожалуйста, выберите курс!');
                goToStep(1);
                return false;
            }
            
            if (!selectedDate) {
                e.preventDefault();
                alert('Пожалуйста, выберите дату начала обучения!');
                goToStep(2);
                return false;
            }
            
            if (!selectedPayment) {
                e.preventDefault();
                alert('Пожалуйста, выберите способ оплаты!');
                goToStep(2);
                return false;
            }
            
            if (message.length < 20) {
                e.preventDefault();
                alert('Пожалуйста, напишите более развернуто о себе и своих целях (минимум 20 символов)');
                document.getElementById('message').focus();
                return false;
            }
            
            return true;
        });
        
        // Инициализация
        updateSlider();
        
        // Автопрокрутка слайдера
        let slideInterval;
        if (totalSlides > 1) {
            slideInterval = setInterval(nextSlide, 5000);
            
            document.querySelector('.slider-container').addEventListener('mouseenter', () => {
                clearInterval(slideInterval);
            });
            
            document.querySelector('.slider-container').addEventListener('mouseleave', () => {
                slideInterval = setInterval(nextSlide, 5000);
            });
        }
        
        // Управление клавиатурой
        document.addEventListener('keydown', function(e) {
            if (e.key === 'ArrowLeft') prevSlide();
            if (e.key === 'ArrowRight') nextSlide();
        });
        
        // Экспорт функций для глобальной видимости
        window.prevSlide = prevSlide;
        window.nextSlide = nextSlide;
        window.goToSlide = goToSlide;
        window.selectCourse = selectCourse;
        window.selectDate = selectDate;
        window.selectPaymentMethod = selectPaymentMethod;
        window.goToStep = goToStep;
    </script>
</body>
</html>
