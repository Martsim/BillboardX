<?php
//kõik ühenduse loomised võiks nüüd läbi selle faili käia:
include('connect.php');
if(!empty($_POST["pealkiri"]) && !empty($_POST["kat_id"]) && !empty($_POST["sisu"]) && !empty($_POST["autor"])){
    $pealkiri = $_POST["pealkiri"];
    $sisu = $_POST["sisu"];
    $autor = $_POST["autor"];

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