<?php
session_start();

include('connect.php');
if(!empty($_POST["pealkiri"]) && !empty($_POST["kat_id"]) && !empty($_POST["sisu"]) ){
    $pealkiri = htmlspecialchars($_POST["pealkiri"]);
    $sisu = htmlspecialchars($_POST["sisu"]);
    $autor = htmlspecialchars($_SESSION['kasutaja']);

    $kat_id = $_POST["kat_id"];
    try {
        $sth = $pdo->prepare("
        INSERT INTO `infoorum_db`.`postitus` (
`id` ,
`autor` ,
`kat_id` ,
`pealkiri` ,
`sisu` ,
`loodud`
)
VALUES (
NULL , :autor, :kat_id, :pealkiri, :sisu, NOW( ) )");

        $sth->bindParam(':autor', $autor);
        $sth->bindParam(':kat_id', $kat_id);
        $sth->bindParam(':pealkiri', $pealkiri);
        $sth->bindParam(':sisu', $sisu);
        $sth->execute();

        echo "Sissekanne õnnestus.";
    }catch(PDOException $e) {
        echo 'Sisse kanne ebaõnnestus, VIGA: ' . $e->getMessage();
    }
}else{
    echo "Esines tühja välja";
}




?>