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

          $PPSQL = "SELECT Percentage, JobName
                          FROM `paypercentages`,closing
                          WHERE (DATEDIFF (closing.Date,ValidityEnd)<=0 OR ValidityEnd IS NULL)
                            AND DATEDIFF (closing.Date,ValidityStart)>=0
                            AND closing.closing_ID = ".$_GET["id"];
          $PPResults = mysqli_query($db, $PPSQL);
          $PresP ="";
          $VPP = "";
          while ($PPRow = $PPResults->fetch_assoc()) {
            if($PPRow["JobName"] == "President"){
              $PresP = $PPRow["Percentage"];
            }else if($PPRow["JobName"] == "Vice President"){
              $VPP = $PPRow["Percentage"];
            }
          }

          $idSQL = "SELECT Agent.Agent_ID, Agent.Name, agent_involved_in_closing.earning, 
                      agent_involved_in_closing.workedAs, PhoneNumber, aCount.nAgents AS ac
                    from Agent_involved_in_closing, Agent, 
                      (SELECT closing.closing_ID AS cID, 
                          COUNT(agent_involved_in_closing.Agent_ID) AS nAgents
                        FROM agent_involved_in_closing, closing
                        WHERE agent_involved_in_closing.Closing_ID = closing.closing_ID
                            AND agent_involved_in_closing.workedAs IN (1,7,13,19)
                        GROUP BY closing.closing_ID) aCount
                    WHERE Agent.Agent_ID = agent_involved_in_closing.Agent_ID
                    AND agent.Agent_ID != 0
                    AND aCount.cID = agent_involved_in_closing.Closing_ID
                    AND agent_involved_in_closing.Closing_ID = ".$row;
          $idResults = mysqli_query($db, $idSQL);

          if ($idResults->num_rows > 0) {
            echo "<table>";
            echo "<tr> <th>Name</th> <th>Earned</th> <th>Percentage</th> <th>Worked as</th> 
                <th>Phone Number</th> <th>Agent Details</th> </tr>";
            while($agentRow = $idResults->fetch_assoc()) { 
              $workedAs = "";
              $Percentage = "";
              $Percentage = setPercentage($agentRow["workedAs"],$agentRow["ac"],$PresP,$VPP);
              $workedAs = setWorkedAs($agentRow["workedAs"]);

              // output data of each row
              echo "<tr><td> " . $agentRow["Name"]. " </td>"; 
              echo "<td> " . $agentRow["earning"]. " </td>"; 
              echo "<td> " . $Percentage . " </td>"; 
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
      function setPercentage($code,$count,$pres,$vp){
        $workedAs = "";
        if($count == 4){
          $workedAs = 25;
        }else if($code>12){
          $workedAs = 25;
        }else if($code>6 && $count==3){
          $workedAs = 25;
        }else if($code>6 && $count==2){
          $workedAs = 50;
        }else if($code<7 && ($count==3 || $count==2)){
          $workedAs = 50;
        }else if($code<7 && $count==1){
          $workedAs = 100;
        }

        if($code%6==0){
          $workedAs = 1;
        }else if($code%6==1){
          //The actual agent
        }else if($code%6==2){
          $workedAs = $pres;
        }else if($code%6==3){
          $workedAs = $vp;
        }else if($code%6==4){
          $workedAs = 7;
        }else {
          $workedAs = 2;
        }

        return $workedAs;
      }
    ?>
</html>