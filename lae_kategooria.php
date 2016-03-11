<?php
/**
 * Created by PhpStorm.
 * User: rauno
 * Date: 6.03.16
 * Time: 21:33
 * Laeb AB-st kõik kategooria postitused AJAX kutse jaoks.
 */

if(!empty($_POST["id"])){
    include('connect.php');
    $id = $_POST["id"];

    try {
        $sth = $pdo->prepare("SELECT * FROM postitus WHERE kat_id = :id ORDER BY loodud DESC");
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
