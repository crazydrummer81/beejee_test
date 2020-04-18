<?php
    require_once "db.php";
    if( isset($_SESSION['logged_user'])) {
        $author = $_SESSION['logged_user']->login;
    }
    else { $author = "Anonymous"; }
    isset($_POST) ? $params = "?" : $params = "";

    if( isset($_POST['page']           )) { $params .= "&page=".$_POST['page']; };
    if( isset($_POST['sort_by']        )) { $params .= "&sort_by=".$_POST['sort_by']; };
    if( isset($_POST['sort_direction'] )) { $params .= "&sort_direction=".$_POST['sort_direction']; };

    if( isset($_POST['send_task']) ) {
        // echo $_POST['send_task'];
        $_POST['content'] = trim(htmlspecialchars($_POST['content']));
        if( $_POST['content'] != "" ) {
            $task = R::dispense('tasks');
            $task->content = $_POST['content'];
            $task->author = $author;
            $task->checked = false;
            $task->email = get_author_email($author);
            R::store($task);
        }
    }
    header("Location: /$params"); // ДОБАВИТЬ ССЫЛКИ НА СТРАНИЦУ И МЕТОД СОРТИРОВКИ
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
