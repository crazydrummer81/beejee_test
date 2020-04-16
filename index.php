<?php
    require_once "db.php";
    if( isset($_GET['page'])) {
        $page = $_GET['page'];
    }
?>

<!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Document</title>
            <link rel="stylesheet" href="css/style.css">
            <link rel="stylesheet" href="css/fonts.css">
            <!-- <script src="http://code.jquery.com/jquery-1.11.1.js"></!--> 

        </head>
        <body>

<?php    
    if( isset($_SESSION['logged_user'])) :
    // вывод для авторизованного
?>


        <div id="authorisation">
            <div>Привет, <strong><?php echo $_SESSION['logged_user']->login; ?></strong>! </div>
            <div><a href="logout.php" id="logout">Выйти</a></div>
        </div>
        
        <h1>Список задач</h1>
        <?php include "section_task_list.php"; ?>
        <h3>Cоздать задачу</h3>
        <?php include "section_task_create.php"; ?>

    <?php else : ?>
        <!-- вывод для неавторизованного -->
        <!-- <div>Не аторизован</div> -->
        <div id="authorisation">
            <a href="/login.php">Войти</a>
            <a href="/signup.php">Зарегистрироваться</a>
        </div>
        <h2>Список задач</h2>
        <?php include "section_task_list.php"; ?>

        <h3>Cоздать задачу без регистрации</h3>
        <?php include "section_task_create.php"; ?>

    <?php endif; ?>

</body>
</html>