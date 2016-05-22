<?php
	session_start();
	include('controller/connect.php');
?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="et" lang="et">

<head>
    <title>Infoorum - Registreerimine</title>
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
	
	
	<div class="registreerimine">
  		
  		<div class="reg-vorm">
  		<h2 align="center"><?= $xml->reg_head->$keel ?></h2><br>
  		<h4 align="center"><i><?= $xml->reg_note->$keel ?><i></h2><br>
  		
  		
  		
  		<form id="reg-vorm2" method="post" action="registreerimine.php">
  			
  			<div>
              			<label for="kasutaja"><?= $xml->reg_user->$keel ?>*</label><br>
              			<input id="reg-input" type="text" name="kasutaja" placeholder="Kasutaja">
            		</div>
            		
            		<div>
              			<label for="parool"><?= $xml->reg_pass->$keel ?>*</label><br>
              			<input id="reg-input" type="password" name="parool" placeholder="Parool">
            		</div>
            		
            		<div>
              			<label for="parool2"><?= $xml->reg_pass2->$keel ?>*</label><br>
              			<input id="reg-input" type="password" name="parool2" placeholder="Parool uuesti">
            		</div>
            		
            		<div>
              			<label for="email"><?= $xml->reg_mail->$keel ?>*</label><br>
              			<input id="reg-input" type="text" name="email" placeholder="Email"><br>
            		</div>
  			
  			<div>
  				<input type="submit" class="btn btn-primary btn-lg btn-block" value=<?= $xml->reg_submit->$keel ?>>
  			</div>
  		
  		</form>
  		<?php
  		if(!empty(trim($_POST['kasutaja'])) && !empty(trim($_POST['parool']))  && !empty(trim($_POST['parool2'])) && !empty(trim($_POST['email']))){
     
     $kasutaja = trim($_POST['kasutaja']);   
     $parool = trim($_POST['parool']);
     $parool2 = trim($_POST['parool2']);
     $email = trim($_POST['email']);
     if($parool != $parool2){echo "Paroolid ei kattu"; exit;}
     if(strpos($kasutaja, ' ') !== false){echo "Kasutajanimes ei tohi tühikut olla"; exit;}
     
     $parool = password_hash($parool, PASSWORD_BCRYPT);
     
     if (!filter_var($email, FILTER_VALIDATE_EMAIL)) { echo "E-mail pole korrekktne"; exit;}
     if($kasutaja != strip_tags($kasutaja) || $email != strip_tags($email)){echo "Keelatud sümbolid"; exit;}
     

     try{
         $sth = $pdo->prepare("SELECT * FROM kasutaja");
         $sth->execute();
         $kasutajad = $sth->fetchAll();

        // sobilik kasutajanimi
         $sobilik = true;

         foreach($kasutajad as $k){
             //kui kasutaja on juba olemas
             if($k['kasutaja'] == $kasutaja) {
                 echo "Kasutja '".$kasutaja."' on juba olemas!";
                $sobilik = false;
                 break;
             }
         }

         if($sobilik){

	     	$token = bin2hex(openssl_random_pseudo_bytes(16));
	     	
		$headers = "From: Infoorum <infoorumcsut@webhost.ut.ee>\r\n";
	
		$title = "Infoorum: kasutaja registreerimine";
		// The message
		$message = "Tere\r\n\r\nAktivatsioonilink: http://infoorum.cs.ut.ee/controller/aktivatsioon.php/?token=$token\r\n Kasutajale: $kasutaja \r\n\r\n Kohtumiseni";
		
		// In case any of our lines are larger than 70 characters, we should use wordwrap()
		$message = wordwrap($message, 70, "\r\n");
		
		// Send
		if(mail($email, $title, $message, $headers)){
			echo "meil saadetud";
			try{
				$sth = $pdo->prepare("
		             INSERT INTO `infoorum_db`.`kasutaja` (
				`id` ,
				`kasutaja` ,
				`parool` ,
				`email` ,
				`aktiveeritud` ,
				`token` ,
				`registreeritud`
				)
		                VALUES (
		                NULL , :kasutaja, :parool, :email, 0, :token, NOW( ))
		             ");
		                 $sth->bindParam(':kasutaja', $kasutaja);
		                 $sth->bindParam(':parool', $parool);
		                 $sth->bindParam(':email', $email);
		                 $sth->bindParam(':token', $token);
		                 $sth->execute();
		           } catch(PDOException $e) {
        			 echo 'ERROR: ' . $e->getMessage();
     			   }
		}
         }
	 

     } catch(PDOException $e) {
         echo 'ERROR: ' . $e->getMessage();
     }
  }?>

  		</div>
  		
  	
	</div>
	
	

	
	

</body>

</html>
?>