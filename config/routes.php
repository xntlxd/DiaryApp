<?php

    // "folder/name" => "level", где level: доступ (0 - Неавториз., 1 - авториз.)

    $routes = [
        "" => '0',
        "service" => "0",
        "logout" => "0",

        // Table
        "table/student" => "1",
        "table/group" => "1",
    ];

    $errors = [
        "0" => "Все хорошо",
        "1" => "Произошла непредвиденная ошибка: 1", 
        "2" => "Несанкционированный вход на страницу", 
        "4" => "Неверный логин или пароль", 
    ]

?>