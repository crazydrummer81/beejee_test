<?php
    require_once "db.php";
    if( !isset($_SESSION['logged_user']) && !( $_SESSION['logged_user']->login == 'admin' ) ) {
        header("Location: /");
        die;
    } else;

        isset($_GET) ? $params = "?" : $params = "";

        if( isset($_GET['page']           )) { $params .= "&page=".$_GET['page']; };
        if( isset($_GET['sort_by']        )) { $params .= "&sort_by=".$_GET['sort_by']; };
        if( isset($_GET['sort_direction'] )) { $params .= "&sort_direction=".$_GET['sort_direction']; };
        
        if( isset($_GET['task_id']) ) {
            $task = R::findOne('tasks', ' id = ? ', array($_GET['task_id']));
            R::trash( $task );
        }
        header( "Location: /$params" );
?>

