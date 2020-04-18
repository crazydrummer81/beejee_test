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
            <link rel="stylesheet" href="css/media.css">
            <link rel="stylesheet" href="css/fonts.css">
        </head>
        <body>
        <div id="main-wrapper-home">
            <header class="authorisation">
<?php    
    if( isset($_SESSION['logged_user'])) : ?>
    <!-- Шапка для авторизованного -->
            <div>Привет, <strong><?php echo $_SESSION['logged_user']->login; ?></strong>! </div>
            <a style="text-align:end;" href="logout.php" id="logout">Выйти</a>
        
    <?php else : ?>
        <!-- Шапка для неавторизованного -->
            <a href="/login.php">Войти</a>
            <a style="text-align:end;" href="/signup.php">Зарегистрироваться</a>
            
            <?php endif; ?>
        
        </header>
        <h1 class="home_heading">Задачи</h1>

    <!-- <h1 style='top:-70px; margin-bottom: -30px;'>Список задач</h1> -->
    <?php include "section_task_list.php"; ?>
    <h3>Cоздать задачу</h3>
    <?php include "section_task_create.php"; ?>
    </div>
</body>
</html>