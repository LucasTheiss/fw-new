<?php 
    session_start();
    $_SESSION['id'] = 0;
    $_SESSION['role'] = 3;
    $_SESSION['adm'] = '1';
    header('Location: ../index.php');
?>