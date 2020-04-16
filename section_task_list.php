<?php
    require_once "db.php";
 
    $tasks = R::findAll( 'tasks' );
    foreach( $tasks as &$task ) {
        $task->email = get_author_email($task->author);
    }
    
    $sort_types = array(
        'login_inc'   => ['direction' => 'inc', 'key' => 'login',   'description' => 'автор'],
        'email_inc'   => ['direction' => 'inc', 'key' => 'email',   'description' => 'e-mail'],
        'content_inc' => ['direction' => 'inc', 'key' => 'content', 'description' => 'задача'],
        'default'     => ['direction' => 'inc', 'key' => 'default', 'description' => 'без сортировки']
    );
    if( isset($_GET['sort_by'])) {
        $sort_by        = $_GET['sort_by'];
        $sort_direction = $_GET['sort_direction'];
        if( isset($_GET['change_sort_by'])        ) {
            $change_sort_by = $_GET['change_sort_by'];
            if( $sort_by == $change_sort_by ) {
                $sort_direction = ($sort_direction == 'dec') ? 'inc' : 'dec';
            }
        } else { $change_sort_by = $sort_by; }
        if( isset($_GET['change_sort_direction']) ) { $change_sort_direction = $_GET['change_sort_direction'];
            } else { $change_sort_direction = $sort_direction; }
        
        
        $compare_func = "compare_".$change_sort_by."_".$sort_direction;
        usort($tasks, $compare_func);
        $sort_by = $change_sort_by;
    } else {
        $sort_by = "default";
        $sort_direction = "inc";
    }
    isset( $_SESSION['logged_user'] ) ? $logged_user = $_SESSION['logged_user']->login : $logged_user = "";
?>

<div id="sort"> 
    <div id="sort-header">Сортировать по</div>
    <div id="sort-content">

        <?php 
            // echo "<pre>"; print_r($sort_types); echo "</pre>";
            foreach( $sort_types as $type ) {
                if( $sort_by == $type['key'] ) {
                    $tag = 'strong';
                    $dir = ( $sort_direction == 'inc' ) ? "&darr;" : "&uarr;"; 
                } 
                else { $tag = 'regular'; $dir=""; }
                
                printf( '<a href="/?sort_by=%s&sort_direction=%s&change_sort_by=%s&change_sort_direction=%s"><%s>%s %s</%s></a>', 
                    $sort_by, $sort_direction, $type['key'], $type['direction'], $tag, $type['description'], $dir, $tag );
            }
        ?>

    </div>
</div>

<?php


    //------------------TASK LIST----------------------
    echo "<div class='task-list-wrapper'>";
        if( $logged_user == "admin") {
            printf('<form name="form_tasks_edit" onsubmit="" action="save_tasks.php" method="POST">'); 
        }

        $tasks_per_pages = 3;
        $tasks_count = count($tasks); 
        $task_id = 0;
        $page_id = 0;
        $tasks_pages = array();
        // $task = $tasks[0];
        
        // Разбиваем все задачи на массивы по tasks_per_pages штук
        
        $counter = 1; $tasks_page_id = 1;
        unset($task);
        foreach( $tasks as $task ) {
            $tasks_pages[$tasks_page_id][] = $task; 
            if( $counter++ >= $tasks_per_pages ) { $counter = 1; $tasks_page_id++; }
        }
        
        if( isset($_GET['page'])) { $current_page = $_GET['page'];}
            else { $current_page = 1; }
        
        if( count($tasks) ) { 
            foreach( $tasks_pages[$current_page] as $task ) {
                echo "<div class='task-item'>";
                echo "<div class='checkbox'>";
                if( $logged_user != "") { //Checkbox
                    if( $logged_user == "admin") {
                        printf("<a class='' href='task_check.php/?task_id=%s&sort_by=%s&sort_direction=%s&page=%s'>", $task->id, $sort_by, $sort_direction, $current_page);
                    } //else { }

                    if( $task->checked ) {
                        echo "<i class='icon-checkbox-checked'></i>";
                    } else {
                        echo "<i class='icon-checkbox-unchecked'></i>";
                    }
                    if( $logged_user == "admin") { echo '</a>'; }
                } 
                echo "</div>";
                
                    printf( "<div id='task_content_%s' class='task'>%s</div>", $task->id, $task->content);
                    printf( '<input type="text" style="display:none;" disabled="true" id="task_content_edited_%s" name="task_edited_%s" value="%s"></input>', $task->id, $task->id, $task->content);
                    if( $logged_user == "admin") {
                        printf( "<button type='button' class='button-task-edit' id='button_task_edit_%s' onclick='editTask(%s)'>Изменить</button>",$task->id, $task->id );
                        printf( "<button style='display:none;' type='button' class='button-task-edit' id='button_task_edit_cancel_%s' onclick='cancelEditTask(%s)'>Отменить</button>",$task->id, $task->id );
                    } else {
                        echo "<div class='button-task-edit'></div>";
                    }

                    echo('<div class="task-author">');
                    printf( "<div class='author-name'>Автор: <strong>%s</strong></div>", $task->author);
                    
                    if( $task->author != 'Anonymous' )
                    printf( " <div class='author-email'> | e-mail: <strong>%s</strong></div>",  $task->email );
                    echo '</div>';
                echo '</div>';
            
            }
        }
        if( $logged_user == "admin") {
            printf('<button id="button_save_form" style="display:none" type="submit">Сохранить</button>'); 
            printf('</form>'); 
        }
    echo "</div>"; // task-lis-wrapper
    // ------------------ССЫЛКИ ПАГИНАЦИИ-----------------
    $page_id = 1;
    if( count($tasks_pages) > 1 ) {
        echo '<div id="pagination">';
        if( $current_page > 1) { 
            printf('<a href="/?page=%d&sort_by=%s&sort_direction=%s">&larr;</a>', $current_page-1, $sort_by, $sort_direction); }

        foreach ($tasks_pages as $page) {
            if( $current_page == $page_id ) { $inner_tag = "strong"; } else { $inner_tag = "span"; }
            $format = "\n<a href='/?page=%d&sort_by=%s&sort_direction=%s'> <%s> %d </%s> </a>";
            printf($format, $page_id, $sort_by, $sort_direction, $inner_tag, $page_id, $inner_tag);
            $page_id++;
        }
        if( $current_page < count($tasks_pages)) { 
            printf('<a href="/?page=%d&sort_by=%s&sort_direction=%s">&rarr;</a>', $current_page+1, $sort_by, $sort_direction); 
        }
        echo '</div>';
    }

