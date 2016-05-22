<?php
session_start();
include('controller/connect.php');
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="et" lang="et">
<head>
    <title>Infoorum - KKK</title>
    <meta charset='utf-8'>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="style.css">
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
    <script src="js/cdn.js"></script>
    <script src="bootstrap/js/bootstrap.min.js"></script>
    <script src="js/script.js"></script>    	
	
</head>
<body>
<?php //PÄIS
	require_once 'view/päis_partial.php';
?>
	
	<div id="raam">

			
			<div id="kkkd">
				<div id="kys_1" class="kysdiv">
				<p class="kkkkys" ><b><?= $xml->kys1->$keel ?></b></p>
				<p class="kkkvas" ><?= $xml->vas1->$keel ?></p>
				</div>
				
				<div id="kys_2" class="kysdiv">
				<p class="kkkkys" ><b><?= $xml->kys2->$keel ?></b></p>
				<p class="kkkvas" ><?= $xml->vas2->$keel ?></p>
				</div>
				
				<div id="kys_3" class="kysdiv">
				<p class="kkkkys" ><b><?= $xml->kys3->$keel ?></b></p>
				<p class="kkkvas" ><?= $xml->vas3->$keel ?></p>
				</div>
			
			
			</div>						
	        
		
	</div>
</body>
</html>