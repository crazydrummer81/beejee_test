<?php
    require_once "db.php";
?>
<div class="sort"> Сортировать по
    <a href="/?sort_by=login">автор</a>
    <a href="/?sort_by=email">e-mail</a>
    <a href="/?sort_by=content">задача</a>
</div>

<?php
    $tasks = R::findAll( 'tasks' );
    foreach( $tasks as &$task ) {
        $task->email = get_author_email($task->author);
    }
    $sort_by = "default";
    if( isset($_GET['sort_by'])) {
        $compare_func = "compare_".$sort_by;
        usort($tasks, $compare_func);
    }
    isset( $_SESSION['logged_user'] ) ? $logged_user = $_SESSION['logged_user']->login : $logged_user = "";

    //------------------FORM----------------------
    if( $logged_user == "admin") {
        printf('<form name="form_tasks_edit" onsubmit="" action="save_tasks.php" method="POST">'); 
    }

    $tasks_count = count($tasks); echo "<br>TASKS COUNT: ".$tasks_count;
    $task_id = 0;
    $page_id = 0;
    $tasks_pages = array();
    $tasks_per_pages = 3;
    $task = $tasks[0];
    
    // Разбиваем все задачи на массивы по tasks_per_pages штук
    $counter = 0; $tasks_page_id = 1;
    foreach( $tasks as $task ) {
        $tasks_pages[$tasks_page_id][] = $task;
        if( $counter++ >= $tasks_per_pages-1 ) { $counter = 0; $tasks_page_id++; }
    }
    
    if( isset($_GET['page'])) { $current_page = $_GET['page'];}
        else { $current_page = 1; }
    
    foreach( $tasks_pages[$current_page] as $task ) {
        echo "<div class='task-item'>";
        if( $logged_user != "") { //Checkbox
            if( $logged_user == "admin") {
                printf("<a class='checkbox' href='task_check.php/?task_id=%s&sort_by=%s'>", $task->id, $sort_by); }
            if( $task->checked ) {
                echo "<i class='icon-checkbox-checked'></i>";
            } else {
                echo "<i class='icon-checkbox-unchecked'></i>";
            }
            if( $logged_user == "admin") { echo '</a>'; }
        }
            printf( "<div id='task_content_%s' class='task'>%s</div>", $task->id, $task->content);
            printf( '<input type="text" style="display:none;" disabled="true" id="task_content_edited_%s" name="task_edited_%s" value="%s"></input>', $task->id, $task->id, $task->content);
            if( $logged_user == "admin") {
                printf( "<button type='button' id='button_task_edit_%s' onclick='editTask(%s)'>Изменить</button>",$task->id, $task->id );
                printf( "<button style='display:none;' type='button' id='button_task_edit_cancel_%s' onclick='cancelEditTask(%s)'>Отменить</button>",$task->id, $task->id );
            }
            $task->email = get_author_email($task->author);

            echo('<div class="task-author">');
            printf( "<div class='author-name'>Автор: <strong>%s</strong></div>", $task->author);
            
            if( $task->author != 'Anonymous' )
            printf( " <div class='author-email'>e-mail: <strong>%s</strong></div>",  $task->email );
            echo '</div>';
        echo '</div>';
        
    }
    if( $logged_user == "admin") {
        printf('<button id="button_save_form" style="display:none" type="submit">Сохранить</button>'); 
        printf('</form>'); 
    }
    // ССЫЛКИ ПАГИНАЦИИ
    $page_id = 1;
    if( count($tasks_pages) > 1 ) {
        echo '<div id="pagination">';
        if( $current_page > 1) { printf('<a href="/?page=%d&sort_by=%s">&larr;</a>', $current_page-1, $sort_by); }
        foreach ($tasks_pages as $page) {
            if( $current_page == $page_id ) { $inner_tag = "strong"; } else { $inner_tag = "span"; }
            $format = "\n<a href='/?page=%d&sort_by=%s'> <%s> %d </%s> </a>";
            printf($format, $page_id, $sort_by, $inner_tag, $page_id, $inner_tag);
            $page_id++;
        }
        if( $current_page < count($tasks_pages)) { printf('<a href="/?page=%d&sort_by=%s">&rarr;</a>', $current_page+1, $sort_by); }
        echo '</div>';
    }

function get_author_email($author_name) {
    $user = R::findOne( 'users', ' login = ? ', array($author_name) );
    return $user->email;
}
function compare_content($a, $b) {
    return $a->content > $b->content;
}
function compare_email($a, $b) {
    return $a->email > $b->email;
}
function compare_login($a, $b) {
    return $a->author > $b->author;
}
function compare_default($a, $b) {
    return $a->id > $b->id;
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
        elem.style="display:inline;";

        elem_id = 'button_save_form';
        elem = document.getElementById(elem_id);
        elem.style = 'display:inline;';
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