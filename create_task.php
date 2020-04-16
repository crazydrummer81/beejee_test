<?php
    require_once "db.php";
    if( isset($_SESSION['logged_user'])) {
        $author = $_SESSION['logged_user']->login;
    }
    else { $author = "Anonymous"; }

    $data = $_POST;
    // echo($data['send_task']);
    // echo($data['content']);
    if( isset($data['send_task']) ) {
        // echo $data['send_task'];
        $data['content'] = htmlspecialchars($data['content']);
        $task = R::dispense('tasks');
        $task->content = $data['content'];
        $task->author = $author;
        $task->checked = false;
        $task->email = get_author_email($author);
        R::store($task);
    }
    header("Location: /");
    function get_author_email($author_name) {
        $user = R::findOne( 'users', ' login = ? ', array($author_name) );
        if( $user->email != NULL ) { return $user->email; }
            else { return "-"; }
    }
?>
<div>Задача создана!</div>
<div>Создать еще одну задачу</div>
<?php include "section_task_create.php"; ?>
<a href="/">На главную</a>
