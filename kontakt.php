<?php
session_start();
include('connect.php');
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="et" lang="et">
<head>
    <title>Infoorum</title>
    <meta charset='utf-8'>
    <link rel="stylesheet" type="text/css" href="style.css">
    <!-- Added cdn check.-->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
    <script src="cdn.js"></script>
    <script src="script.js"></script>
    <script src="http://maps.googleapis.com/maps/api/js"></script>
    	
	<script>
	var myCenter=new google.maps.LatLng(58.378242, 26.714601);
	
	function initialize() {
	  var mapProp = {
	    center:myCenter,
	    zoom:10,
	    mapTypeId:google.maps.MapTypeId.ROADMAP
	  };
	  var map=new google.maps.Map(document.getElementById("googleMap"),mapProp);
	  
	  var marker=new google.maps.Marker({
  		position:myCenter,
  	  });
          marker.setMap(map);
          
          var infowindow = new google.maps.InfoWindow({
  	  	content:"BXCode"
          });

	  google.maps.event.addListener(marker, 'click', function() {
          	infowindow.open(map,marker);
          });
          
          // Suurenda 14-ni, kui markerile klikkida
	  google.maps.event.addListener(marker,'click',function() {
          	map.setZoom(14);
          	map.setCenter(marker.getPosition());
          });
	}
	google.maps.event.addDomListener(window, 'load', initialize);
	</script>
</head>
</body>
	<div id="päis">
		<a id="logo" href="index.php">Infoorum</a>
	</div>
	
	<div id="lingid">
	    <div class="container_lingid">
	        <div class="cont_lingid">
	            <a href="#" >Reeglid</a>
	            <a href="#" >Info</a>
	            <a href="#" >KKK</a>
	            <a href="kontakt.php" >Kontakt</a>
	        </div>
	    </div>
	</div>
	
	<div id="raam">
		<div class="kontakt_container">
			<?php 
				$action=$_REQUEST['action']; 
				/* kasutada siin meilimise_katsetus.php vormi? */
				if ($action=="")    /* kuva kontakti vorm */ 
				    { 
				    ?>
				    <div class="kontakt"> 
				    <kontakt_vorm  action="" method="POST" enctype="multipart/form-data"> 
				    <input type="hidden" name="action" value="submit"> 
				    Nimi:<br> 
				    <input name="name" type="text" value="" size="30"/><br> 
				    Email:<br> 
				    <input name="email" type="text" value="" size="30"/><br> 
				    Sõnum:<br> 
				    <textarea name="message" rows="7" cols="30"></textarea><br> 
				    <input type="submit" value="Saada kiri"/> 
				    </kontakt_vorm> 
				    </div>
				    <?php 
				    }  
				else                /* saada sisestatud andmed */ 
				    { 
				    $name=$_REQUEST['name']; 
				    $email=$_REQUEST['email']; 
				    $message=$_REQUEST['message']; 
				    if (($name=="")||($email=="")||($message=="")) 
				        { 
				        echo "Kõik väljad peavad olema täidetud, palun täitke <a href=\"\">kontaktivorm</a> uuesti."; 
				        } 
				    else{         
				        $from="From: $name<$email>\r\nReturn-path: $email"; 
				        $subject="Kontakti vormi kaudu saadetud kiri"; 
				        mail("noreply@infoorum.cs.ut.ee", $subject, $message, $from); 
				        echo "Kiri saadetud!"; 
				        } 
				    }   
			?>
		</div>
	        <div id="googleMap"></div>
		
	</div>
</body>
</html>