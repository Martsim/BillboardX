<?php
// enable sessions
session_start();
//header('Content-Type: text/html; charset=UTF-8');

//kui meldimis andmed on postitatud
if(!empty($_POST["kasutaja"]) && !empty($_POST["parool"])){
    try {
        $kasutaja = $_POST["kasutaja"];
        $parool = md5($_POST["parool"]);
        //andmed AB-ga ühenduse loomiseks
        $host = "localhost";
        $user = 'infoorum_rauno';
        $pass = 'k33ruline';
        $database = "infoorum_db";

        $pdo = new PDO("mysql:host=$host;dbname=$database;", $user, $pass);
        $sth = $pdo->prepare("SELECT * FROM kasutaja WHERE kasutaja = :kasutaja and parool = :parool");
        $sth->bindParam(':kasutaja', $kasutaja);
        $sth->bindParam(':parool', $parool);
        $sth->execute();

        $tulemus = $sth->fetchAll();
        if(count($tulemus) > 0){
            $_SESSION["melditud"] = true;
            $_SESSION["kasutaja"] = $kasutaja;

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
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset='utf-8'>
    <style type="text/css">
        div{
            background-color: lightgreen;
            border-radius: 10px;
            margin: 5px;
        }
        .container{
            overflow-x: scroll;
        }
        .container_lingid{
            max-height: 40px;
        }
        .container > div{
            background-color: aliceblue;
        }
        #raam{
            background-color: lightgoldenrodyellow;
            padding: 0;
            margin: 0;
        }
        #päis{
            padding: 40px;
            margin: 0;
        }
        #lingid{
            padding: 0;
            margin: 5px;
        }
        #logo{
            float: left;
            font-weight: bold;
            font-size: large;
        }
        form{
            float: right;
        }
        input{
            width: 100px;
        }
        #pop_up > input {
            width: 200px;
        }
        .cont_lingid{
            width: 600px;
            overflow:auto;
            border: 1px solid;
        }
        .kateg{
            height: 100px;
            width: 100px;
            padding: 12px;
            display: inline-block;
            transform: rotate(270deg);
            text-align: center;
            background-color: forestgreen;
        }
        .sisu{
            height: 150px;
            min-width: 150px;
            display: inline-block;
            padding: 25px;
            float:left;
            text-align:center;
        }
        .cont{
            overflow:auto;
            border: 1px solid;
            display: flex;
        }
        .pealkiri{
            font-weight: bold;
        }
        #postita_nupp{
            border: 1px solid black;
            background-color: darkolivegreen;
            float: right;
            padding: 15px;
        }
        #postita_nupp:hover{
            border: 3px solid black;
            font-weight: bold;
            background-color: darkslategray;
        }
        #tume_taust{
            display: none;
            position: absolute;
            top: 0%;
            left: 0%;
            width: 100%;
            height: 100%;
            background-color: black;
            z-index:1001;
            -moz-opacity: 0.8;
            opacity:.80;
            filter: alpha(opacity=80);
        }

        #pop_up {
            display: none;
            position: absolute;
            top: 25%;
            left: 25%;
            width: 50%;
            height: 50%;
            padding: 16px;
            border: 16px solid orange;
            background-color: white;
            z-index:1002;
            overflow: auto;
        }
    </style>
</head>
<body>
<div id="päis">
    <div id="logo">Infoorum</div>
    <!-- see on tume taust pop_up(modal)'i jaoks: -->
    <div id="tume_taust"></div>

    <?php
    //kui on toimunud edukas sisselogimine, näita sisse loginud kasutajale:
    if (isset($_SESSION["melditud"]) && $_SESSION["melditud"] == true){
        echo "<div style = 'float: right;'>Oled sisse logitud, ".$_SESSION['kasutaja']." <a href='logout.php'>Logi välja</a></div>";
        echo "<div id = 'postita_nupp'>Postita</div>";
        echo"<form id='pop_up'>
                <a href='#' id='sule_aken'>Sule aken</a><br>
                <input type='text' name='kat_id' placeholder='Kategooria ID(täisarv)'><br>
                <input type='text' name='pealkiri' placeholder='Pealkiri'><br>
                <label for='sisu_lisamine'>Sisu</label><br>
                <textarea form='pop_up' name = 'sisu' id='sisu_lisamine' rows='4' cols='50'></textarea><br>
                <input type='text' style='display:none' name='autor' value='".$_SESSION["kasutaja"]."'>
                <input type='submit' value='Postita'><br>

            </form>";//halb lahendus: seda vormi saab kasutaja muuta, niiet siin saaks ta autorit määrata ükskõik kelleks; autorit oleks vaja määrata teisiti

    }else{//Log-in form:
        echo'
        <form method="post" action="index.php">
            <input type="text" name="kasutaja" placeholder="Kasutaja">
            <input type="password" name="parool" placeholder="Parool">
            <input type="submit" value="Sisene">
        <a href="#" >Registreeru</a>
    </form>
        ';
    }
    ?>
