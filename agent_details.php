<?php
  $pagename='agen';
  include('session.php');
?>
<html>
  <head>
    <link rel="stylesheet" href="treant-js-master/Treant.css">
    <link rel="stylesheet" href="treant-js-master/es-tree.css">
    <?php include('htmlhead.php'); ?>
    <title>Agen</title>
  </head>
  <body class="mainbody">
    <?php include('sidebar.php'); ?>
    <div class="content">
      <?php include('header.php'); ?>
      <input type="button" onclick="printDiv('printableArea')" class="btn btn-lg btn-success" value="PRINT" />
      <div class="maincontent" id="printableArea">        
          
        <div class="row">
          <div class="col">
        <?php
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
          
          $a->printDetails();
          $a->getEarningTotal($db);

          ?>
            <!-- <a class="btn btn-warning" 
              href='agent_involved_in_closing.php?id=<?php echo $row; ?>'
              >Click to see Agent's involvement in closings</a>  -->
          <?php
          // echo"Agent's Downlines <br>-----------------------------------<br>";
          // $a->getDownline($db);
          ?>
          <h3>---------------------------------------</h3>
          <h3>Riwayat</h3>
        <?php
            $pass = $_GET["id"];

            $closingSQL = "SELECT * FROM  Agent_involved_in_closing where Agent_ID = " . $pass;
            $closingResults = mysqli_query($db,$closingSQL);
            if ($closingResults->num_rows > 0) {
            // output data of each row
              
              while($closingRow = $closingResults->fetch_assoc()) {
                $workedAs = setWorkedAs($closingRow["workedAs"]);
                echo '<table class="table">'; 

                echo '<tr>';    
                echo '<td class="tabelkiri">' ."Closing ID: " .'</td>';  
                echo '<td>' .$closingRow["Closing_ID"] .'</td>';   
                echo '</tr>';

                echo '<tr>';    
                echo '<td class="tabelkiri">' ."Komisi: " .'</td>';  
                echo '<td>' .$closingRow["earning"] .'</td>';   
                echo '</tr>';
                
                echo '<tr>';    
                echo '<td class="tabelkiri">' ."Sebagai: " .'</td>';  
                echo '<td>' .$workedAs .'</td>';   
                echo '</tr>';
                
                echo '</table>';
                // echo "Closing ID: " . $closingRow["Closing_ID"]. "<br>"; 
                // echo "Earned    : " . $closingRow["earning"]. "<br>";
                // echo "Worked As : " . $workedAs. "<br>"; 
                echo "<br>";
              }              
            } else {
              echo "0 results";
            }

            //Get Closing Details but who cares about this
            /*$idSQL = "SELECT Closing_ID from Agent_involved_in_closing where Agent_ID = " . $pass;
            $idResults = mysqli_query($db, $idSQL);

            if ($idResults->num_rows > 0) {
            // output data of each row
              while($idRow = $idResults->fetch_assoc()) {
                $closingSQL = "SELECT * FROM closing where Closing_ID = ". $idRow["Closing_ID"];
                $closingResults = mysqli_query($db,$closingSQL);
                if ($closingResults->num_rows > 0) {
                // output data of each row
                  while($closingRow = $closingResults->fetch_assoc()) {
                    echo "ID: " . $closingRow["closing_ID"]. "<br>"; 
                    echo "Date: " . $closingRow["Date"]. "<br>";
                    echo "Price: " . $closingRow["Price"]. "<br>"; 
                    echo "Address: " . $closingRow["Address"]. "<br>";
                    echo "-------------<br>";
                  }
                } else {
                  echo "0 results";
                }
              }
            } else {
              echo "Agent not involved in any closing";
            }*/
              
          function setWorkedAs($code){
              $workedAs = "";
              if($code>18){
                $workedAs = "Agen 4";
              }else if($code>12){
                $workedAs = "Agen 3";
              }else if($code>6){
                $workedAs = "Agen 2";
              }else{
                $workedAs = "Agen 1";
              }

              if($code%6==0){
                $workedAs = "Upline ketiga ".$workedAs;
              }else if($code%6==1){
                //The actual agent
              }else if($code%6==2){
                $workedAs = "Presiden cabang  ".$workedAs;
              }else if($code%6==3){
                $workedAs = "Wakil presiden cabang ".$workedAs;
              }else if($code%6==4){
                $workedAs = "Upline pertama ".$workedAs;
              }else {
                $workedAs = "Upline kedua ".$workedAs;
              }

              return $workedAs;
            }
        ?>
        </div>
          <div class="col">
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
                              title: "Upline",
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

                new Treant( chart_config );
        
                function printDiv(divName) {
                    var printContents = document.getElementById(divName).innerHTML;
                    var originalContents = document.body.innerHTML;

                    document.body.innerHTML = printContents;

                    window.print();

                    document.body.innerHTML = originalContents;
                }
              </script>
          </div>
        </div>
        <a href="agenmain.php" class="btn agendetailkembalibtn">KEMBALI</a>
      </div>
    </body>
</html>