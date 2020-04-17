<?php
    require_once "db.php";
    
    isset( $_SESSION['logged_user'] ) ? $logged_user = $_SESSION['logged_user']->login : $logged_user = "";

    $tasks = R::findAll( 'tasks' );
    foreach( $tasks as &$task ) {
        $task->email = get_author_email($task->author);
    }
    unset($task);
    
    //----------------ОПРЕДЕЛЕНИЕ ТИПА И НАПРАВЛЕНИЯ СОРТИРОВКИ------------------
    if( isset($_GET['sort_by']) && ($_GET['sort_by'] != "") ) {
        $current_sort_by        = $_GET['sort_by'];
        if( isset($_GET['sort_direction']) && $_GET['sort_direction'] != "" ) { $sort_direction = $_GET['sort_direction']; 
            } else { $sort_direction = "inc"; }
        if( isset($_GET['change_sort_direction']) && $_GET['change_sort_direction'] != ""  ) { $change_sort_direction = $_GET['change_sort_direction'];
            } else { $change_sort_direction = $sort_direction; }
        if( isset($_GET['change_sort_by']) && $_GET['change_sort_by'] != "" ) {
            $change_sort_by = $_GET['change_sort_by'];
            if( $current_sort_by == $change_sort_by ) {
                $sort_direction = ($sort_direction == 'dec') ? 'inc' : 'dec';
            }
        } else { $change_sort_by = $current_sort_by; }
        $compare_func = "compare_".$change_sort_by."_".$sort_direction; // ФОРМИРУЕМ СТРОКУ ДЛЯ ФУНКЦИИ USORT()
        
        //----------------СОРТИРОВКА МАССИВА ЗАДАЧ------------------
        usort($tasks, $compare_func);
        $current_sort_by = $change_sort_by;
        
        } else { // ЕСЛИ НЕ ЗАДАНА СОРТИРОВКА УСТАНАВЛИВАЕМ ТИП И НАПРАВЛЕНИЕ ПО УМОЛЧАНИЮ
            $current_sort_by = "default";
            $sort_direction = "inc";
            $change_sort_by = "default";
    }
    $current_sort_by = $change_sort_by; // ПОСЛЕ СОРТИРОВКИ УСТАНАВИЛИВАЕМ ПАРАМЕТРЫ ДЛЯ СЛЕДУЮЩЕГО ЗАПРОСА
?>

<div id="sort"> 
    <div id="sort-header">Сортировать по</div>
    <div id="sort-content">
        
        <?php //--------------------ВЫВОД ССЫЛОК СОРТИРОВКИ-------------------- 
            $sort_types = array(
                'login'   => ['key' => 'login',   'description' => 'автор'],
                'email'   => ['key' => 'email',   'description' => 'e-mail'],
                'content' => ['key' => 'content', 'description' => 'задача'],
                'default' => ['key' => 'default', 'description' => 'без сортировки']
            );
            foreach( $sort_types as $type ) {
                if( $current_sort_by == $type['key'] ) {
                    $tag = 'strong';
                    $dir = ( $sort_direction == 'inc' ) ? "&uarr;" : "&darr;"; 
                } 
                else { $tag = 'regular'; $dir=""; }
                
                printf( '<a href="/?sort_by=%s&sort_direction=%s&change_sort_by=%s"><%s>%s %s</%s></a>', 
                    $current_sort_by, $sort_direction, $type['key'], $tag, $type['description'], $dir, $tag );
            }
        ?>

    </div>
    <div id='button-save'>
        <?php if( $logged_user == "admin"): ?>
            <a href="#" 
                id="button_save_form" 
                style="display:none" 
                onclick='document.getElementById("form_tasks_edit").submit()'>
                    <i class="icon-save-disk"></i>
            </a>
        <?php endif; ?>
    </div>
</div>

