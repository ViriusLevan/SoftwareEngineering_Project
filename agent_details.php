<!DOCTYPE html>
<html>
    <head>
    <title> Agent List </title>
    <link type='text/css' rel='stylesheet' href='style.css'/>
    <link rel="stylesheet" href="treant-js-master/Treant.css">
    <link rel="stylesheet" href="treant-js-master/es-tree.css">
 </head>
 <body>
      <p>
        <?php
        	include('session.php');
          require('class_agent.php');
          
          $row = $_GET["id"];

          $AgentSQL =
                "SELECT agent.Agent_ID AS aID, agent.Name AS aName, agent.PhoneNumber AS phone, 
                  agent.ImmediateUpline_ID AS iupID, agent.Status,branch.branch_id AS bID,branch.Name AS bName 
                  FROM agent,branch,agent_branch_employment 
                  WHERE agent.Agent_ID = agent_branch_employment.Agent_ID
                  AND agent_branch_employment.Branch_ID = branch.branch_id
                  AND agent_branch_employment.End IS NULL
                  AND agent.Agent_ID = " . $row;
          $AgentResult = mysqli_query($db, $AgentSQL);
          $AgentRow = $AgentResult->fetch_assoc();

          $a = new Agent($AgentRow["aID"], $AgentRow["bID"],
            $AgentRow["aName"], $AgentRow["phone"], $AgentRow["iupID"]);
          
          $a->getEarningTotal($db);
          $a->printDetails();

          ?>
            <a class="btn btn-warning" 
              href='agent_involved_in_closing.php?id=<?php echo $row; ?>'
              >Click to see Agent's involvement in closings</a> 
            <br>
          <?php

<<<<<<< HEAD
          // echo"Agent's Downlines <br>-----------------------------------<br>";
          // $a->getDownline($db);
=======
          //echo"Agent's Downlines <br>-----------------------------------<br>";
          //$a->getDownline($db);
>>>>>>> a864ab38e5107b6e96abdae42a8eddf568904694


        ?>

        <div class="chart" id="agent-tree"></div>

        <?php
          $chosenAgentId = $_GET['id'];

          $sql = "SELECT * FROM agent where status = 1";
          $result = mysqli_query($db,$sql);

          $agentInvolved = array();
          $data = array();
          $uplineID = -1;
          $count = 2;

          while($row = $result->fetch_assoc()) {
              if ($row['Agent_ID'] == $chosenAgentId) {
                  $data['agentID'] = $row['Agent_ID'];
                  $data['agentName'] = $row['Name'];
                  $data['uplineID'] = $row['ImmediateUpline_ID'];
                  $uplineID = $row['ImmediateUpline_ID'];
                  $data['agentStatus'] = $row['Status'];
                  $data['agentPhone'] = $row['PhoneNumber'];
                  $agentInvolved[0] = $data;
              } else if ($row['ImmediateUpline_ID'] == $chosenAgentId) {
                  $data['agentID'] = $row['Agent_ID'];
                  $data['agentName'] = $row['Name'];
                  $data['uplineID'] = $row['ImmediateUpline_ID'];
                  $data['agentStatus'] = $row['Status'];
                  $data['agentPhone'] = $row['PhoneNumber'];
                  $agentInvolved[$count] = $data;
                  $count++;
              }
          }

          if ($uplineID!=null) {
              $sql = "SELECT * FROM agent where Agent_ID = $uplineID AND status = 1";
              $result = mysqli_query($db,$sql);
              $row = $result->fetch_assoc();

              $data['agentID'] = $row['Agent_ID'];
              $data['agentName'] = $row['Name'];
              $data['uplineID'] = $row['ImmediateUpline_ID'];
              $data['agentStatus'] = $row['Status'];
              $data['agentPhone'] = $row['PhoneNumber'];
              $agentInvolved[1] = $data;
          } else {
              $agentInvolved[1] = null;
          }

          $arrayLength = sizeof($agentInvolved);
          //     echo json_encode($agentInvolved);
          // $json_data = json_encode($agentInvolved);
          // file_put_contents('agents.json', $json_data);

          //     $coba = $agentInvolved[1];
          //     echo($coba['agentName']);
          //     echo(($agentInvolved[1])['uplineID']);
          ?>

          <script src="treant-js-master/vendor/raphael.js"></script>
          <script src="treant-js-master/Treant.js"></script>
          
          <!-- <script src="agents.json"></script> -->
          <script type="text/javascript">
              var abc = <?php echo $arrayLength; ?>;
              var chart_config = {
                  chart: {
                      container: "#agent-tree",

                      connectors: {
                          type: 'step'
                      },
                      node: {
                          HTMLclass: 'nodeExample1'
                      }
                  },
                  nodeStructure: {
                      text: {
                          title: "Direct Upline",
                          <?php echo 'name: "'.($agentInvolved[1])["agentName"].'"'; ?>,
                          <?php echo 'contact: "'.($agentInvolved[1])["agentPhone"].'"'; ?>,
                      },
                      // image: "examples/headshots/2.jpg",
                      link: {
                          <?php echo 'href: "agent_details.php?id='.($agentInvolved[0])["uplineID"].'"'; ?>
                      },
                      children: [
                      {
                          text:{
                              <?php echo 'name: "'.($agentInvolved[0])["agentName"].'"'; ?>,
                          },
                          // image: "examples/headshots/1.jpg",
                          stackChildren: true,
                          children: [
                          <?php 
                          for ($i = 2; $i < $arrayLength; $i++) {
                              ?>
                              {
                                  text:{
                                      title: "Downline",
                                      <?php echo 'name: "'.($agentInvolved[$i])["agentName"].'"'; ?>,
                                      <?php echo 'contact: "'.($agentInvolved[$i])["agentPhone"].'"'; ?>
                                  },
                                  // image: "examples/headshots/8.jpg",
                                  link: {
                                      <?php echo 'href: "agent_details.php?id='.($agentInvolved[$i])["agentID"].'"'; ?>
                                  }
                              },
                              <?php
                          }
                          ?>                  
                          ]
                      }
                      ]
                  }
              };
          </script>
          <!-- <script src="es-tree.js"></script> -->
          <script>
              new Treant( chart_config );
          </script>
      </p>
    </body>
</html>