</div>

<div id="lingid">
    <div class="container_lingid" align="center">
        <div class="cont_lingid">
            <a href="#" >Reeglid</a>
            <a href="#" >Info</a>
            <a href="#" >KKK</a>
            <a href="#" >Kontakt</a>
        </div>
    </div>
</div>

<div id="raam">

    <?php
    //kategooriate/sisu laadimine AB-st
    $host = "localhost";
    $user = 'infoorum_rauno';
    $pass = 'k33ruline';
    $database = "infoorum_db";

    try{
        $pdo = new PDO("mysql:host=$host;dbname=$database;", $user, $pass);

        $sth = $pdo->prepare("SELECT * FROM kategooria");
        $sth->execute();
        $kategooriad = $sth->fetchAll();

        $sth = $pdo->prepare("SELECT * FROM postitus");
        $sth->execute();
        $sisud = $sth->fetchAll();

        foreach($kategooriad as $kateg){
            echo '<div class="container">
                <div class="cont">
                    <div class="kateg">'.$kateg['nimi'].'</div>';
            $kategooria_id = $kateg['id'];

            //ebeaefektiivne - iga kategooria korral käib sisu massiivi läbi
            foreach($sisud as $sis){
                if($sis['kat_id'] == $kategooria_id){
                    echo "<div class='sisu'>";
                    echo "<p class ='pealkiri'>".$sis['pealkiri']."</p>";
                    echo '<div class = "sisu_tekst">'.$sis['sisu'].'</div>';

                    echo "</div>";
                }
            }
            echo '</div></div>';

        }
    }catch(PDOException $e) {
        echo 'ERROR: ' . $e->getMessage();
    }
    ?>

    <!--    <div class="container">
            <div class="cont">
                <div class="kateg">Kategooria 1</div>
                <div class="sisu">Sisu1</div>
                <div class="sisu">Sisu2</div>
                <div class="sisu">Sisu3</div>
                <div class="sisu">Sisu4</div>
                <input type="submit" class="lisa_sisu" value="lisa sisu">
            </div>
        </div>

        <div class="container">
            <div class="cont">
                <div class="kateg">Kategooria 2</div>
                <div class="sisu">Sisu2.1</div>
                <div class="sisu">Sisu2</div>
                <div class="sisu">Sisu3</div>
                <div class="sisu">Sisu4</div>
                <input type="submit" class="lisa_sisu" value="lisa sisu">
            </div>
        </div>
    </div>
    <input type="submit" id="lisa_kat" value="lisa kategooria">-->

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
    <script>
        $( document ).ready(function() {
            $('#postita_nupp').on('click', function(event){
                document.getElementById('pop_up').style.display='block';
                document.getElementById('tume_taust').style.display='block';
                event.preventDefault();
            });
            $('#sule_aken').on('click', function(){
                document.getElementById('pop_up').style.display='none';
                document.getElementById('tume_taust').style.display='none';
            });

            $("#pop_up").submit(function(){
                var values = $(this).serialize();
                $.ajax({
                    url: "postita.php",
                    type: "post",
                    data: values ,
                    async: false,

                    success: function (vastus) {
                        // you will get response from your php page (what you echo or print)
                        alert(vastus);
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        console.log(textStatus, errorThrown);
                    }

                });
            });
        });
    </script>
</body>
</html>