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

    </style>
</head>
<body>

<div id="päis">
    <div id="logo">Infoorum</div>
    <form>
        <input type="text" name="kasutaja" placeholder="Kasutaja">
        <input type="password" name="parool" placeholder="Parool">
        <input type="submit" value="Sisene">
        <a href="" style="color: black">Registreeru</a>
    </form>

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


    } catch(PDOException $e) {
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
            /*//sisul skrollides ei liiguta lehte, vaid ainult sisu
            $('.sisu').on('wheel', function(event) {
                event.preventDefault();
            });*/
        });
    </script>
</body>
</html>