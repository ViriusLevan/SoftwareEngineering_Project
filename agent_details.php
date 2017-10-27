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
          require('class_agent.php');
          
          $row = $_GET["id"];

          $AgentSQL =
                "SELECT * FROM agent WHERE Agent_ID=" . $row;
          $AgentResult = mysqli_query($db, $AgentSQL);
          $AgentRow = $AgentResult->fetch_assoc();

          $a = new Agent($AgentRow["Agent_ID"], $AgentRow["Branch_ID"], 
            $AgentRow["Name"], $AgentRow["PhoneNumber"], $AgentRow["ImmediateUpline_ID"]);
          
          $a->printDetails();
          $a->getDownline($db);

        ?>
      </p>
    </body>
</html>