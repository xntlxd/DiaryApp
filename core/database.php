<?php

    // Создание и настройка объекта для подключения к базе данных.

    $mysqli = mysqli_init();

    if (!$mysqli) { 
        echo('mysqli_init failed');
    }

    if (!$mysqli = new mysqli('localhost', 'root', '', 'kvantoriumdb')) { // Так как проект будет работать на локальном сервере, созданныи при помощи 'XAMPP', задаем такие параметры.
        echo('Connect Error (' . $mysqli->connect_errno . ') ' . $mysqli->connect_error);
    }

    if (!$mysqli->query("SET NAMES 'utf8'")) {
        printf("Ошибка при загрузке набора символов utf8: %s\n", $mysqli->error);
        exit();
    }

?>