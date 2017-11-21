<?php
  $pagename='agendetail';
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

          $a = new Agent($AgentRow["aID"], $AgentRow["bID"], $AgentRow["bName"], 
            $AgentRow["aName"], $AgentRow["phone"], $AgentRow["iupID"]);
          
          $a->getUplineData($db);
          $a->printDetails();
          $a->getEarningTotal($db);

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
<<<<<<< HEAD
                echo '<td class="tabelkiri">' ."Komisi(Rp): " .'</td>';  
                echo '<td>' .$closingRow["earning"] .'</td>';   
=======
                echo '<td class="tabelkiri">' ."Komisi: " .'</td>';  
                echo '<td>Rp ' .number_format($closingRow["earning"]) .'</td>';   
>>>>>>> 003e87d359971b7c0cdcf3fc00d3fe8aa3fead8a
                echo '</tr>';
                
                echo '<tr>';    
                echo '<td class="tabelkiri">' ."Sebagai: " .'</td>';  
                echo '<td>' .$workedAs .'</td>';   
                echo '</tr>';
                
                echo '</table>';
                echo "<br>";
              }              
            } else {
              echo "0 results";
            }
              
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
              ?>

              <script src="treant-js-master/vendor/raphael.js"></script>
              <script src="treant-js-master/Treant.js"></script>
              
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
                              
                              <?php if (($agentInvolved[0])["uplineID"]!=null) { echo 'name: "'.($agentInvolved[1])["agentName"].'"'; } else { echo 'name: "COMPANY"'; } ?>,
                              <?php echo 'contact: "'.($agentInvolved[1])["agentPhone"].'"'; ?>,
                          },
                          link: {
                              <?php if (($agentInvolved[0])["uplineID"]!=null) { echo 'href: "agent_details.php?id='.($agentInvolved[0])["uplineID"].'"';} ?>
                          },
                          children: [
                          {
                              text:{
                                  <?php echo 'name: "'.($agentInvolved[0])["agentName"].'"'; ?>,
                              },
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
        <button onclick="printDiv('printableArea')" class="btn printbtn">CETAK</button>
      </div>
    </body>
</html>