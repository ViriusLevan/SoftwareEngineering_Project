<?php 
	$pagename='agendaftar'; 
	include('session.php');
?>
<html>
	<head>
		<?php include('htmlhead.php'); ?>
		<title>Agen</title>
	</head>
	<body class="mainbody"
		<?php 
			

			if(isset($_GET["editID"])){
				echo "onload='showAgentDetails()'";
			}
			else if(isset($_GET["dismissalID"])){
				echo "onload='showAgentDismissal()'";
				$minDismissalSQL = "SELECT closing.Date AS cDate
					FROM closing,`agent_involved_in_closing` 
					WHERE agent_involved_in_closing.Closing_ID = closing.closing_ID
					AND agent_involved_in_closing.Agent_ID = 1
					ORDER BY closing.Date DESC LIMIT 1";
				$minDismissalResult = mysqli_query($db, $minDismissalSQL);
          		$minDismissalRow = $minDismissalResult->fetch_assoc();
          		$minDismissal = new Datetime($minDismissalRow["cDate"]);
			    $minDismissal->modify('+1 day');
			    $minDismissalDate = $minDismissal->format('Y-m-d');

			}
			else if ($_SERVER["REQUEST_METHOD"] == "POST"){
				if(isset($_POST["editAID"]) && isset($_POST["editName"]) 
			//POST for agent update/agent transfer
					&& isset($_POST["editPhone"]) && isset($_POST["editBID"])){
					//check if edit Variables are set, redundant
					//SQL to get data of agent being edited
					$AgentVerifSQL = "SELECT agent.Agent_ID, agent.Name AS aName, 
						agent.PhoneNumber AS aPhone,branch.branch_id AS bID
					 	FROM `agent`,agent_branch_employment,branch 
						WHERE branch.branch_id = agent_branch_employment.Branch_ID
						AND agent_branch_employment.Agent_ID = agent.Agent_ID
						AND agent_branch_employment.End IS NULL
						AND agent.Agent_ID = " . $_POST["editAID"];
					$AgentVerifResult = mysqli_query($db, $AgentVerifSQL);
          			$AgentVerifRow = $AgentVerifResult->fetch_assoc();

          			if($_POST["editName"] != $AgentVerifRow["aName"]
          				|| $_POST["editPhone"] != $AgentVerifRow["aPhone"]){
          				//Update agent entry if name or phone data is verified to be different
          				$stmt = $db->prepare("UPDATE `agent` 
          					SET `Name`= ?,`PhoneNumber`=? 
          					WHERE agent.Agent_ID = ?");
						$stmt->bind_param('ssi', $field1, $field2, $field3);
						$field1 = $_POST["editName"];
						$field2 = $_POST["editPhone"];
						$field3 = $_POST["editAID"];
						if ($stmt->execute()) {
							$stmt->close();
						    // echo "Agen berhasil dibuat <br>";
						}else{
							$stmt->close();
			    			echo "Error: <br>" . mysqli_error($db);
						}
          			}

          			if($_POST["editBID"] != $AgentVerifRow["bID"] 
					 && isset($_POST["editTDate"])){//check if branch is different
						$bUpdateSQL = $db->prepare("UPDATE `agent_branch_employment` 
											SET `End`= ?  
											WHERE Agent_ID = ?
											AND Branch_ID = ?
											AND End IS NULL");
						$bUpdateSQL->bind_param('sii',$fu1,$fu2,$fu3);
						$transferDate = $_POST["editTDate"];
						$transferDate = str_replace("-","", $transferDate);
						$fu1 = $transferDate;
						$fu2 = $_POST["editAID"];
						$fu3 = $AgentVerifRow["bID"];

						if ($bUpdateSQL->execute()) {
							$bUpdateSQL->close();
	    					// echo "Catatan pekerjaan berhasil diperbarui";
						    $newDate = new Datetime($_POST["editTDate"]);
						    $newDate->modify('+1 day');
						    $newDateStr = $newDate->format('Ymd');
						    var_dump($newDateStr);
							$newEmployment = $db->prepare("INSERT INTO `agent_branch_employment`
								(`Agent_ID`, `Branch_ID`, `Started`, `End`) 
								VALUES (?,?,?,NULL)");
							$newEmployment->bind_param('iis',$f1,$f2,$f3);
							$f1 = $_POST["editAID"];
							$f2 = $_POST["editBID"];
							$f3 = $newDateStr;
							if ($newEmployment->execute()) {
								$newEmployment->close();
							    // echo "Pekerjaan berhasil ditambah <br>";
							}else{
								$newEmployment->close();
				    			echo "Error: <br>" . mysqli_error($db);
							}
						} else {
							$bUpdateSQL->close();
	    					echo "Error updating record: " . mysqli_error($db);
						}

					}

				}else if(isset($_POST["addName"])){
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
				}else if(isset($_POST["dismissalDate"]) && isset($_POST["dismissalID"])
							&& isset($_POST["dismissalBranch"])){
					//POST for dismissal
					$dismissalDate = $_POST["dismissalDate"];
					$dismissalDate = str_replace("-","", $dismissalDate);
					$dismissalSQL = $db->prepare("UPDATE `agent` 
						SET `Status`=0 WHERE Agent_ID = ?");
					$dismissalSQL->bind_param('i',$f1);
					$f1 = $_POST["dismissalID"];
					
					if($dismissalSQL->execute()){
						$dismissalSQL->close();
						echo "Agent berhasil dipecat";
						$employmentSQL = $db->prepare("UPDATE `agent_branch_employment` 
											SET `End`= ?  
											WHERE Agent_ID = ?
											AND Branch_ID = ?
											AND End IS NULL");
						$employmentSQL->bind_param('sii',$e1, $e2, $e3);
						$e1 = $dismissalDate;
						$e2 = $_POST["dismissalID"];
						$e3 = $_POST["dismissalBranch"];

						if($employmentSQL->execute()){
							$employmentSQL->close();
							echo "Pekerjaan berhasil diakhiri";
						}else{
							$employmentSQL->close();
							echo "Error: <br>" . mysqli_error($db);
						}

					}else{
						$dismissalSQL->close();
						echo "Error: <br>" . mysqli_error($db);
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
				<button class="btn kantormaintambahbtn" onclick="document.getElementById('tambah').style.display='block'">TAMBAH</button>
				<a href="agent_productivity.php" class="btn kantormainprodukbtn">PRODUKTIVITAS AGEN</a>
			</div>
			<br><br>
			<div class="kantormaintabel">				
				<div class="kantormaintabelheader"><h4>Daftar Agen</h4></div>
				<?php
			        $sql = "SELECT agent.Agent_ID, agent.Name, agent.PhoneNumber,agent.ImmediateUpline_ID,
			        			branch.status AS bStatus, branch.name as bName
			        			FROM `agent`,agent_branch_employment,branch
								WHERE agent.Agent_ID = agent_branch_employment.Agent_ID
								AND agent_branch_employment.Branch_ID = branch.branch_id
								AND agent.status = 1
								AND agent.Agent_ID != 0
								AND agent_branch_employment.End IS NULL";
				    $result = mysqli_query($db,$sql);
				    if ($result->num_rows > 0) {
				    	echo '<table class="table sortable">';
				    	echo "<tr> <th>ID</th> <th>Nama Agen</th> <th>Nama Cabang</th> <th>No. Telepon</th> <th>Upline</th> <th>Opsi</th> </tr>";
					    while($row = $result->fetch_assoc()) {//Output data
					    	if($row["bStatus"] == 0)
					    		echo"<tr class = 'Unstationed'>";
					    	else
					    		echo "<tr>";
					        echo "<td> " . $row["Agent_ID"]. " </td>"; 
		              		echo "<td> " . $row["Name"]. " </td>"; 
					        echo "<td> " . $row["bName"]. " </td>";
					        echo "<td> " . $row["PhoneNumber"]. " </td>";
					        if($row["ImmediateUpline_ID"] == null){
					        	echo "<td>  -Tidak Ada- </td>"; 
					        }else{
					        	$IUSQL =//Getting name of Immediate Upline
							    	"SELECT Name FROM agent WHERE Agent_ID=" . $row["ImmediateUpline_ID"];
			    				$IUResult = mysqli_query($db, $IUSQL);
			    				$IURow = $IUResult->fetch_assoc();
			    				$IU = $IURow["Name"];
					        	echo "<td> " . $IU . " </td>"; 
					    	}?>
					    	<td>
					    		<a class="btn agendetailbtn" href='agent_details.php?id=<?php echo $row["Agent_ID"]; ?>'>DETAIL</a>           
						    	<a href='agenmain.php?editID=<?php echo $row["Agent_ID"]; ?>' class="btn kantordaftarubah">UBAH</a>
					    	</td></tr>
					    	<?php
					    }
					    echo"</table>";
					} else {
				    	echo "0 results";
					}

					//if an agent is clicked, go to agent details with agent id
        ?>
        	<script type="text/javascript">
        		function showAgentDetails(){
        			document.getElementById('agendetail').style.display='block';
        		}
        	</script>
			</div>
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
										<h5 class="kantormainformlabel">Cabang</h5>
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
			<div id="agendetail" class="w3-modal" data-backdrop="">
				<div class="w3-modal-content w3-animate-top w3-card-4">
					<header class="w3-container modalheader">
						<span onclick="document.getElementById('agendetail').style.display='none'"
						class="w3-button w3-display-topright">&times;</span>
						<h2>UBAH AGEN</h2>
					</header>
					<div class="w3-container">
					<form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>">
									<?php
										if(isset($_GET["editID"])){
											$row = $_GET["editID"];
											
											$AgentSQL =
								                "SELECT agent.Agent_ID, agent.Name, agent.PhoneNumber, 
								                  agent.ImmediateUpline_ID, agent.Status,
								                  branch.branch_id as bID,branch.Name as bName, 
								                  agent_branch_employment.Started
								                  FROM agent,branch,agent_branch_employment 
								                  WHERE agent.Agent_ID = agent_branch_employment.Agent_ID
								                  AND agent_branch_employment.Branch_ID = branch.branch_id
								                  AND agent_branch_employment.End IS NULL
								                  AND agent.Agent_ID = " . $row;
									        $AgentResult = mysqli_query($db, $AgentSQL);
									        $AgentRow = $AgentResult->fetch_assoc();
									        echo "<input type='hidden' name='editAID' 
									        	value=". $AgentRow["Agent_ID"] .">";
									        echo '<h5 class="kantormainformlabel">Nama</h5>';
									        echo "<input type='text' class='form-control' name='editName'
									         value=". $AgentRow["Name"] ." required>";
									         echo '<h5 class="kantormainformlabel">No. Telepon</h5>';
									        echo "<input  type='tel' class='form-control' name='editPhone' 
									         pattern='[0-9]+' value=". $AgentRow["PhoneNumber"] ." required>";
									        $branchSQL = "SELECT branch_id,Name FROM `branch` where status=1";
											$branchResult = mysqli_query($db, $branchSQL);
											echo '<h5 class="kantormainformlabel">Cabang</h5>';
									    	echo "<select name='editBID' class='form-control kantormainselectvpv'>";
										    while($row = $branchResult->fetch_assoc()) {
										    	if($row["branch_id"] == $AgentRow["bID"])
										    		echo "<option selected='selected' 
										    			value=".$row["branch_id"]."> ". $row["Name"] ." </option>";
										    	else
										        	echo "<option value=".$row["branch_id"]."> ". $row["Name"] ." </option>"; 
										    }
										    echo "</select>";
										    $dateMin = new Datetime($AgentRow["Started"]);
										    $dateMinStr = $dateMin->format('Y-m-d');
										    echo '<h5 class="kantormainformlabel">Tgl. Pindah</h5>';
										    echo "<input type='date' class='form-control min=". $dateMinStr ." 
										    	name='editTDate' value=". $dateMinStr ."
										    	pattern='[0-9]{4}-[0-9]{2}-[0-9]{2}'>";
										}
									?>								
					</div>
					<div class="agenkembalihapusubah">
						<button type="submit" class="btn agenkembali" 
							onclick="document.getElementById('agendetail').style.display='none'">
							KEMBALI</button>
						<a href="agenmain.php?dismissalID=<?php echo $AgentRow["Agent_ID"];?>
							&dismissalName=<?php echo $AgentRow["Name"];?>
							&dismissalBranch=<?php echo $AgentRow["bID"];?>" 
							class="btn agenhapus">PECAT</a>
						<button type="submit" class="btn agenubah">SIMPAN</button>
					</div>
					</form>
				</div>
			</div>
			<div id="hapus" class="w3-modal" data-backdrop="">
					<div class="w3-modal-content w3-animate-top w3-card-4">
						<header class="w3-container modalheader">
							<h2>PECAT AGEN <?php echo $_GET["dismissalName"];?></h2>
						</header>
						<form method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>">
							<div class="w3-container">
								<input type='hidden' name='dismissalID' 
									value=<?php echo $_GET["dismissalID"];?> >
								<input type='hidden' name='dismissalBranch' 
									value=<?php echo $_GET["dismissalBranch"];?> >								
								<h5 class="kantormainformlabel">Tanggal Hapus</h5>
								<input type="date" name="dismissalDate" class="form-control" 
									min=<?php echo $minDismissalDate;?>
									value=<?php echo $minDismissalDate;?>
									pattern='[0-9]{4}-[0-9]{2}-[0-9]{2}'>						
								<div class="modalfooter">
									<button type="button" class="btn modalleftbtn" 
										onclick="document.getElementById('hapus').style.display='none'">BATAL</button>
									<button type="submit" class="btn kantormodalhapus">PECAT</button>
								</div>
								<script type="text/javascript">
									function showAgentDismissal(){
										document.getElementById('hapus').style.display='block';
									}
								</script>
							</div>
						</form>
					</div>
				</div>
				</div>
		</div>
	</body>

</html>

