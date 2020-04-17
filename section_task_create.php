<?php
    require_once "db.php";
    if( isset($_SESSION['logged_user'])) {
        $author = $_SESSION['logged_user']->login;
    }
    else { $author = "Anonymous"; }
?>
    <form action="create_task.php" method="POST">
        <div class="section-task-create">
            <input type="textarea" name="content" id="field_new_task" onsubmit="">
            <input type="hidden" name="page" value="<?php echo $current_page; ?>">
            <input type="hidden" name="sort_by" value="<?php echo $current_sort_by; ?>">
            <input type="hidden" name="sort_direction" value="<?php echo $sort_direction; ?>">
            <button name="send_task" type="submit">Создать</button>
        </div>
    </form>