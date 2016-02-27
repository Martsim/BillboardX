<?php
$host = "localhost";
$user = 'infoorum_rauno';
$pass = 'k33ruline';
$database = "infoorum_db";

try{
    $pdo = new PDO("mysql:host=$host;dbname=$database;", $user, $pass);
}catch(PDOException $e){
    echo 'ERROR: ' . $e->getMessage();

}

?>