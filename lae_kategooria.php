<?php
/**
 * Created by PhpStorm.
 * User: rauno
 * Date: 6.03.16
 * Time: 21:33
 * Laeb AB-st kõik kategooria postitused AJAX kutse jaoks.
 */

if(!empty($_POST["id"])){
    //$session_start(); //siin peaks toimuma ka kontroll, et kasutaja enda postitustel oleks ka kustutamise nupp
    //pigem peaks kustuta.php-s kontroll olema
    include('connect.php');
    $id = $_POST["id"];

    try {
        $sth = $pdo->prepare("SELECT * FROM postitus WHERE kat_id = :id");
        $sth->bindParam(':id', $id);
        $sth->execute();
        $sisud = $sth->fetchAll();

        echo json_encode($sisud);
    }catch(PDOException $e){
        echo "Viga";
    }

}else{
    echo "Ei saanud sobivat kategooria ID-d, et uuendada avalehte.";
}
