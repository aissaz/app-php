<?php
session_start();
unset($_SESSION["user_name"]);
unset($_SESSION["name"]);
header("Location:./authentification.php");
?>
