<?php
    require_once "db.php";
?>

<!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Список задач</title>
            <link rel="stylesheet" href="css/style.css">
            <link rel="stylesheet" href="css/fonts.css">
        </head>
        <body>
        <div id="main-wrapper-home">
<?php    
    if( isset($_SESSION['logged_user'])) : ?>
    <!-- Шапка для авторизованного -->
        <div id="authorisation">
            <div>Привет, <strong><?php echo $_SESSION['logged_user']->login; ?></strong>! </div>
            <h1>Задачи</h1>
            <a style="text-align:end;" href="logout.php" id="logout">Выйти</a>
        </div>
        
    <?php else : ?>
        <!-- Шапка для неавторизованного -->
        <div id="authorisation">
            <a href="/login.php">Войти</a>
            <h1>Задачи</h1>
            <a style="text-align:end;" href="/signup.php">Зарегистрироваться</a>
        </div>

    <?php endif; ?>

    <!-- <h1 style='top:-70px; margin-bottom: -30px;'>Список задач</h1> -->
    <?php include "section_task_list.php"; ?>
    <h3>Cоздать задачу</h3>
    <?php include "section_task_create.php"; ?>
    </div>
</body>
</html>