<?php
/**
 * Created by PhpStorm.
 * User: rauno
 * Date: 10.04.16
 * Time: 21:48
 */
include('connect.php');
    //ID-d:
    $kat = $_POST['kat_id'];//selle kategooria
    $post = $_POST['sisu_id'];//viimane post

    $sth = $pdo->prepare("SELECT * FROM postitus WHERE kat_id = :id ORDER BY loodud DESC LIMIT 1");
    $sth->bindParam(':id', $kat);
    $sth->execute();
    $sisud = $sth->fetchAll();

    //kui andmebaasi viimase postituse ID pole sama mis lehel (uus post vahepeal)
    if(!empty($post) && $post != $sisud[0]['id']){
        echo json_encode($sisud);
    }else{
        echo "pole uut";
    }

