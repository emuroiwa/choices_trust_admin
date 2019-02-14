<?php 
session_start();

unset($_SESSION['username']);
unset($_SESSION['name']);
unset($_SESSION['branch']);


session_unset();
session_destroy();

header("location:index.php");
?>