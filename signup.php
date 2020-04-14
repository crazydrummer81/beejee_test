<?php
    require "db.php";
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
        else if( R::count('users', "email = ?", array($data['email'])) )  { 
                echo "Пользователь с таким e-mail уже существует";
                
            } 
        else
            R::store($user);
            echo "<div class='signup_success'>Спасибо за регистрацию!</div>";
            echo '<div class="signup_redirect"><a href="/login.php">Перейти на страницу входа</a></div>';
            exit;
        }

?>


    
    <form name=contact_form onsubmit="return validate_form();" action="signup.php" method="POST">
                <p><strong>Ваш логин</strong>:</p>
                <input type="text" name="login">
                <p><strong>e-mail</strong>:</p>
                <input type="email" name="email">
                <p><strong>Пароль</strong>:</p>
                <input type="password" name="password">
                <p><strong>Повторите пароль</strong>:</p>
                <input type="password" name="password_2">
                <p>
                    <button type="submit" name="do_signup">Зарегистрироваться</button>
                </p>

    </form>
    <div id="login">Уже зарегистрированы?</div>
    <a href="login.php" id="login">Войдите в свой аккаунт</a>

    <script>
        function validate_form() {
            valid = true;
            if(
                ( document.contact_form.name.value == "" ) ||
                ( document.contact_form.email.value == "" ) ||
                ( document.contact_form.password.value == "" ) ||
                ( document.contact_form.password_2.value == "" )) {
                    alert("Пожалуйста, заполните все поля формы..." );
                    valid = false;
            }
            else if  ( document.contact_form.password.value != document.contact_form.password_2.value ) {
                alert("Подтверждение пароля не совпадает, пожалуйста, повторите ввод")
                valid = false;
            }
            return valid;
        }
    </script>

