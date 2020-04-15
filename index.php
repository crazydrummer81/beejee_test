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



        <div>Привет, <?php echo $_SESSION['logged_user']->login; ?> </div>
        <div><a href="logout.php" id="logout">Выйти</a></div>
        
        <h1>Задачник</h1>
        

        <h2>Список задач</h2>
        <?php include "section_task_list.php"; ?>
        <h3>Cоздать задачу</h3>
        <?php include "section_task_create.php"; ?>

    <?php else : ?>
        <!-- вывод для неавторизованного -->
        <div>Не аторизован</div>
        <a href="/login.php">Авторизоваться</a><br>
        <a href="/signup.php">Зарегистрироваться</a>

        <h2>Список задач</h2>
        <?php include "section_task_list.php"; ?>

        <h3>Cоздать задачу без регистрации</h3>
        <?php include "section_task_create.php"; ?>

    <?php endif; ?>

</body>
</html>