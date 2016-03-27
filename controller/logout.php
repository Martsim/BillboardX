<?
session_start();
session_destroy();

$host = $_SERVER["HTTP_HOST"];
$path = rtrim(dirname($_SERVER["PHP_SELF"]), "/\\");
header("Location: ../index.php");