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

            $idSQL = "SELECT Agent.Name, agent_involved_in_closing.earning, 
                        agent_involved_in_closing.workedAs, PhoneNumber 
                      from Agent_involved_in_closing, Agent 
                      where Agent.Agent_ID = agent_involved_in_closing.Agent_ID
                      AND Closing_ID = 2";//YOU KNOW WHAT TO DO
            $idResults = mysqli_query($db, $idSQL);

            if ($idResults->num_rows > 0) {
              while($agentRow = $idResults->fetch_assoc()) { 
                $workedAs = "";
                $workedAs = setWorkedAs($agentRow["workedAs"]);

                // output data of each row
                echo "Name: " . $agentRow["Name"]. "<br>"; 
                echo "Earned: " . $agentRow["earning"]. "<br>"; 
                echo "Worked as :"; .  
                echo "Phone: " . $agentRow["PhoneNumber"]. "<br>";
                echo "-------------<br>";
                
              }
            } else {
              echo "Agent has no closing";
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