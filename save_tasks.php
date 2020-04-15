<?php
    require_once "db.php";
    if( !isset($_SESSION['logged_user']) && !( $_SESSION['logged_user']->login == 'admin' ) ) {
        header("Location: /");
        die;
    } else;

        $data = $_POST;
        $keys = array_keys($data);
        
        if( isset($data) ) {
            extract_ids($keys, 'task_edited_');
            $keys_elem_id = 0;
            foreach( $data as $content ) {
                $key = $keys[$keys_elem_id++];
                $task = R::findOne('tasks', ' id = ? ', array($key));
                $task->content = $content;
                R::store($task);
            }
            header( "refresh:3;url=/" );
            echo('Все изменения сохранены. Вы будете перенаправлены на главную страницу...');
        }

    function extract_ids(&$keys, $str_to_delete) {
        $i = 0;
        foreach( $keys as $key ) {
            $keys[$i] = str_replace( $str_to_delete, '', $keys[$i] );
            $i++;
        }
    }
?>

