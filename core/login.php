<?php

    include('include.php');

    if (!array_key_exists('type', $_POST)) {
        return_user('../index.php', '1');
    }

    if ($_POST['type'] == 'reg') {
        $password = hash_password($_POST['password']);

        global $mysqli;
        $stmt = $mysqli->prepare("INSERT INTO `accounts` (`login`, `password`) VALUES (?, ?)");
        $stmt->bind_param('ss', $_POST['login'], $password);
        $stmt->execute();
        $stmt->close();

    } else if ($_POST['type'] == 'login') {
        $profile = mysqli_login($_POST['login'], $_POST['password']);

        if (mysqli_num_rows($profile) == 0) {
            return_user('../index.php', '1');
        } else {
            $_SESSION['token'] = get_token($_POST['login'], $_POST['password']);
        }

    }

    return_user('../index.php');

?>