<!--------------------ВЫВОД СПИСКА ЗАДАЧ---------------------->
<div class='task-list-wrapper'>
<?php
        if( $logged_user == "admin") {
            printf('<form name="form_tasks_edit" id="form_tasks_edit" onsubmit="" action="save_tasks.php" method="POST">'); 
        }

        $tasks_per_pages = 3;
        $tasks_count = count($tasks); 
        $task_id = 0;
        $page_id = 0;
        $tasks_pages = array();
        // $task = $tasks[0];
        
        // Разбиваем все задачи на массивы по tasks_per_pages штук
        
        $counter = 1; $tasks_page_id = 1;
        foreach( $tasks as $task ) {
            $tasks_pages[$tasks_page_id][] = $task; 
            if( $counter++ >= $tasks_per_pages ) { $counter = 1; $tasks_page_id++; }
        }
        
        if( isset($_GET['page']) && ($_GET['page'] != 0 ) ) {
            $current_page = $_GET['page']; echo "SUKA ".$current_page > count($tasks_pages);
            if ( $current_page > count($tasks_pages) ) {
                $current_page = count($tasks_pages);
            }
        }
            else { $current_page = 1; } 
        
        if( count($tasks) ) { 
            foreach( $tasks_pages[$current_page] as $task ) {
                echo "<div class='task-item'>";
                echo "<div class='checkbox'>";
                if( $logged_user != "") { //Checkbox
                    if( $logged_user == "admin") {
                        printf("<a class='' href='task_check.php/?task_id=%s&sort_by=%s&sort_direction=%s&page=%s'>", $task->id, $current_sort_by, $sort_direction, $current_page);
                    } //else { }

                    if( $task->checked ) {
                        echo "<i class='icon-checkbox-checked'></i>";
                    } else {
                        echo "<i class='icon-checkbox-unchecked'></i>";
                    }
                    if( $logged_user == "admin") { echo '</a>'; }
                } 
                echo "</div>";
                
                    echo "<div class='task'>";
                    printf( "<div id='task_content_%s' >%s</div>", $task->id, $task->content);
                    printf( '<input type="text" style="display:none;" disabled="true" id="task_content_edited_%s" name="task_edited_%s" value="%s"></input>', $task->id, $task->id, $task->content);
                    printf( "<a style='display:none;' id='button_task_edit_cancel_%s' onclick='cancelEditTask(%s)'><i class='icon-cancel-circle'></i></a>",$task->id, $task->id );
                    echo "</div>";
                    echo "<div class='button-task-edit'>";
                    if( $logged_user == "admin") {
                        printf( "<a href='#' id='button_task_edit_%s' onclick='editTask(%s)'><i style='font-size: 1.3em;' class='icon-compose'></i></a>",$task->id, $task->id );
                        // printf( "<a style='display:none;' id='button_task_edit_cancel_%s' onclick='cancelEditTask(%s)'><i class='icon-cancel-circle'></i></a>",$task->id, $task->id );
                        printf( "<a href='/delete_task.php/?task_id=%s&page=%d&sort_by=%s&sort_direction=%s' class='button-task-delete' id='button_task_delete_%s'><i class='icon-bin'></i></a>", $task->id, $current_page, $current_sort_by, $sort_direction,  $task->id  );

                    } //else {
                        //echo "<div class='button-task-edit'></div>";
                    //}
                    echo "</div>";

                    echo('<div class="task-author">');
                    printf( "<div class='author-name'>Автор: <strong>%s</strong></div>", $task->author);
                    
                    if( $task->author != 'Anonymous' )
                    printf( " <div class='author-email'> | e-mail: <strong>%s</strong></div>",  $task->email );
                    echo '</div>';
                echo '</div>';
            
            }
        }
        // if( $logged_user == "admin") {
        //     printf('<button id="button_save_form" style="display:none" type="submit">Сохранить</button>'); 
            printf('</form>'); 
        //}
?>
</div> <!-- task-list-wrapper -->
<?php
    // ------------------ССЫЛКИ ПАГИНАЦИИ-----------------
    $page_id = 1;
    if( count($tasks_pages) > 1 ) {
        echo '<div id="pagination">';
        if( $current_page > 1) { 
            printf('<a href="/?page=%d&sort_by=%s&sort_direction=%s">&larr;</a>', $current_page-1, $current_sort_by, $sort_direction); }

        foreach ($tasks_pages as $page) {
            if( $current_page == $page_id ) { $inner_tag = "strong"; } else { $inner_tag = "span"; }
            $format = "\n<a href='/?page=%d&sort_by=%s&sort_direction=%s'> <%s> %d </%s> </a>";
            printf($format, $page_id, $current_sort_by, $sort_direction, $inner_tag, $page_id, $inner_tag);
            $page_id++;
        }
        if( $current_page < count($tasks_pages)) { 
            printf('<a href="/?page=%d&sort_by=%s&sort_direction=%s">&rarr;</a>', $current_page+1, $current_sort_by, $sort_direction); 
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
        elem.style = "display:none;";
        console.log(elem);
        
        elem_id = 'task_content_edited_' + task_id; //console.log('id: '+elem_id);
        elem = document.getElementById(elem_id);
        elem.style = "display:inline;";
        elem.disabled = false;
        console.log(elem);
        
        elem_id = 'button_task_edit_' + task_id; console.log('id: '+elem_id);
        elem = document.getElementById(elem_id);
        elem.style="display:none;";

        elem_id = 'button_task_edit_cancel_' + task_id; console.log('id: '+elem_id);
        elem = document.getElementById(elem_id);
        elem.style="display:inline;";

        elem_id = 'button_save_form';
        elem = document.getElementById(elem_id);
        elem.style = 'display:inline;';
    }

    function cancelEditTask(task_id) {
        console.log("ОТМЕНА "+task_id);

        elem_id = 'task_content_edited_' + task_id;
        elem = document.getElementById(elem_id);
        elem.style = "display:none;";
        elem.disabled = true;

        elem_id = 'task_content_' + task_id;
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