

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Вход</title>
</head>
<body>

    <main>
        <div id="main-form">
            <h1 id="form-label">Кванториум</h1>
            <form action="service" method="post">
                <input type="text" name="user-login" id="form-login" class="form-input" placeholder="Логин">
                <input type="password" name="user-password" id="form-password" class="form-input" placeholder="Пароль">
                <input type="text" name="type" value="login" hidden>
                <input type="submit" value="Вход" id="form-login-input" class="form-button">
                <input type="submit" value="Зарегистрироваться" id="form-reg" class="form-button">
            </form>
            <?php if (array_key_exists("status", $_GET)) {
                    if (array_key_exists($_GET['status'], $errors)) {
                        echo "<p id='error-message'>".$errors[$_GET['status']]."</p>";
                    } 
                 } ?>
            <!-- <p id="form-error">Вы не заполнили поле ввода</p> -->
        </div>
    </main> 
    
</body>
</html>
