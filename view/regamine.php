<!-- <form method="post" action="regamine.php">

        <input type="password" name="paroolike" placeholder="Parool">
        <input type="submit" value="Registreeri">
</form> -->
<?php
include('../controller/connect.php');
 
//if($_POST['paroolike'] == "paroolikene"){
echo '
<form method="post" action="regamine.php">

        <input type="text" name="kasutaja" placeholder="Kasutaja">
        <input type="password" name="parool" placeholder="Parool">
        <input type="password" name="parool2" placeholder="Parool uuesti">
        <input type="text" name="email" placeholder="Email">
        <input type="submit" value="Registreeri">
</form>';
//}

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
 }else{
 echo "Täida väljad";
 }

?>