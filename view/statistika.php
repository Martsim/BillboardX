<?php
/**
 * Created by PhpStorm.
 * User: rauno
 * Date: 20.03.16
 * Time: 19:33
 */
include('../controller/connect.php');

//keele valimine
    	if(isset($_COOKIE['keel'])) {
    		$keel = $_COOKIE['keel'];
		
    	}else{
    		$keel = "et";
    	}
    	$xml = simplexml_load_file("../xml/keel.xml");


$sth = $pdo->prepare("SELECT * FROM v_statistika;");
$sth->execute();
$stats = $sth->fetchAll();
foreach($stats as $stat){
    echo $stat['kokku']." ".$xml->stats_lause->$keel." ".$stat['nimi'].".<br>";
}