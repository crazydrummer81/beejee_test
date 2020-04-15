<?php
    require_once "db.php";
    $params = "";

    if( isset($_GET['page'])) { $params .= "?page=".$_GET['page']; };

    if( !isset($_GET['task_id'])) {
        header("Location: /$params");
    }

    $task_id = $_GET['task_id'];
    $task = R::findOne( 'tasks', 'id = ? ', array($task_id) );
    $task->checked = !$task->checked;
    R::store( $task );
    header("Location: /$params");

?>
    