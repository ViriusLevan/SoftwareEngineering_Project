<?php 
	$pagename='kantor'; 
	include('session.php');
?>
<html>
	<head>
		<?php include('htmlhead.php'); ?>
		<title>Login</title>
	</head>
	<body class="mainbody" 
		<?php  
			if(isset($_GET["editBID"])){
				echo "onload='showEditDetails()'";
			}else if(isset($_GET["dismissalID"])){
				//Get minimum date for dismissal
				echo "onload='showDismissalDetails()'";
				$minDismissalSQL = "SELECT DISTINCT closing.Date AS cDate
					FROM closing,`agent_involved_in_closing`, branch, agent_branch_employment
					WHERE agent_involved_in_closing.Closing_ID = closing.closing_ID
                    AND agent_branch_employment.Agent_ID = agent_involved_in_closing.Agent_ID
                    AND agent_branch_employment.Branch_ID = branch.branch_id
                    AND agent_branch_employment.End IS NULL
                    AND branch.branch_id = ". $_GET["dismissalID"] ."
                    ORDER BY closing.Date DESC LIMIT 1";
				$minDismissalResult = mysqli_query($db, $minDismissalSQL);
          		$minDismissalRow = $minDismissalResult->fetch_assoc();
          		$minDismissal = new Datetime($minDismissalRow["cDate"]);
			    $minDismissal->modify('+1 day');
			    $minDismissalDate = $minDismissal->format('Y-m-d');
			}else if ($_SERVER["REQUEST_METHOD"] == "POST"){
				if(isset($_POST["editIBID"])){
					//Branch Edit
					$bUpdateSQL = $db->prepare("UPDATE `branch` 
						SET `President_ID`= ?,
						`VicePresident_ID`= ? ,`Name`= ? ,`address`= ? 
						WHERE branch_id = ? ");
					$bUpdateSQL->bind_param('iissi',$f1,$f2,$f3,$f4,$f5);

					if($_POST["editIPID"]=="empty")
						$PID = NULL;
					else 
						$PID = $_POST["editIPID"];
					if($_POST["editIVPID"]=="empty")
						$VPID = NULL;
					else 
						$VPID = $_POST["editIVPID"];

					$f1 = $PID;
					$f2 = $VPID;
					$f3 = $_POST["editIBName"];
					$f4 = $_POST["editIBAddress"];
					$f5 = $_POST["editIBID"];
					if($bUpdateSQL->execute()){
						$bUpdateSQL->close();
						echo "Branch Info updated successfully";
					}else{
						$bUpdateSQL->close();
						echo "Error: <br>" . mysqli_error($db);
					}
				}
				else if(isset($_POST["addBName"])){
					//Branch Add
					$bAddSQL = $db->prepare("INSERT INTO `branch`
						(`President_ID`, `VicePresident_ID`, `status`, `Name`, `address`) 
						VALUES (?,?,1,?,?)");
					$bAddSQL->bind_param('iiss',$f1,$f2,$f3,$f4);
					if($_POST["addPID"]=="empty")
						$PID = NULL;
					else 
						$PID = $_POST["addPID"];
					if($_POST["addVPID"]=="empty")
						$VPID = NULL;
					else 
						$VPID = $_POST["addVPID"];
					$f1 = $PID;
					$f2 = $VPID;
					$f3 = $_POST["addBName"];
					$f4 = $_POST["addBAddress"];

					if($bAddSQL->execute()){
						$bAddSQL->close();
						echo "Branch Added successfully";
					}else{
						$bAddSQL->close();
						echo "Error: <br>" . mysqli_error($db);
					}
				}else if (isset($_POST["dismissalID"])) {
					//Branch Dismissal
					$dismissalDate = $_POST["dismissalDate"];
					$dismissalDate = str_replace("-","", $dismissalDate);
					$dismissalSQL = $db->prepare("UPDATE `branch` 
						SET `President_ID`= NULL, `VicePresident_ID`= NULL ,`status`=0 
						WHERE branch_id = ?");
					$dismissalSQL->bind_param('i',$f1);
					$f1 = $_POST["dismissalID"];
					
					if($dismissalSQL->execute()){
						$dismissalSQL->close();
						echo "Agent Dismissed Successfully";
						$employmentSQL = $db->prepare("UPDATE `agent_branch_employment` 
											SET `End`= ?  
											WHERE Branch_ID = ?
											AND End IS NULL");
						$employmentSQL->bind_param('si',$e1, $e2);
						$e1 = $dismissalDate;
						$e2 = $_POST["dismissalID"];

						if($employmentSQL->execute()){
							$employmentSQL->close();
							echo "Employment Successfully Ended";
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

		?>
	>
		<?php include('sidebar.php'); ?>
		<div class="content">
			<?php include('header.php'); ?>
			<div class="maincontent">
				<div class="kantormainbtn">
					<button onclick="document.getElementById('tambah').style.display='block'" class="btn kantormaintambahbtn" data-toggle="modal" data-target="#exampleModal">TAMBAH</button>
					<a href="kantormain.php" class="btn kantormainprodukbtn">PRODUKTIVITAS KANTOR</a>
				</div>
				<br><br>
				<div class="kantormaintabel">
					<div class="kantormaintabelheader"><h4>Daftar Kantor</h4></div>
					<table class="table">
						<tr>
							<th>Kantor</th>
							<th>Alamat</th>
							<th>President</th>
							<th>Vice President</th>
							<th>Opsi</th>
						</tr>
						<?php 
						$sql = "SELECT  branch.branch_id AS bID, branch.Name, branch.address, 
									branch.President_ID AS pID, branch.VicePresident_ID AS vpID,
									IFNULL(pGet.President, 'Noone') AS President, 
									IFNULL(vpGet.VP, 'Noone') AS VicePresident
									FROM branch LEFT JOIN
                                    (SELECT agent.Name as President, branch.branch_id FROM agent, 
                                     branch WHERE agent.Agent_ID = branch.President_ID)pGet  
                                    ON pGet.branch_id = branch.branch_id
                                    LEFT JOIN
                                    (SELECT agent.Name as VP, branch.branch_id FROM agent, 
                                     branch WHERE agent.Agent_ID = branch.VicePresident_ID)vpGet  
                                    ON vpGet.branch_id = branch.branch_id
                                    where branch.status = 1";
					   $result = mysqli_query($db,$sql);
					   if ($result->num_rows > 0) {
					    // output data of each row
						    while($row = $result->fetch_assoc()) {
						        echo "<tr> <td>". $row["Name"] . "</td>";
						        echo "<td>". $row["address"] ."</td>"; 
					        	echo "<td> " . $row["President"]. "</td>"; 
					        	echo "<td> " . $row["VicePresident"]. "</td>";
						    	?><td>
						    	<a href="kantordaftar.php?editBID=<?php echo $row["bID"];?>
									&editPID=<?php echo $row["pID"];?>
									&editVPID=<?php echo $row["vpID"];?>
									&editPNAME=<?php echo $row["President"];?>
									&editVPNAME=<?php echo $row["VicePresident"];?>
									&editBNAME=<?php echo $row["Name"];?>
									&editBADD=<?php echo $row["address"];?>"
								class="btn kantordaftarubah">UBAH</a>
								<a href="kantordaftar.php?dismissalID=<?php echo $row["bID"];?>
									&dismissalName=<?php echo $row["Name"];?>"
								class="btn kantordaftarhapus">HAPUS</a>
						    	<?php 
						    }
						} else {
					    	echo "0 results";
						}

					?>
					</table>
				</div>
				<script type="text/javascript">
					function showEditDetails(){
						document.getElementById('edit').style.display='block'
					}
				</script>
				<div id="tambah" class="w3-modal" data-backdrop="">
					<div class="w3-modal-content w3-animate-top w3-card-4">
						<header class="w3-container modalheader">
							<span onclick="document.getElementById('tambah').style.display='none'"
							class="w3-button w3-display-topright">&times;</span>
							<h2>TAMBAH KANTOR BARU</h2>
						</header>
						<div class="w3-container">
							<form  method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>">
								<h5 class="kantormainformlabel">Nama Kantor</h5>
								<input class="form-control" type="text" placeholder="Masukkan nama kantor"
									name="addBName">
								<h5 class="kantormainformlabel">Alamat Kantor</h5>
								<input class="form-control" type="text" placeholder="Masukkan alamat kantor"
									name="addBAddress">
								<br>
								<div class="row">
									<div class="col">
										<h5 class="kantormainformlabel">Principal</h5>
										<?php
											$sql = "SELECT DISTINCT agent.Name AS aName, agent.Agent_ID as aID
														FROM agent, agent_branch_employment, branch
														WHERE agent.Status = 1
														AND agent_branch_employment.End IS NULL
														AND agent_branch_employment.Agent_ID = agent.Agent_ID
														AND agent_branch_employment.Branch_ID = branch.branch_id
														AND agent.Agent_ID !=0
														AND agent.Agent_ID NOT IN((SELECT agent.Agent_ID as ID
															FROM agent, branch 
															WHERE(agent.Agent_ID = branch.President_ID OR agent.Agent_ID = branch.VicePresident_ID)
														))";
										    $result = mysqli_query($db, $sql);
										    if ($result->num_rows > 0) {
										    	echo "<select name='addPID' class='form-control kantormainselectvpv'
										    		onchange='optionDisabling()'>";
											    echo "<option value='empty' selected='selected'> Noone </option>";
											    while($row = $result->fetch_assoc()) {
											        echo "<option value=".$row["aID"]."> ". $row["aName"] ." </option>"; 
											    }
											    echo "</select> <br>";
											}     
											else {
										    	echo "No agents available for assignment <br>";
											}
										?>
									</div>
									<div class="col">
										<h5 class="kantormainformlabel">Vice Principal</h5>
										<?php
											$result = mysqli_query($db, $sql);
										    if ($result->num_rows > 0) {
										    	echo "<select name='addVPID' class='form-control kantormainselectvpv'
										    		onchange='optionDisabling()'>";
											    echo "<option value='empty' selected='selected'> Noone </option>";
											    while($row = $result->fetch_assoc()) {
											        echo "<option value=".$row["aID"]."> ". $row["aName"] ." </option>"; 
											    }
											    echo "</select> <br>";
											}     
											else {
										    	echo "No agents available for assignment <br>";
											}
										?>
									</div>
								</div>
								<br>
								<div class="modalfooter">
									<button type="submit" class="btn modalleftbtn" onclick="document.getElementById('tambah').style.display='none'">BATAL</button>
									<button type="submit" class="btn modalrightbtn">SIMPAN</button>
								</div>
							</form>
						</div>
					</div>
				</div>
				<div id="edit" class="w3-modal" data-backdrop="">
					<div class="w3-modal-content w3-animate-top w3-card-4">
						<header class="w3-container modalheader">
							<span onclick="document.getElementById('edit').style.display='none'"
							class="w3-button w3-display-topright">&times;</span>
							<h2>EDIT KANTOR</h2>
						</header>
						<div class="w3-container">
							<form method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>">
								<input type='hidden' name='editIBID' value="<?php echo $_GET["editBID"];?>">
								<h5 class="kantormainformlabel">Nama Kantor</h5>
								<input class="form-control" type="text" value="<?php echo $_GET["editBNAME"];?>"
									name="editIBName" required>
								<h5 class="kantormainformlabel">Alamat Kantor</h5>
								<input class="form-control" type="text" value="<?php echo $_GET["editBADD"];?>"
									name="editIBAddress" required>
								<br>
								<div class="row">
									<div class="col">
										<h5 class="kantormainformlabel">Principal</h5>
										<?php
											$sql = "SELECT DISTINCT agent.Name AS aName, agent.Agent_ID as aID
														FROM agent, agent_branch_employment, branch
														WHERE agent.Status = 1
														AND agent_branch_employment.End IS NULL
														AND agent_branch_employment.Agent_ID = agent.Agent_ID
														AND agent_branch_employment.Branch_ID = branch.branch_id
														AND agent.Agent_ID !=0
														AND agent.Agent_ID NOT IN((SELECT agent.Agent_ID as ID
															FROM agent, branch 
															WHERE(agent.Agent_ID = branch.President_ID OR agent.Agent_ID = branch.VicePresident_ID)
														))";
										    $result = mysqli_query($db, $sql);
										    if ($result->num_rows > 0) {
										    	echo "<select name='editIPID' class='form-control kantormainselectvpv'
										    		onchange='optionDisabling()'>";
											    echo "<option value='empty'> Noone </option>";
											    if($_GET["editPID"]!=NULL)
												    echo "<option value=". $_GET["editPID"] ." selected='selected'> "
												    	. $_GET["editPNAME"] ." </option>";
											    while($row = $result->fetch_assoc()) {
											        echo "<option value=".$row["aID"]."> ". $row["aName"] ." </option>"; 
											    }
											    echo "</select> <br>";
											}     
											else {
										    	echo "No agents available for assignment <br>";
											}
										?>
									</div>
									<div class="col">
										<h5 class="kantormainformlabel">Vice Principal</h5>
										<?php
											$result = mysqli_query($db, $sql);
										    if ($result->num_rows > 0) {
										    	echo "<select name='editIVPID' class='form-control kantormainselectvpv'
										    		onchange='optionDisabling()'>";
											    echo "<option value='empty'> Noone </option>";
											    if($_GET["editVPID"]!=NULL)
											    	echo "<option value=". $_GET["editVPID"] ." selected='selected'> "
											    		. $_GET["editVPNAME"] ." </option>";
											    while($row = $result->fetch_assoc()) {
											        echo "<option value=".$row["aID"]."> ". $row["aName"] ." </option>"; 
											    }
											    echo "</select> <br>";
											}     
											else {
										    	echo "No agents available for assignment <br>";
											}
										?>
									</div>
								</div>
								<br>
								<div class="modalfooter">
									<button type="button" class="btn modalleftbtn" onclick="document.getElementById('edit').style.display='none'">BATAL</button>
									<button type="submit" class="btn modalrightbtn">SIMPAN</button>
								</div>
							</form>
						</div>
						<script type="text/javascript">
							function optionDisabling(){//HELP ME
								var select = document.getElementsByClassName("form-control kantormainselectvpv");
								var selections = [];

								for (var i = 0; i <4; i++) {
									if(select[i].disabled == false && 
										select[i].options[select[i].selectedIndex].value != "Noone"){
										selections.push(select[i].options[select[i].selectedIndex].value);
									}
								}

								for (var i = 0; i<4; i++) {
									var opt = select[i].getElementsByTagName("option");
									for (var j = 0; j < opt.length; j++) {
										if(selections.indexOf(opt[j].value) == -1){
										//not found on selections
											opt[j].disabled = false;
										}else if(opt[j].value == select[i].options[select[i].selectedIndex].value){
										//the currently selected option
											opt[j].disabled = false; 
										}else{//Found on selection and not the currently selected option
											opt[j].disabled = true;
										}
									}
								}
							}
						</script>
					</div>
				</div>
				<div id="hapus" class="w3-modal" data-backdrop="">
					<div class="w3-modal-content w3-animate-top w3-card-4">
						<header class="w3-container">
							<h2>Branch <?php echo $_GET["dismissalName"];?> DISMISSAL</h2>
						</header>
						<form method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>">
							<div class="w3-container">
								<input type='hidden' name='dismissalID' 
									value=<?php echo $_GET["dismissalID"];?> >
								Dismissal Date<input type="date" name="dismissalDate" 
									min=<?php echo $minDismissalDate;?>
									value=<?php echo $minDismissalDate;?>
									pattern='[0-9]{4}-[0-9]{2}-[0-9]{2}'>						
								<div class="modalfooter">
									<button type="button" class="btn modalleftbtn" 
										onclick="document.getElementById('hapus').style.display='none'">TIDAK</button>
									<button type="submit" class="btn modalrightbtn">IYA</button>
								</div>
								<script type="text/javascript">
									function showDismissalDetails(){
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