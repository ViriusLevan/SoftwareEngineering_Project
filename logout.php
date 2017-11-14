<?php
   // session_start();
  	session_start();
	unset($_SESSION["login_user"]);
  	header("location: login.php");

   // if(session_destroy()) {
   //    header("Location: login.php");
   // }
?>