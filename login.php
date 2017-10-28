<?php
   include("config.php");
   session_start();
   
   if($_SERVER["REQUEST_METHOD"] == "POST") {
      // username and password sent from form 
      
      $myusername = mysqli_real_escape_string($db,$_POST['username']);
      $mypassword = mysqli_real_escape_string($db,$_POST['password']); 
      
      $sql = "SELECT username FROM admin WHERE username = '$myusername' and password = '$mypassword'";
      $result = mysqli_query($db,$sql);
      if (!$result) {
    		printf("Error: %s\n", mysqli_error($db));
    		exit();
		}
      $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
      $count = mysqli_num_rows($result);
      
      // If result matched $myusername and $mypassword, table row must be 1 row
		
      if($count == 1) {
         $_SESSION['login_user'] = $myusername;
         
         header("location: welcome.php");
      }else {
         $error = "Your Login Name or Password is invalid";
      }
   }
?>

<html>
   <head>
   <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/css/bootstrap.min.css" integrity="sha384-PsH8R72JQ3SOdhVi3uxftmaW6Vc51MKb0q5P2rRUpPvrszuE4W1povHYgTpBfshb" crossorigin="anonymous">
   <link rel="stylesheet" type="text/css" href="style.css">
      <title>Login</title>
   </head>
   <body class="loginbody">
      <div align = "center">
            <div style = "margin:10% 40%">
            <img class="mainlogo" src="img/mainlogo.png" alt="">
               <form action = "" method = "post">
                  <input type = "text" name = "username" class = "form-control form" placeholder="Username" />
                  <input type = "password" name = "password" class = "form-control form" placeholder="Password" />
                  <input type = "submit" value = " LOGIN " class="btn btn-primary form" /><br />
               </form>
               <div style = "font-size:11px; color:#cc0000; margin-top:10px"><?php if(isset($error))echo $error; ?></div>
            </div>
      </div>
   </body>
</html>