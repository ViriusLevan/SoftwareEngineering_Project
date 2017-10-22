<!DOCTYPE html>
<html>
    <head>
   <title> Agent List </title>
      <link type='text/css' rel='stylesheet' href='style.css'/>
 </head>
 <body>
      <p>
        <?php
        	include('session.php');
          include_once('class_agent.php');
          	
          $a = new Agent(1, 1, "John", "0123456789", NULL);
          $a->getDownline($db);

        ?>
      </p>
    </body>
</html>