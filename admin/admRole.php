<?php 
    session_start();
    $_SESSION['user_id'] = 0;
    $_SESSION['user_role'] = 'admin';
    header('Location: index.php');
?>