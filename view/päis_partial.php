<?php
/**
 * Created by PhpStorm.
 * User: rauno
 * Date: 21.05.16
 * Time: 13:40
 * PÄIS KOOS LINKIDEGA
 */
$root = realpath($_SERVER["DOCUMENT_ROOT"]);
//keele valimine
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
            <option value="et">Eesti keeles</option>
            <option value="en">In English</option>
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

        //Näita postita nuppu ainult avalehel
        if($_SERVER['REQUEST_URI'] === '/index.php'){
            echo "<div id = 'postita_nupp'>".$xml->postita->$keel."</div>";
        }


        //Postitamise Modal
        echo"</div><form id='pop_up'>
                <a href='' id='sule_aken'>". $xml->sule->$keel ."</a><br>
               <select name='kat_id'>";
        foreach($tulemus as $t){
            echo "<option value =".$t['id'].">". ucfirst($t['nimi']) ." </option>";
        }
        echo "</select><br>
                <label>". $xml->pealkiri->$keel ."<br>
                <input type='text' title = 'pealkiri' name='pealkiri' placeholder='". $xml->pealkiri->$keel ."'></label><br>
                <label for='sisu_lisamine'>". $xml->sisu->$keel ."</label><br>
                <textarea form='pop_up' title = 'sisu' name = 'sisu' id='sisu_lisamine' rows='4' cols='50'></textarea><div id='counter'>0</div><br>
                <input type='submit' value='". $xml->postita->$keel ."'><br>

            </form>";


    }else{//Log-in form:
        echo'
        <form id="login_form" method="post" action="../index.php">
            <label for="kasutaja">'. $xml->kasutaja->$keel .': </label>
            <input type="text" name="kasutaja" placeholder='. $xml->kasutaja->$keel.'  id="kasutaja">
            <label for="parool">'. $xml->parool->$keel .': </label>
            <input type="password" name="parool" placeholder='.$xml->parool->$keel.' id="parool">
            <input type="submit" value="'. $xml->sisene->$keel .'">
            <a href="registreerimine.php" >' .$xml->registreeru->$keel. '</a>
            <div class ="fb-no-jump">

            </div>
        </form>
        ';
    }
    //Sisu laadimise modal
    echo "<form id='pop_up2'>
                <a href='' id='sule_aken2'>". $xml->sule->$keel ."</a><br>
                <strong><p id='modal_pealkiri'></p></strong>
                <em><p id='modal_autor'></p></em>
                <p id='modal_sisu'></p>
                <br>
                <hr>
                <p id = 'modal_kommentaar'></p>
                ";
    if (isset($_SESSION["melditud"]) && $_SESSION["melditud"] == true){
        echo "<textarea form='pop_up2' title = 'kommenteeri' name = 'modal_komment' id='modal_kommenteeri' rows='2' cols='30'></textarea>
                    <input type='submit' value='Kommenteeri' id='postita_kommentaar'><br>";
    }

    echo "</form>";
    ?>
</div>

<div id="lingid">
    <a href="index.php" ><?= $xml->kodu->$keel ?></a>
    <a href="KKK.php" ><?= $xml->kkk->$keel ?></a>
    <a href="kontakt.php" ><?= $xml->kontakt->$keel ?></a>
    <a href="anneta.php" ><?= $xml->anneta->$keel ?></a>
</div>
