<?php
    require_once "db.php";
    $data = $_POST;
    if( isset($data['do_signup']) ) {
        // Регистрируем пользовалеля
        $user = R::dispense('users');
        $user->login = trim(htmlspecialchars($data['login']));
        $user->email = trim(htmlspecialchars($data['email']));
        $user->password = password_hash($data['password'], PASSWORD_DEFAULT);
        if( R::count('users', "login = ?", array($data['login'])) )  { 
            $errors[] = "Пользователь с таким логином уже существует";
        } 
        elseif( R::count('users', "email = ?", array($data['email'])) )  { 
            $errors[] = "Пользователь с таким e-mail уже существует";
            } 
        else {
            R::store($user);
            header("Location: /login.php");
            // echo "<div class='signup_success' style='text-align: center; margin-top:20px;'>Спасибо за регистрацию!</div>";
            // echo "<div class='signup_redirect' style='text-align: center; margin-top:20px;'><a href='/login.php'>Перейти на страницу входа</a></div>";
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
        <link rel="stylesheet" href="css/media.css">
        <link rel="stylesheet" href="css/fonts.css">
    </head>
    <body>
    <div id="main-wrapper-signup">
        <header>
            <h1 class="signup_heading">Создайте аккаунт</h1>
        </header>
        <?php if( !empty($errors) ) {
                echo "<div id='login_error'>".array_shift($errors)."</div>"; }
        ?>
            <div id="login-form">
                <form name=contact_form onsubmit="return signup_form_validate(this);" action="signup.php" method="POST">
                    <div class="login-form-field" placeholder="Ваш логин"><strong>Ваш логин</strong>:
                    <input type="text" name="login" required></div>
                    <div class="login-form-field"><strong>e-mail</strong>:
                    <input type="email" name="email" required></div>
                    <div class="login-form-field" ><strong>Пароль</strong>:
                    <input type="password" name="password" required></div>
                    <div class="login-form-field"><strong>Повторите пароль</strong>:
                    <input type="password" name="password_2" required></div>
                    <div class="login-form-button"><button class="button" type="submit" name="do_signup">Зарегистрироваться</button></div>
                </form>
            </div>
        <div id="signup_offer">
            <div id="login">Уже зарегистрированы?</div>
            <a href="login.php" id="login">Войдите в свой аккаунт</a>
        </div>
    </div>
    <script src="js/signup_form_validate.js"></script>

    </body>
    </html>