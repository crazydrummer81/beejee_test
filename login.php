<?php
    require_once "db.php";

    $data = $_POST;
    
    if( isset($data['do_login']) ) {
        // Регистрируем пользовалеля
        $errors = array();
        $user = R::dispense('users');
        
        $user = R::findOne('users', "login = ?", array($data['login']));
        if( $user ) {
            if( password_verify($data['password'], $user->password) ) {
                //логиним пользователя
                $_SESSION['logged_user'] = $user;
                header("Location: /");
                echo( "<div>Вход выполнен.</div><div><a href='/'>Вы будете перенапрвлены на главную страницу...</a></div>" );
            } else {
                $errors[] = "Логин и пароль не совпадают";
            }
        } else {
            $errors[] = "Пользователь с таким логином не найден!";
        }


            
    } 
    if( !isset($_SESSION['logged_user'])) : ?>
    
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
        <div id="main-wrapper-login">
            <header>
                <h1 class="login_heading">Вход в личный кабинет</h1>
            </header>
            <?php if( !empty($errors) ) {
                echo "<div id='login_error'>".array_shift($errors)."</div>";
            } ?>
            <div id="login-form">
                <form name=contact_form onsubmit="return login_form_validate(this);" action="login.php" method="POST">
                    <div class="login-form-field"><strong>Логин</strong>:
                    <input type="text" name="login"></div>
                    <div class="login-form-field"><strong>Пароль</strong>:
                    <input type="password" name="password"></div>
                    <div class="login-form-button"><button class="button" type="submit" name="do_login">Войти</button></div>
                </form>
            </div>
            <div id="signup_offer">
                <div id="login">Ещё не зарегистрированы?</div>
                <a href="signup.php" id="signup">Создайте аккаунт за 30 секунд!</a>
            </div>

            <script src="js/login_form_validate.js"></script>
        </div>       
        </body>
        </html>
    <?php endif; ?>

