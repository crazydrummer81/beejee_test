<?php
    require "db.php";

    if( isset($_SESSION['logged_user'])) :
    // вывод для авторизованного
        echo "Привет, ".$_SESSION['logged_user']->login;

    else : 
    //вывод для неавторизованного
        echo "Не аторизован";

    endif;

?>

    <a href="logout.php" id="logout">Выйти</a>

    <h1>Задачник</h1>
    <a href="/login.php">Авторизоваться</a><br>
    <a href="/signup.php">Зарегистрироваться</a>
