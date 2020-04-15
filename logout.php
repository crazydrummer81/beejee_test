<?php
    require_once "db.php";
    unset($_SESSION['logged_user']);
    header("Location: /");
?>