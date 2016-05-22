<?php
// enable sessions
session_start();
//header('Content-Type: text/html; charset=UTF-8');
//andmebaasiga ühendamine:
include('controller/connect.php');

//kui meldimisandmed on postitatud
if(!empty($_POST["kasutaja"]) && !empty($_POST["parool"])){
    try{
        $kasutaja = $_POST["kasutaja"];
        $parool = $_POST["parool"];

        $sth = $pdo->prepare("SELECT * FROM kasutaja WHERE kasutaja = :kasutaja");
        $sth->bindParam(':kasutaja', $kasutaja);
        $sth->execute();

        $tulemus = $sth->fetchAll();

        if(count($tulemus) > 0){
            //lähen tulemus array sisse ja võtan sealt parooli key value
            foreach($tulemus as $t){
                $paroolABst= $t['parool'];
                $aktiveeritud = $t['aktiveeritud'];
            }

            if(password_verify($parool, $paroolABst) && $aktiveeritud == 1){
                $_SESSION["melditud"] = true;
                $_SESSION["kasutaja"] = $kasutaja;
            }
            //else{echo "Vale parool";}

            // redirect user to home page, using absolute path
            $host = $_SERVER["HTTP_HOST"];
            $path = rtrim(dirname($_SERVER["PHP_SELF"]), "/\\");
            header("Location: http://$host$path/index.php");
            exit;


        }else{
            echo "Kasutajanimi või parool on vale";

        }
    }catch(PDOException $e) {
        echo 'ERROR: ' . $e->getMessage();
    }
}
//else if(!isset($_SESSION["melditud"]) && (empty($_POST["kasutaja"]) || empty($_POST["parool"]))) {
// echo "Esines tühja välja";}
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="et" lang="et">
<head>
    <title>Infoorum - avaleht</title>
    <meta charset='utf-8'>
    <meta name=viewport content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" href="icon.ico" type="image/x-icon" />
    <link rel="stylesheet" type="text/css" href="style.css">
    <!-- Added cdn check.-->
    <script async src="https://code.jquery.com/jquery-2.2.3.min.js"></script>
    <script src="js/cdn.js"></script>
    <script async src="js/jquery-ui.min.js"></script>
    <script async src="js/script.js"></script>
    <script async type="text/javascript" src="./fbapp/fb.js"></script>
</head>
<body>

<?php
//päis ja lingid:
require_once 'view/päis_partial.php';

?>

<div id="raam">
    <?php
    //
    //kategooriate/sisu laadimine AB-st
    ///////////////////////////////////

    $sth = $pdo->prepare("SELECT * FROM kategooria ORDER BY id desc ");
    $sth->execute();
    $kategooriad = $sth->fetchAll();

    foreach($kategooriad as $k){

        $sth = $pdo->prepare("SELECT * FROM postitus WHERE kat_id = :kat_id ORDER BY loodud DESC LIMIT :mitmendast, 4");
        $sth->bindParam(":kat_id", $k['id']);
        $mitmendast = (!empty($_GET["kat".$k['id']])) ? $_GET['kat'.$k['id']] : 0;
        $mitmendast = (int)$mitmendast;//castimiseta ei tööta
        $sth->bindParam(":mitmendast",  $mitmendast, PDO::PARAM_INT);
        $sth->execute();
        $sisud = $sth->fetchAll();

        $kategooria_id = $k['id'];
            echo '<div class="container">
            <div class="kateg"><span class="vert">'.$k['nimi'].'</span></div>
                <div class="cont" id = "'.$kategooria_id.'">

                 ';

        foreach($sisud as $s){
            echo "<div class='sisu' id = p".$s['id'].">";
                    echo "<p class ='sisu_pealkiri'>".$s['pealkiri']."</p>";
                    echo "<p class ='sisu_autor'>".$s['autor']."</p>";
                    echo '<img src="comments.svg" class="komm_pilt" alt="comment">';
                    //postituse kommentaaride loendur
                    $sth = $pdo->prepare("SELECT id FROM kommentaar WHERE post_id = ?");
                    $sth->execute([$s['id']]);
                    echo " ".count($sth->fetchAll());


                    echo '<div class = "sisu_tekst">'.$s['sisu'].'</div>';
                    //case insensitive comparing(strcasecmp)
                    if(!empty($_SESSION["kasutaja"]) && strcasecmp ($_SESSION["kasutaja"], $s['autor']) == 0){
                        echo "<a class='kustuta_nupp' href='../controller/kustuta.php?id=".$s['id']."'>Kustuta postitus</a>";
                    }

                    echo "</div>";
        }
        
        echo '</div></div>';
    }

    ?>
</div>

<footer><div id="stats_nupp"> <?= $xml->statistika->$keel ?> </div>
	<p id="stats"></p>
</footer>
</body>
</html>