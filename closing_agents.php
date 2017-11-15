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
          $row = $_GET["id"];

          $idSQL = "SELECT Agent.Agent_ID, Agent.Name, agent_involved_in_closing.earning, 
                      agent_involved_in_closing.workedAs, PhoneNumber 
                    from Agent_involved_in_closing, Agent 
                    where Agent.Agent_ID = agent_involved_in_closing.Agent_ID
                    AND agent.Agent_ID != 0
                    AND closing.Closing_ID = ". $row;
          $idResults = mysqli_query($db, $idSQL);

          if ($idResults->num_rows > 0) {
            echo "<table>";
            echo "<tr> <th>Name</th> <th>Earned</th> <th>Worked as</th> 
                <th>Phone Number</th> <th>Agent Details</th> </tr>";
            while($agentRow = $idResults->fetch_assoc()) { 
              $workedAs = "";
              $workedAs = setWorkedAs($agentRow["workedAs"]);

              // output data of each row
              echo "<tr><td> " . $agentRow["Name"]. " </td>"; 
              echo "<td> " . $agentRow["earning"]. " </td>"; 
              echo "<td> " .  $workedAs . " </td>";
              echo "<td> " . $agentRow["PhoneNumber"]. " </td>";
              
              ?> 
                <td>
                  <a class="btn btn-warning" href='agent_details.php?id=<?php echo $agentRow["Agent_ID"]; ?>'>Click</a> 
                </td></tr>
              <?php

            }
          } else {
            //SHOULD NEVER HAPPEN
            echo "No agents found";
          }
              
        ?>
      </p>
    </body>

    <?php 
      function setWorkedAs($code){
        $workedAs = "";
        if($code>18){
          $workedAs = "Agent 4";
        }else if($code>12){
          $workedAs = "Agent 3";
        }else if($code>6){
          $workedAs = "Agent 2";
        }else{
          $workedAs = "Agent 1";
        }

        if($code%6==0){
          $workedAs .= "'s 3rd upline";
        }else if($code%6==1){
          //The actual agent
        }else if($code%6==2){
          $workedAs .= "'s Branch President";
        }else if($code%6==3){
          $workedAs .= "'s Vice President";
        }else if($code%6==4){
          $workedAs .= "'s 1st upline";
        }else {
          $workedAs .= "'s 2nd upline";
        }

        return $workedAs;
      }
    ?>
</html>