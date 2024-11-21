<?php

    $functions = False; // Создаем объект функции, который при ошибке будет возвращать False.

    function return_user($_location, $_status = 0) {  // Перенаправление пользователя с возможностью указать статус.
        if ($_status == 0) {
            header("Location: ".$_location);
        } else {
            header("Location: ".$_location."?status=".$_status);
        }
        
        exit();
    }

    function dd($variable) {
        die(var_dump($variable));
    }

    function hash_password($_password, $_salt = 'salt') { // Хеширование пароля
        $_password = md5(base64_encode($_password.'hash_salt').$_salt);
        return $_password;
    }

    function get_token($_login, $_password, $_hash = True, $_salt = 'salt') { // Создание токена пользователя
        if ($_hash == True) {
            $_password = hash_password($_password, $_salt);
        }

        $_token = base64_encode($_login.'%?'.$_password.'=keyDown');
        return $_token;
    }

    function close_token($_token) { // Получение Логина и Пароля (реверсификация) из токена.
        $_keys = explode('%?', base64_decode($_token));
        $_keys[1] = substr($_keys[1], 0, -8);
        return ['login' => $_keys[0], 'password' => $_keys[1]];
    }

    function mysqli_login($_login, $_password, $_crypt = True) { // Получение пользователя при логировании.
        if ($_crypt == True) {
            $_password = hash_password($_password);
        }

        global $mysqli;
        $stmt = $mysqli->prepare("SELECT `id` FROM `accounts` WHERE `login` = ? AND `password` = ?");
        $stmt->bind_param('ss', $_login, $_password);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows != 0) {
            return $result;
        } else {
            return False;
        }

        $stmt->close();
    }

    function mysqli_exist_token($_token) { // Проверка на существование пользователя с таким токеном.
        $_keys = close_token($_token);
        $profile = mysqli_login($_keys['login'], $_keys['password'], False);

        if (!$profile) {
            return False;
        } else {
            return mysqli_fetch_assoc($profile);
        }

    }

    function mysqli_search($_query) {

        $_query = htmlspecialchars(mysql_real_escape_string(trim($_query)));

        if (!empty($_query)) {

            $_result = $mysqli->query("SELECT `groups`.`name` as gname, `kvants`.`name` as kname, `groups`.`level`, `teachers`.`lastname`, `teachers`.`firstname`, `teachers`.`patname` FROM `groups` JOIN `kvants` ON `groups`.`to_kvant` = `kvants`.`id` JOIN `teachers` ON `groups`.`to_teachers` = `teachers`.`id` WHERE `gname` LIKE '$_query' OR `kname` LIKE '$_query' OR `g`.`level` LIKE '$_query' OR `teachers`.`lastname` LIKE '$_query' OR `teachers`.`firstname` LIKE '$_query' OR `teachers`.`patname` LIKE '$_query'");

        }
    }

    $functions = True; // Возвращение True при правильном подключении модуля.

?>