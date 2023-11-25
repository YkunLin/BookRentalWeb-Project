<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();


echo "<h1 style='color: green;'>you are successfully logout of the system.";

$_SESSION = null;

session_destroy();
header("Location: index.php");

?>