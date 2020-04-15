<?php
    require_once "db.php";
    if( isset($_SESSION['logged_user'])) {
        $author = $_SESSION['logged_user']->login;
    }
    else { $author = "Anonymous"; }

        $data = $_POST;
        if( isset($data['send_task']) ) {
            // Регистрируем пользовалеля
            $task = R::dispense('tasks');
            $task->content = $data['content'];
            $task->author = $author;
            $task->checked = false;
            R::store($task);
        }
?>
<div>Задача создана!</div>
<div>Создать еще одну задачу</div>
<?php include "section_task_create.php"; ?>
<a href="/">На главную</a>
