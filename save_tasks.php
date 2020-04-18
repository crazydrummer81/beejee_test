<?php
    require_once "db.php";
    if( !isset($_SESSION['logged_user']) && !($_SESSION['logged_user']->login == 'admin') && !isset($_POST) ) {
        header("Location: /");
        die;
    } else;

        
    $params = "?";

    if( isset($_POST['page']           )) { $params .= "&page=".$_POST['page']; };
    if( isset($_POST['sort_by']        )) { $params .= "&sort_by=".$_POST['sort_by']; };
    if( isset($_POST['sort_direction'] )) { $params .= "&sort_direction=".$_POST['sort_direction']; };

    // echo "<pre>\n".var_dump($_POST)."\n</pre>";

    foreach( $_POST as $key => $content ) {
        // echo "\nKEY: $key CONTENT: $content"; 
        // echo "\nSTRPOS: ".strpos($key, "task_edited_");
        if( strpos($key, "task_edited_") !== FALSE ) {
            $id = str_replace( "task_edited_", "", $key );
            $task = R::findOne('tasks', ' id = ? ', array($id));
            if( trim($content) == "" ) { R::trash( $task ); }
            else {
                $task->content = trim(htmlspecialchars($content));
                // echo "Saving to id:$id => $content";
                R::store($task);
            }
        }   
    }

        header( "Location: /$params" );

?>

