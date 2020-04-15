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


    
    <form name=contact_form onsubmit="return signup_form_validate(this);" action="signup.php" method="POST">
        <p><strong>Ваш логин</strong>:</p>
        <input type="text" name="login">
        <p><strong>e-mail</strong>:</p>
        <input type="email" name="email">
        <p><strong>Пароль</strong>:</p>
        <input type="password" name="password">
        <p><strong>Повторите пароль</strong>:</p>
        <input type="password" name="password_2">
        <p><button type="submit" name="do_signup">Зарегистрироваться</button></p>
    </form>
    <div id="login">Уже зарегистрированы?</div>
    <a href="login.php" id="login">Войдите в свой аккаунт</a>

    <script src="js/signup_form_validate.js"></script>
