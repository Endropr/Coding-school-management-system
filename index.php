

<? 
session_start();

if(isset($_SESSION['login'])){
    if($_SESSION['role'] == 'admin'){
        header("Location: admin/");
    } else {
        header("Location: profile/");
    }
    exit;
}

include "inc/header_start.php";
?>

<style>
    .main-content {
        padding: 20px 15px;
        background-color: #f4f7fe;
    }

    .main-content img {
        max-width: 100%;
        height: auto;
        border-radius: 15px;
        margin-bottom: 30px;
    }

    .container-custom {
        max-width: 850px; /* Оптимальная ширина для двух колонок */
        margin: 0 auto;
    }

    .card {
        background: #ffffff;
        border-radius: 15px;
        padding: 30px;
        margin-bottom: 25px;
        box-shadow: 0 8px 24px rgba(149, 157, 165, 0.1);
        border: 1px solid #eef2f7;
    }

    .card-title {
        color: #1e293b;
        font-weight: 700;
        margin-bottom: 25px;
    }

    /* СЕТКА УСЛУГ: СТРОГО 2 КОЛОНКИ */
    .services-grid {
        display: grid;
        grid-template-columns: 1fr 1fr; /* Ровно две колонки */
        gap: 20px;
    }

    .service-item {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 25px;
        display: flex;
        flex-direction: column;
        align-items: center; /* Центрируем контент внутри */
        justify-content: space-between;
        transition: all 0.3s ease;
    }

    .service-item:hover {
        border-color: #1a5ddb;
        box-shadow: 0 5px 15px rgba(26, 93, 219, 0.1);
    }

    /* Сетка преимуществ */
    .advantages-card {
        background: #f0f7ff !important;
        border: 1px solid #d0e4ff;
    }

    .advantages-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr); /* 4 в ряд для преимуществ */
        gap: 15px;
    }

    /* Кнопки */
    .btn-group {
        display: flex;
        gap: 15px;
        justify-content: center;
        flex-wrap: wrap;
        margin-top: 25px;
    }

    .btn {
        display: inline-block;
        padding: 12px 30px;
        border-radius: 10px;
        text-decoration: none;
        font-weight: 600;
        transition: all 0.3s;
        min-width: 180px;
        text-align: center;
    }

    .btn-outline {
        border: 2px solid #1a5ddb;
        color: #1a5ddb;
    }

    .btn-primary {
        background: #1a5ddb;
        color: #fff;
        border: 2px solid #1a5ddb;
    }

    /* Адаптация под мобильные */
    @media (max-width: 700px) {
        .services-grid {
            grid-template-columns: 1fr; /* На маленьких экранах в одну колонку */
        }
        .advantages-grid {
            grid-template-columns: 1fr 1fr; /* Преимущества 2х2 на мобильных */
        }
        .btn {
            width: 100%;
        }
    }
</style>

