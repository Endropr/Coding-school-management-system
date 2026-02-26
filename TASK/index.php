<?php 
session_start();
include "../inc/header.php";
?>
<div class="h_2">
    <p class="p_1">Подача заявки на курс</p>
<form action="../admin/controllers/" method="post">
    <div>
        <label for="id_course">Выберите курс</label><br>
        <select name="id_course" id="id_course">
            <option value="1">Основы алгоритмизации программирования</option>
            <option value="2">Основы веб-дизайна</option>
            <option value="3">Основы проектирования баз данных</option>
        </select>
    </div>
    <div>
        <label for="message">Дополнительная информация</label><br>
        <textarea name="message" rows="5"></textarea>
    </div>
    <input type="submit" value="Подать заявку">
</form>
</div>