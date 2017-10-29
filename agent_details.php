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
          
          $a->getEarningTotal($db);
          $a->printDetails();

          ?>
            <a class="btn btn-warning" 
              href='agent_involved_in_closing.php?id=<?php echo $row; ?>'
              >Click to see Agent's involvement in closings</a> 
            <br>
          <?php

          echo"Agent's Downlines <br>-----------------------------------<br>";
          $a->getDownline($db);

        ?>
      </p>
    </body>
</html>