<div class="main-content" style="text-align: center;">
    <img src="assets/imagess/course1_small.jpg" alt="Сервис">
    
    <div class="container-custom">
        <!-- Блок услуг -->
        <div class="card">
            <h2 class="card-title">🔧 Качественный ремонт автомобилей</h2>
            <p style="font-size: 17px; color: #64748b; margin-bottom: 30px;">
                Профессиональное обслуживание и ремонт любой сложности.
            </p>
            
            <div class="services-grid">
                <div class="service-item">
                    <div>
                        <h3 style="color: #1a5ddb; font-size: 19px; margin-bottom: 10px;">🛠️ Техническое обслуживание</h3>
                        <p style="font-size: 15px; color: #475569;">Регулярное ТО, замена масла, фильтров</p>
                    </div>
                    <div style="margin-top: 15px;">
                        <span style="color: #94a3b8; font-size: 14px;">⏱️ 1-2 часа</span>
                        <div style="color: #1e293b; font-weight: 800; font-size: 18px; margin-top: 5px;">от 2 500 ₽</div>
                    </div>
                </div>
                
                <div class="service-item">
                    <div>
                        <h3 style="color: #1a5ddb; font-size: 19px; margin-bottom: 10px;">🔍 Диагностика</h3>
                        <p style="font-size: 15px; color: #475569;">Компьютерная диагностика систем</p>
                    </div>
                    <div style="margin-top: 15px;">
                        <span style="color: #94a3b8; font-size: 14px;">⏱️ 30-60 минут</span>
                        <div style="color: #1e293b; font-weight: 800; font-size: 18px; margin-top: 5px;">от 1 500 ₽</div>
                    </div>
                </div>
                
                <div class="service-item">
                    <div>
                        <h3 style="color: #1a5ddb; font-size: 19px; margin-bottom: 10px;">🛡️ Ремонт тормозов</h3>
                        <p style="font-size: 15px; color: #475569;">Замена колодок, дисков и жидкости</p>
                    </div>
                    <div style="margin-top: 15px;">
                        <span style="color: #94a3b8; font-size: 14px;">⏱️ 2-3 часа</span>
                        <div style="color: #1e293b; font-weight: 800; font-size: 18px; margin-top: 5px;">от 4 000 ₽</div>
                    </div>
                </div>
                
                <div class="service-item">
                    <div>
                        <h3 style="color: #1a5ddb; font-size: 19px; margin-bottom: 10px;">⚡ Электрика</h3>
                        <p style="font-size: 15px; color: #475569;">Ремонт оборудования и АКБ</p>
                    </div>
                    <div style="margin-top: 15px;">
                        <span style="color: #94a3b8; font-size: 14px;">⏱️ 1-4 часа</span>
                        <div style="color: #1e293b; font-weight: 800; font-size: 18px; margin-top: 5px;">от 3 000 ₽</div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Блок преимуществ -->
        <div class="card advantages-card">
            <h2 class="card-title">⭐ Наши преимущества</h2>
            <div class="advantages-grid">
                <div>
                    <div style="font-size: 32px; margin-bottom: 10px;">👨‍🔧</div>
                    <h4 style="margin: 5px 0; color: #1e293b; font-size: 15px;">Механики</h4>
                    <p style="font-size: 12px; color: #64748b;">Стаж > 5 лет</p>
                </div>
                <div>
                    <div style="font-size: 32px; margin-bottom: 10px;">🔧</div>
                    <h4 style="margin: 5px 0; color: #1e293b; font-size: 15px;">Запчасти</h4>
                    <p style="font-size: 12px; color: #64748b;">Оригиналы</p>
                </div>
                <div>
                    <div style="font-size: 32px; margin-bottom: 10px;">🛡️</div>
                    <h4 style="margin: 5px 0; color: #1e293b; font-size: 15px;">Гарантия</h4>
                    <p style="font-size: 12px; color: #64748b;">12 месяцев</p>
                </div>
                <div>
                    <div style="font-size: 32px; margin-bottom: 10px;">⏱️</div>
                    <h4 style="margin: 5px 0; color: #1e293b; font-size: 15px;">Скорость</h4>
                    <p style="font-size: 12px; color: #64748b;">До 24 часов</p>
                </div>
            </div>
        </div>
        <div class="card" style="margin-top: 20px;">
            <h2 class="card-title">🚀 Запишитесь на сервис прямо сейчас!</h2>
            <p style="margin-bottom: 30px; font-size: 18px;">
                Создайте учетную запись, добавьте свой автомобиль и запишитесь на обслуживание
            </p>
            
            <div style="display: flex; gap: 20px; justify-content: center; margin-top: 20px;">
                <a href="auth/" class="btn" style="min-width: 180px;">
                    🔐 Войти
                </a>
                <a href="register/" class="btn" style="background: #1a5ddb; min-width: 180px;">
                    📝 Зарегистрироваться
                </a>
            </div>
        </div>
    </div>
</div>

</body>
</html>
