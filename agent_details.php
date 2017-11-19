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
                "SELECT agent.Agent_ID, agent.Name, agent.PhoneNumber, 
                  agent.ImmediateUpline_ID, agent.Status,branch.branch_id,branch.Name 
                  FROM agent,branch,agent_branch_employment 
                  WHERE agent.Agent_ID = agent_branch_employment.Agent_ID
                  AND agent_branch_employment.Branch_ID = branch.branch_id
                  AND agent_branch_employment.End IS NULL
                  AND agent.Agent_ID = " . $row;
          $AgentResult = mysqli_query($db, $AgentSQL);
          $AgentRow = $AgentResult->fetch_assoc();

          $a = new Agent($AgentRow["Agent_ID"], 
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