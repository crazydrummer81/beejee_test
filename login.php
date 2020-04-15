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

        if( !empty($errors) ){
            echo "<div id='login_error'>".array_shift($errors)."</div>";
        }
            
    } 
    if( !isset($_SESSION['logged_user'])) : ?>

        <h1>Войдите в свой личный кабинет</h1>
        <form name=contact_form onsubmit="return login_form_validate(this);" action="login.php" method="POST">
            <p><strong>Логин</strong>:</p>
            <input type="text" name="login">
            <p><strong>Пароль</strong>:</p>
            <input type="password" name="password">
            <p><button type="submit" name="do_login">Войти</button></p>
        </form>
        <div id="login">Ещё не зарегистрированы?</div>
        <a href="signup.php" id="signup">Создайте аккаунт за 30 секунд!</a>

        <script src="js/login_form_validate.js"></script>

    <?php endif; ?>

