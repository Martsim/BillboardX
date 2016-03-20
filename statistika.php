<?php
/**
 * Created by PhpStorm.
 * User: rauno
 * Date: 20.03.16
 * Time: 19:33
 */
include('connect.php');

$sth = $pdo->prepare("SELECT * FROM v_statistika;");
$sth->execute();
$stats = $sth->fetchAll();

foreach($stats as $stat){
    echo ucfirst($stat['nimi'])." kategoorias postitusi ".$stat['kokku'].".<br>";
}