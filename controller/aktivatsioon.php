<?php
if(!empty($_GET['token'])){
	include('connect.php');
	$token = $_GET['token'];

	$sth = $pdo->prepare("SELECT * FROM kasutaja WHERE token = :token");
        $sth->bindParam(':token', $token);
        $sth->execute();

        $tulemus = $sth->fetchAll();
        
        if(count($tulemus) > 0){
        	$sth = $pdo->prepare("UPDATE `infoorum_db`.`kasutaja` SET `aktiveeritud` = 1 WHERE token = :token;");
        	$sth->bindParam(':token', $token);
        	$sth->execute();
        	
        	
        }

}
$host = $_SERVER["HTTP_HOST"];
//$path = rtrim(dirname($_SERVER["PHP_SELF"]), "/\\");
header("Location: http://$host/index.php");
?>