<?php
// enable sessions
session_start();
//header('Content-Type: text/html; charset=UTF-8');
//andmebaasiga ühendamine:
include('controller/connect.php');

//kui meldimis andmed on postitatud
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
    <title>Infoorum</title>
    <meta charset='utf-8'>
    <link rel="stylesheet" type="text/css" href="style.css">
    <!-- Added cdn check.-->
    <script src="https://code.jquery.com/jquery-2.2.3.js"></script>
    <script src="js/cdn.js"></script>
    <script src="js/jquery-ui.min.js"></script>
    <script src="js/script.js"></script>
    <script type="text/javascript" src="./fbapp/fb.js"></script>
</head>
<body>
<?php //keele valimine
    	if(isset($_COOKIE['keel'])) {
    		$keel = $_COOKIE['keel'];
		
    	}else{
    		$keel = "et";
    	}
    	$xml = simplexml_load_file("xml/keel.xml");
?>
<div id="päis">
    <a id="logo" href="index.php">Infoorum</a>
    
    <!--Keelevalik-->
    <form id="keele_vorm" method="get" action="/controller/keel_kypsisesse.php">
    	<select name="keel" title="Keele valik" onchange="this.form.submit()">
    		<option><?= $xml->keel->$keel ?></option>
    		<option value="et">Eesti</option>
  		<option value="en">English</option>
	</select>
    </form>
    <!-- see on tume taust pop_up(modal)'i jaoks: -->
    <div id="tume_taust"></div>

    <?php
    //kui on toimunud edukas sisselogimine, näita sisse loginud kasutajale:
    if (isset($_SESSION["melditud"]) && $_SESSION["melditud"] == true){
        //leian kõik kategooriad, salvestan array-na $tulemusse
        $sth = $pdo->prepare("SELECT * FROM get_kategooriad_idni");
        $sth->execute();
        $tulemus = $sth->fetchAll();


        echo "<div id = 'melditud'><div style = 'float: right;'>".$xml->logitud->$keel."<span id='kasutaja'>".$_SESSION['kasutaja']."</span> <a href='controller/logout.php'>".$xml->välja->$keel."</a></div>";
        echo "<div id = 'postita_nupp'>".$xml->postita->$keel."</div></div>";

        echo"<form id='pop_up'>
                <a href='#' id='sule_aken'>". $xml->sule->$keel ."</a><br>
               <select name='kat_id'>";
        foreach($tulemus as $t){
            echo "<option value =".$t['id'].">". ucfirst($t['nimi']) ." </option>";
        }
        echo "</select><br>
                <label for='pealkiri'>". $xml->pealkiri->$keel ."</label><br>
                <input type='text' name='pealkiri' placeholder='". $xml->pealkiri->$keel ."'><br>
                <label for='sisu_lisamine'>". $xml->sisu->$keel ."</label><br>
                <textarea form='pop_up' name = 'sisu' id='sisu_lisamine' rows='4' cols='50'></textarea><div id='counter'>0</div><br>
                <input type='submit' value='". $xml->postita->$keel ."'><br>

            </form>";

    }else{//Log-in form:
        echo'
        <form id="login_form" method="post" action="index.php">
            <label for="kasutaja">'. $xml->kasutaja->$keel .': </label>
            <input type="text" name="kasutaja" placeholder='. $xml->kasutaja->$keel.'  id="kasutaja">
            <label for="parool">'. $xml->parool->$keel .': </label>
            <input type="password" name="parool" placeholder='.$xml->parool->$keel.' id="parool">
            <input type="submit" value="'. $xml->sisene->$keel .'">
            <a href="view/regamine.php" >' .$xml->registreeru->$keel. '</a>
            <div class ="fb-no-jump">
            
            </div>
        </form>
        ';
    }
    ?>
</div>

<div id="lingid">
    <a href="index.php" ><?= $xml->kodu->$keel ?></a>
    <a href="#" ><?= $xml->reeglid->$keel ?></a>
    <a href="#" ><?= $xml->info->$keel ?></a>
    <a href="#" ><?= $xml->kkk->$keel ?></a>
    <a href="kontakt.php" ><?= $xml->kontakt->$keel ?></a>
    <a href="anneta.php" ><?= $xml->anneta->$keel ?></a>
</div>

<div id="raam">
    <?php
    //
    //kategooriate/sisu laadimine AB-st
    ///////////////////////////////////

    $sth = $pdo->prepare("SELECT * FROM get_kategooriad");
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
            echo "<div class='sisu' id = ".$s['id'].">";
                    echo "<p class ='sisu_pealkiri'>".$s['pealkiri']."</p>";
                    echo "<p class ='sisu_autor'>".$s['autor']."</p>";
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