function get_author_email($author_name) {
    $user = R::findOne( 'users', ' login = ? ', array($author_name) );
    if( $user->email != NULL ) { return $user->email; }
        else { return "-"; }
}
function compare_content_inc($a, $b) {
    return $a->content > $b->content;
}
function compare_content_dec($a, $b) {
    return $a->content < $b->content;
}
function compare_email_inc($a, $b) {
    return $a->email > $b->email;
}
function compare_email_dec($a, $b) {
    return $a->email < $b->email;
}
function compare_login_inc($a, $b) {
    return $a->author > $b->author;
}
function compare_login_dec($a, $b) {
    return $a->author < $b->author;
}
function compare_default_inc($a, $b) {
    return $a->id > $b->id;
}
function compare_default_dec($a, $b) {
    return $a->id < $b->id;
}

?>

<script>
    function editTask(task_id) {

        elem_id = 'task_content_' + task_id; //console.log('id: '+elem_id);
        elem = document.getElementById(elem_id);
        // console.log(elem);
        // target_html = '<input type="text" id="task_content_edited_' + task_id + '" name="task_edited_' + task_id + '" value="'+ elem.innerText +'"></input>';
        // elem.innerHTML = target_html;
        elem.style = "display:none;";
        console.log(elem);
        
        elem_id = 'task_content_edited_' + task_id; //console.log('id: '+elem_id);
        elem = document.getElementById(elem_id);
        elem.style = "display:inline;";
        elem.disabled = false;
        console.log(elem);
        
        elem_id = 'button_task_edit_' + task_id; console.log('id: '+elem_id);
        elem = document.getElementById(elem_id);
        // elem.innerText = "Отмена";
        elem.style="display:none;";

        elem_id = 'button_task_edit_cancel_' + task_id; console.log('id: '+elem_id);
        elem = document.getElementById(elem_id);
        // elem.innerText = "Отмена";
        elem.style="display:block;";

        elem_id = 'button_save_form';
        elem = document.getElementById(elem_id);
        elem.style = 'display:block;';
    }

    function cancelEditTask(task_id) {
        console.log("ОТМЕНА "+task_id);

        elem_id = 'task_content_edited_' + task_id; //console.log('id: '+elem_id);
        elem = document.getElementById(elem_id);
        elem.style = "display:none;";
        elem.disabled = true;

        elem_id = 'task_content_' + task_id; //console.log('id: '+elem_id);
        elem = document.getElementById(elem_id);
        elem.style = "dispay:inline;";
        elem.disabled = true;
        
        elem_id = 'button_task_edit_cancel_' + task_id; console.log('id: '+elem_id);
        elem = document.getElementById(elem_id);
        elem.style="display:none;";

        elem_id = 'button_task_edit_' + task_id; console.log('id: '+elem_id);
        elem = document.getElementById(elem_id);
        elem.style="display:inline;";
        
        return false;
    }

</script>