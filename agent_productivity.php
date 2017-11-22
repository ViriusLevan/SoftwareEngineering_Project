<?php
	$pagename='agenproduk';
	include('session.php');
?>
<html>
	<head>
		<?php include('htmlhead.php'); ?>
		<title>Agen</title>
	</head>
	<body class="mainbody"
		<?php
			if ($_SERVER["REQUEST_METHOD"] == "POST"){
				if(isset($_POST["addName"])){
				//POST for new Agent
					$name = $phone = $UplineID = $BranchID = "";
					$password = $passwordCon = "";
					$name = test_input($_POST["addName"]);
					$phone = $_POST["phone"];
					$UplineID = test_input($_POST["UplineID"]);
					$BranchID = test_input($_POST["BranchID"]);
				  	if ($UplineID == "empty") {$UplineID = null;}

				  	if (!$db) {
				    	die("Connection failed: " . mysqli_connect_error());
					}else{
							$stmt = $db->prepare("
								INSERT INTO agent (Name, ImmediateUpline_ID, Status, PhoneNumber) 
								VALUES (?,?,1,?)");
							$stmt->bind_param('sis', $field2, $field3, $field4);

							$field2 = $name;
							$field3 = $UplineID;
							$field4 = $phone;

						if ($stmt->execute()) {
							$stmt->close();
						    // echo "Agen berhasil dibuat <br>";
							
							$agentID =  mysqli_insert_id($db);//get last inserted AUTO_INCREMENT (which is agent ID)
							//Branch Employment Insertion
							$employmentSTMT = $db->prepare("
						    		INSERT INTO `agent_branch_employment`(`Agent_ID`, `Branch_ID`, `Started`, `End`) 
						    		VALUES (?,?,?,NULL)");
							$employmentSTMT->bind_param('iis', $f1, $f2, $f3);
							$f1 = $agentID;
							$f2 = $BranchID;
							$f3 = date("Y-m-d");
							if($employmentSTMT->execute()){
					    		$employmentSTMT->close();
					    		// echo "Pekerjaan berhasil ditambah <br>";
					    	}else{
					    		$employmentSTMT->close();
					    		echo "Error: <br>" . mysqli_error($db);
					    	}

							//Recursive Insertion for downline relation
						    while($UplineID != NULL){
						    	$upSTMT = $db->prepare("
						    		INSERT INTO `agent_has_downline`(`Agent_ID`, `Downline_ID`) 
						    		VALUES (?,?)");
						    	$upSTMT->bind_param('ii' ,$Up, $Down);
						    	$Up = $UplineID;
						    	$Down = $agentID;

						    	
						    	if($upSTMT->execute()){
						    		$upSTMT->close();
						    		// echo "Downline berhasil dibuat <br>";
						    	}else{
						    		$upSTMT->close();
						    		echo "Error: <br>" . mysqli_error($db);
						    	}

						    	$cAgentSQL = 
									    "SELECT agent.ImmediateUpline_ID FROM agent 
											WHERE agent.Agent_ID = " . $UplineID;
				    			$cAgentResult = mysqli_query($db, $cAgentSQL);
				    			$cAgentRow = $cAgentResult->fetch_assoc();
				    			$UplineID = $cAgentRow["ImmediateUpline_ID"];
						    }

						} else {
							$stmt->close();
						    echo "Error: <br>" . mysqli_error($db);
						}
					}
				} 
			}
			function test_input($data) {
			  $data = trim($data);
			  $data = stripslashes($data);
			  $data = htmlspecialchars($data);
			  return $data;
			}
		?>
	>
	
		<?php include('sidebar.php'); ?>
		<div class="content">
			<?php include('header.php'); ?>
			<div class="maincontent">
				<div class="kantormainbtn">
					<button onclick="document.getElementById('tambah').style.display='block'" class="btn kantormaintambahbtn" data-toggle="modal" data-target="#exampleModal">TAMBAH</button>
					<a href="agenmain.php" class="btn kantormainprodukbtn">DAFTAR AGEN</a>
					<button onclick="printDiv('printableArea')" class="btn printbtn">CETAK</button>
				</div>
				<br>
				<div class="kantormainfilter">
					<h2>Filter</h2>
					<form action=<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?> method="post">
						<h5 class="kantormainformlabel">Tanggal&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:</h5>
						<input type="date" name="bfrDate"
							id="startDate" value="<?php echo date('Y-m-d');?>" class="form-control kantormainselect">
						<h5 class="kantormainformlabel">s/d</h5>
						<input type="date" name="aftDate"
							id="endDate" value="<?php echo date('Y-m-d');?>" class="form-control kantormainselect">
						<input type="submit" name="submit" class="btn kantormainfiltersubmit">
					</form>
				</div>
				<br>
				<div class="kantormaintabel" id="printableArea">
					<div class="kantormaintabelheader"><h4>Hasil Produktivitas Agen</h4></div>
					<table class="table sortable" id="produktable">
						<tr>
							<th>Nama Agen</th>
							<th>Unit</th>
							<th>Total Closing</th>
							<th>Pendapatan agen dari Closing</th>
						</tr>
					
	<?php 
		$AgentProSQL = 
			"SELECT agent.Name AS Name, IFNULL(UNIT, 0) AS Unit, 
				IFNULL(Productivity,0) AS Pro, IFNULL(Earnings,0) AS Earn 
				FROM agent LEFT OUTER JOIN
					(SELECT agent.Name, agent.Agent_ID,
						SUM(CASE 
			             WHEN aCount.nAgents = 1 THEN 1
			             WHEN aCount.nAgents = 2 THEN 0.5
			             WHEN agent_involved_in_closing.workedAs = 1
			             	&& aCount.nAgents = 3 THEN 0.5
			             WHEN agent_involved_in_closing.workedAs IN (7,13)
			             	&& aCount.nAgents = 3 THEN 0.25
			             WHEN aCount.nAgents = 4 THEN 0.25
			            END) AS Unit,
						COUNT(DISTINCT agent_involved_in_closing.Closing_ID) AS Productivity,
						SUM(agent_involved_in_closing.earning) AS Earnings
						FROM agent_involved_in_closing, branch, agent, Agent_Branch_Employment,
			                    (
			                    	SELECT closing.closing_ID AS cID, 
			                        	COUNT(agent_involved_in_closing.Agent_ID) AS nAgents
			                        FROM agent_involved_in_closing, closing
			                        WHERE agent_involved_in_closing.Closing_ID = closing.closing_ID
			                            AND agent_involved_in_closing.workedAs IN (1,7,13,19)
			                        GROUP BY closing.closing_ID
			                    )aCount
						WHERE agent_involved_in_closing.workedAs IN (1,7,13,19)
							AND agent_involved_in_closing.Agent_ID = agent.Agent_ID
							AND agent.Agent_ID = Agent_Branch_Employment.Agent_ID
							AND Agent_Branch_Employment.Branch_ID = branch.Branch_ID
							AND Agent.Agent_ID != 0
							AND aCount.cID = agent_involved_in_closing.Closing_ID
							GROUP BY agent.Agent_ID) pro
				ON pro.Agent_ID = agent.Agent_ID
				WHERE agent.Agent_ID !=0";
		if(isset($_POST["bfrDate"]) && isset($_POST["aftDate"])) {
			if($_POST["bfrDate"]!= NULL && $_POST["aftDate"]!= NULL){
				$bfrDate = $_POST["bfrDate"];
				$aftDate = $_POST["aftDate"];
				$bfrDate =  str_replace("-","", $bfrDate); //remove "-" from date
				$aftDate =  str_replace("-","", $aftDate);
				$AgentProSQL = 
					"SELECT agent.Name AS Name, IFNULL(UNIT, 0) AS Unit, 
						IFNULL(Productivity,0) AS Pro, IFNULL(Earnings,0) AS Earn 
						FROM agent LEFT OUTER JOIN
							(SELECT agent.Name, agent.Agent_ID,
								SUM(CASE 
					             WHEN aCount.nAgents = 1 THEN 1
					             WHEN aCount.nAgents = 2 THEN 0.5
					             WHEN agent_involved_in_closing.workedAs = 1
					             	&& aCount.nAgents = 3 THEN 0.5
					             WHEN agent_involved_in_closing.workedAs IN (7,13)
					             	&& aCount.nAgents = 3 THEN 0.25
					             WHEN aCount.nAgents = 4 THEN 0.25
					            END) AS Unit,
								COUNT(DISTINCT agent_involved_in_closing.Closing_ID) AS Productivity,
								SUM(agent_involved_in_closing.earning) AS Earnings
								FROM agent_involved_in_closing, branch, agent, Agent_Branch_Employment, closing,
					                    (
					                    	SELECT closing.closing_ID AS cID, 
					                        	COUNT(agent_involved_in_closing.Agent_ID) AS nAgents
					                        FROM agent_involved_in_closing, closing
					                        WHERE agent_involved_in_closing.Closing_ID = closing.closing_ID
					                            AND agent_involved_in_closing.workedAs IN (1,7,13,19)
					                        GROUP BY closing.closing_ID
					                    )aCount
								WHERE agent_involved_in_closing.workedAs IN (1,7,13,19)
									AND agent_involved_in_closing.Agent_ID = agent.Agent_ID
									AND agent.Agent_ID = Agent_Branch_Employment.Agent_ID
									AND Agent_Branch_Employment.Branch_ID = branch.Branch_ID
									AND Agent.Agent_ID != 0
									AND aCount.cID = agent_involved_in_closing.Closing_ID
									AND closing.closing_ID = agent_involved_in_closing.Closing_ID
									AND DATEDIFF(closing.Date,$bfrDate)>=0
									AND DATEDIFF(closing.Date,$aftDate)<=0
									GROUP BY agent.Agent_ID) pro
						ON pro.Agent_ID = agent.Agent_ID
						WHERE agent.Agent_ID !=0";
			}
		}
		
		$AgentProResult = mysqli_query($db, $AgentProSQL);

		if ($AgentProResult->num_rows > 0) {
            while($AgentProRow = $AgentProResult->fetch_assoc()) { 

              // output data of each row
              echo "<tr><td> " . $AgentProRow["Name"]. " </td>"; 
              echo "<td> " . $AgentProRow["Unit"]. " </td>"; 
              echo "<td> " . $AgentProRow["Pro"] . " </td>"; 
              echo '<td class="pull-right"> ' . "Rp ".  number_format($AgentProRow["Earn"]) . " </td>";

            }
        } else {
            //SHOULD NEVER HAPPEN
            echo "No agents found";
        }
	?>
					</table>
				</div>
			</div>
			<script>
			function sortTable(n) {
			  var table, rows, switching, i,a, b, x, y, shouldSwitch, dir, switchcount = 0;
			  table = document.getElementById("produktable");
			  switching = true;
			  dir = "asc"; 
			  while (switching) {
			    switching = false;
			    rows = table.getElementsByTagName("TR");
			    for (i = 1; i < (rows.length - 1); i++) {
			      shouldSwitch = false;
			      x = rows[i].getElementsByTagName("TD")[n];
			      y = rows[i + 1].getElementsByTagName("TD")[n];
			      if (n==0) {
					a=x.innerHTML;
					b=y.innerHTML;
			      } else{
			      	a = parseInt(x.innerHTML);
			      	b = parseInt(y.innerHTML);
			      }
			      
			      if (dir == "asc") {
			        if (a> b) {
			          shouldSwitch= true;
			          break;
			        }
			      } else if (dir == "desc") {
			        if (a< b) {
			          shouldSwitch= true;
			          break;
			        }
			      }
			    }
			    if (shouldSwitch) {
			      rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
			      switching = true;
			      switchcount ++; 
			    } else {
			      if (switchcount == 0 && dir == "asc") {
			        dir = "desc";
			        switching = true;
			      }
			    }
			  }
			}
			</script>
			<div id="tambah" class="w3-modal" data-backdrop="">
					<div class="w3-modal-content w3-animate-top w3-card-4">
						<header class="w3-container modalheader">
							<span onclick="document.getElementById('tambah').style.display='none'"
							class="w3-button w3-display-topright">&times;</span>
							<h2>TAMBAH AGEN BARU</h2><!--Agent_add.php -->
						</header>
						<div class="w3-container">
							<form method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>">
								<h5 class="kantormainformlabel">Nama Agen</h5>
								<input class="form-control" type="text" placeholder="Masukkan nama agen"
									name="addName" required>
								<h5 class="kantormainformlabel">Nomor Telepon</h5>
								<input class="form-control" placeholder="Masukkan nomor telepon" 
									type="tel" name="phone" pattern="[0-9]+" required>
								<br>
								<div class="row">
									<div class="col">
										<h5 class="kantormainformlabel">Kantor</h5>
										<?php 
											$branchSQL = "SELECT branch_id,Name FROM `branch` where status=1";
											$branchResult = mysqli_query($db, $branchSQL);
											if ($branchResult->num_rows > 0) {
										    	echo "<select name='BranchID' class='form-control kantormainselectvpv'>";
											    while($row = $branchResult->fetch_assoc()) {
											        echo "<option value=".$row["branch_id"]."> ". $row["Name"] ." </option>"; 
											    }
											    echo "</select> <br>";
											}     
											else {//THIS SHOULD NOT HAPPEN
										    	echo "Tidak ada cabang<br>";
											}
										?>
									</div>
									<div class="col">
										<h5 class="kantormainformlabel">Upline</h5>
										<?php
											$agentSQL = "SELECT agent.Name, agent.Agent_ID from agent 
												where status=1 AND Agent_ID != 0";
										    $agentResult = mysqli_query($db, $agentSQL);
										    if ($agentResult->num_rows > 0) {
										    	echo "<select name='UplineID' class='form-control kantormainselectvpv'>";
											    echo "<option value='empty'> Noone </option>";
											    while($agentRow = $agentResult->fetch_assoc()) {
											        echo "<option value=".$agentRow["Agent_ID"]."> ". $agentRow["Name"] ." </option>"; 
											    }
											    echo "</select> <br>";
											}     
											else {
										    	echo "Tidak ada agen <br>";
											}
										?>
									</div>
								</div>
								<br>
								<div class="modalfooter">
									<button type="button" class="btn modalleftbtn" onclick="document.getElementById('tambah').style.display='none'">BATAL</button>
									<button type="submit" class="btn modalrightbtn">SIMPAN</button>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
		<script>
			function printDiv(divName) {
			    var printContents = document.getElementById(divName).innerHTML;
			    var originalContents = document.body.innerHTML;

			    document.body.innerHTML = printContents;

			    window.print();

			    document.body.innerHTML = originalContents;
			}
		</script>
 	</body>
</html>