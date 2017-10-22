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

            $idSQL = "SELECT Agent.Name, Percentage, PhoneNumber 
                      from Agent_has_closing, Agent 
                      where Agent.Agent_ID = agent_has_closing.Agent_ID
                      AND Closing_ID = 2";//YOU KNOW WHAT TO DO
            $idResults = mysqli_query($db, $idSQL);

            if ($idResults->num_rows > 0) {
            // output data of each row
              while($agentRow = $idResults->fetch_assoc()) { 
                // output data of each row
                echo "Name: " . $agentRow["Name"]. "<br>"; 
                echo "Percentage: " . $agentRow["Percentage"]. "<br>"; 
                echo "Phone: " . $agentRow["PhoneNumber"]. "<br>";
                echo "-------------<br>";
                
              }
            } else {
              echo "Agent has no closing";
            }
              
        ?>
      </p>
    </body>
</html>