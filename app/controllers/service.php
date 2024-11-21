<?php
    include("../config/path.php");

    print_r($_POST);

    if (array_key_exists("user-login", $_POST)) {

        if (!array_key_exists('type', $_POST)) {
            require_once(ERROR."/404.php");
            die();
        }

        if ($_POST['type'] == 'reg') {
            $password = hash_password($_POST['password']);

            global $mysqli;
            $stmt = $mysqli->prepare("INSERT INTO `accounts` (`login`, `password`) VALUES (?, ?)");
            $stmt->bind_param('ss', $_POST['login'], $password);
            $stmt->execute();
            $stmt->close();

        } else if ($_POST['type'] == 'login') {
            $profile = mysqli_login($_POST['user-login'], $_POST['user-password']);

            if (!$profile) {
                return_user('/diaryapp/', '4');
            } else {
                $_SESSION['token'] = get_token($_POST['user-login'], $_POST['user-password']);
            }

        }

        return_user('table/student');
    }

?>