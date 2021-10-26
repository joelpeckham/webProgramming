<?php
//Logout
session_start();
$back = isset($_SESSION['returnFile']) ? $_SESSION['returnFile'] : "index.php";
session_destroy();
header("Location: $back");
?>