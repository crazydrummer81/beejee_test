<?php
    require_once "db.php";
    $data = $_POST;
    if( isset($data['do_signup']) ) {
        // Регистрируем пользовалеля
        $user = R::dispense('users');
        $user->login = $data['login'];
        $user->email = $data['email'];
        $user->password = password_hash($data['password'], PASSWORD_DEFAULT);
        if( R::count('users', "login = ?", array($data['login'])) )  { 
            echo "Пользователь с таким логином уже существует";
        } 
        elseif( R::count('users', "email = ?", array($data['email'])) )  { 
                echo "Пользователь с таким e-mail уже существует";
            } 
        else {
            R::store($user);
            echo "<div class='signup_success'>Спасибо за регистрацию!</div>";
            echo "<div class='signup_redirect'><a href='/login.php'>Перейти на страницу входа</a></div>";
            exit;
        }
    }

?>

    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Document</title>
        <link rel="stylesheet" href="css/style.css">
    </head>
    <body style="max-width:350px;">
    <h1>Создайте аккаунт</h1>
        <div id="login-form">
            <form name=contact_form onsubmit="return signup_form_validate(this);" action="signup.php" method="POST">
                <div class="login-form-field"><strong>Ваш логин</strong>:</div>
                <input type="text" name="login">
                <div class="login-form-field"><strong>e-mail</strong>:</div>
                <input type="email" name="email">
                <div class="login-form-field"><strong>Пароль</strong>:</div>
                <input type="password" name="password">
                <div class="login-form-field"><strong>Повторите пароль</strong>:</div>
                <input type="password" name="password_2">
                <div><button type="submit" name="do_signup">Зарегистрироваться</button></div>
            </form>
        </div>
    <div id="signup_offer">
        <div id="login">Уже зарегистрированы?</div>
        <a href="login.php" id="login">Войдите в свой аккаунт</a>
    </div>

    <script src="js/signup_form_validate.js"></script>

    </body>
    </html>