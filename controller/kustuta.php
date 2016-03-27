<?php
session_start();
include('connect.php');

if(!empty($_GET["id"])){
	$id = $_GET["id"];
	$kasutaja= $_SESSION['kasutaja'];

	//kui see postitus(id) on tõesti selle autori post
	$sth = $pdo->prepare("SELECT * FROM postitus WHERE autor = :kasutaja and id = :id");
    $sth->bindParam(':kasutaja', $kasutaja);
    $sth->bindParam(':id', $id);
    $sth->execute();

    $tulemus = $sth->fetchAll();

    if(count($tulemus) > 0){
    	$sth = $pdo->prepare("DELETE FROM postitus WHERE id = :id");
    	$sth->bindParam(':id', $id);
    	$sth->execute();
    }

}
//tagasi index.php-sse
$host = $_SERVER["HTTP_HOST"];
//$path = rtrim(dirname($_SERVER["PHP_SELF"]), "/\\");
header("Location: http://$host/index.php");

?>
