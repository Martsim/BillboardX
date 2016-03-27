<?php //keele salvestamine küpsistesse>
    	if(!empty($_GET['keel'])){
    		setcookie('keel',$_GET['keel'],time() + (86400 * 365), "/");//4. parameeter(path) vajalik, määrab kus cookie kehtib
    	}
  	
header("Location: ../index.php");

