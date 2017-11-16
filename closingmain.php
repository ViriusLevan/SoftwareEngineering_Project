<?php
	$pagename='closing';
	include('session.php');
?>
<html>
	<head>
		<?php include('htmlhead.php'); ?>
		<title>Closing</title>
	</head>
	<body class="mainbody" onload="agentOptions(1)">
		<?php include('sidebar.php'); ?>
		<div class="content">
			<?php include('header.php'); ?>
			<div class="maincontent">
				<div class="kantormainbtn">
					<button onclick="document.getElementById('tambah').style.display='block'" class="btn kantormaintambahbtn" data-toggle="modal" data-target="#exampleModal">TAMBAH</button>
				</div>
				<br>
				<div class="kantormainfilter">
					<h2>Filter</h2>
					<form action="">
						<h5 class="kantormainformlabel">Tanggal&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:</h5>
						<!-- <select name="bfrbulan" class="form-control propertyselect">
															<option value="agenA">Agen A</option>
						</select> -->
						<input type="date" name="bfrDate"
						id="startDate" onchange="closingFilter()" class="form-control kantormainselect">
						<h5 class="kantormainformlabel">s/d</h5>
						<input type="date" name="aftDate"
						id="endDate" onchange="closingFilter()" class="form-control kantormainselect">
						<!-- JS Not implemented YET -->
						<br>
						<script>
							function closingFilter() {
								// window.alert("shit");
							var startDt = document.getElementById("startDate").value;
							var endDt = document.getElementById("endDate").value;
							// Declare variables
							var table, td, i;
							table = document.getElementById("closingTable");
							var numRows = table.rows.length;
							dt1 = new Date(startDt).getTime();
							dt2 = new Date(endDt).getTime();
								for (var i = 1; i < numRows; i++) {
								var cells = table.rows[i].getElementsByTagName('td');
								console.log(cells[2].innerHTML);
								dtTD = (new Date(cells[2].innerHTML)).getTime();
							if (dtTD>dt1 && dtTD<dt2) {
								table.rows[i].style.display = "";
							}else{
									table.rows[i].style.display = "none";
							}
								}
							}
						</script>
					</form>
				</div>
				<br>
				<div class="kantormaintabel">
					<div class="kantormaintabelheader"><h4>Hasil Closing</h4></div>
					<table class="table" id="closingTable">
						<tr>
							<th>Alamat</th>
							<th>Harga (Rp)</th>
							<th>Tanggal</th>
							<th>Opsi</th>
						</tr>
						<?php
							$closingSQL = "SELECT * FROM closing";
						$closingResult = mysqli_query($db,$closingSQL);
						if ($closingResult->num_rows > 0) {
						while($row = $closingResult->fetch_assoc()) {
						// output data of each row
						echo "<tr><td> " . $row["Address"]. " </td>";
						echo "<td> " . $row["Price"]. " </td>";
						echo "<td>   " . $row["Date"]. " </td>";
						?>
						<td>
							<a href='closing_agents.php?id=<?php echo $row["closing_ID"]; ?>' class="btn kantordaftarubah">DETAIL</a>
						</td>
					</tr>
					<?php
								}
								} else {
								echo "No closing found";
								}
					?>
				</table>
			</div>
			<div id="tambah" class="w3-modal" data-backdrop="">
				<div class="w3-modal-content w3-animate-top w3-card-4">
					<header class="w3-container modalheader">
						<span onclick="document.getElementById('tambah').style.display='none'"
						class="w3-button w3-display-topright">&times;</span>
						<h2>TAMBAH CLOSING BARU</h2>
					</header>
					<div class="w3-container">
						<form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>">
							<h5 class="kantormainformlabel">Alamat</h5>
							<input class="form-control" type="text" name="address" placeholder="Masukkan alamat properti" required>
							<div class="row">
								<div class="col">
									<h5 class="kantormainformlabel">Tanggal</h5>
									<input class="form-control" type="date" name="date" required>
								</div>
								<div class="col">
									<h5 class="kantormainformlabel">Harga (Rp)</h5>
									<input class="form-control" placeholder="Masukkan harga properti" type="number" name="price" min=100000.00 step="any" required>
								</div>
							</div>
							<br>
							<h5 class="kantormainformlabel">Banyaknya Agen</h5>
							<select class="form-control" name="nAgents" id="nAgents" onchange="agentOptions(this.value)">
								<option value="1">1</option>
								<option value="2">2</option>
								<option value="3">3</option>
								<option value="4">4</option>
							</select>
							<div class="row">
								<div class="col">
									<h5 class="kantormainformlabel">Agen 1 (Agen Utama)</h5>
									<?php
										$agentSQL = "SELECT agent.Name, agent.Agent_ID
														FROM agent,agent_branch_employment,branch
														WHERE agent.status=1
														AND agent.Agent_ID != 0
														AND agent.Agent_ID = agent_branch_employment.Agent_ID
														AND agent_branch_employment.Branch_ID = branch.branch_id
														AND branch.status = 1";
									$agentResult = mysqli_query($db, $agentSQL);
									if ($agentResult->num_rows > 0) {
										echo "<select name='agent1ID' id='agent1Select'
																			onchange='optionDisabling()' class='agentSelection form-control' required>";
																		while($agentRow = $agentResult->fetch_assoc()) {
																		echo "<option value=".$agentRow["Agent_ID"]."> ". $agentRow["Name"] ." </option>";
																		}
										echo "</select>";
										}
										else {
										echo "No agents to assign to <br>";
										}
									?>
								</div>
								<div class="col">
									<h5 class="kantormainformlabel">Agen 2</h5>
									<?php
									$agentResult = mysqli_query($db, $agentSQL);
									if ($agentResult->num_rows > 0) {
										echo "<select name='agent2ID' id='agent2Select'
																			onchange='optionDisabling()' class='agentSelection form-control' required>";
																		while($agentRow = $agentResult->fetch_assoc()) {
																		echo "<option value=".$agentRow["Agent_ID"]."> ". $agentRow["Name"] ." </option>";
																		}
										echo "</select>";
										}
										else {
										echo "No agents to assign to <br>";
										}
									?>
								</div>
							</div>
							<div class="row">
								<div class="col">
									<h5 class="kantormainformlabel">Agen 3</h5>
									<?php
									$agentResult = mysqli_query($db, $agentSQL);
									if ($agentResult->num_rows > 0) {
										echo "<select name='agent3ID' id='agent3Select'
																			onchange='optionDisabling()' class='agentSelection form-control' required>";
																		while($agentRow = $agentResult->fetch_assoc()) {
																		echo "<option value=".$agentRow["Agent_ID"]."> ". $agentRow["Name"] ." </option>";
																		}
										echo "</select>";
										}
										else {
										echo "No agents to assign to <br>";
										}
									?>
								</div>
								<div class="col">
									<h5 class="kantormainformlabel">Agen 4</h5>
									<?php
									$agentResult = mysqli_query($db, $agentSQL);
									if ($agentResult->num_rows > 0) {
										echo "<select name='agent4ID' id='agent4Select'
																			onchange='optionDisabling()' class='agentSelection form-control' required>";
																		while($agentRow = $agentResult->fetch_assoc()) {
																		echo "<option value=".$agentRow["Agent_ID"]."> ". $agentRow["Name"] ." </option>";
																		}
										echo "</select>";
										}
										else {
										echo "No agents to assign to <br>";
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
						<script type="text/javascript">
							function agentOptions(n){
						// Agent Select disabling
						// probably needs improvement
								if(n == 1){
									document.getElementById("agent2Select").disabled = true;
									document.getElementById("agent3Select").disabled = true;
									document.getElementById("agent4Select").disabled = true;
								}
								if(n == 2){
									document.getElementById("agent2Select").disabled = false;
									document.getElementById("agent3Select").disabled = true;
									document.getElementById("agent4Select").disabled = true;
								}if(n == 3){
									document.getElementById("agent2Select").disabled = false;
									document.getElementById("agent3Select").disabled = false;
									document.getElementById("agent4Select").disabled = true;
								}
								if(n == 4){
									document.getElementById("agent2Select").disabled = false;
									document.getElementById("agent3Select").disabled = false;
									document.getElementById("agent4Select").disabled = false;
								}
								optionDisabling();
							}
							function optionDisabling(){
								var select = document.getElementsByClassName("agentSelection");
								var selections = [];
								for (var i = 0; i < 4; i++) {
									if(select[i].disabled == false){
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
			</div>
		</div>
	</div>
	<?php
	
	$address = $date = $price = $nAgents = $agent1ID = "";
	$password = $passwordCon = "";
	if ($_SERVER["REQUEST_METHOD"] == "POST") {
		$address = test_input($_POST["address"]);
		$date = $_POST["date"];
		$price = test_input($_POST["price"]);
		$nAgents = test_input($_POST["nAgents"]);
		$agent1ID = test_input($_POST["agent1ID"]);
		if($nAgents>1)$agent2ID = test_input($_POST["agent2ID"]);
		if($nAgents>2)$agent3ID = test_input($_POST["agent3ID"]);
		if($nAgents>3)$agent4ID = test_input($_POST["agent4ID"]);
		$agents = [];
		echo "<h2>Your Input:</h2>";
		if(isset($address))echo $address. "<br>";
		if(isset($date))echo $date. "<br>";
		if(isset($price))echo $price. "<br>";
		if(isset($nAgents))echo $nAgents. "<br>";
		if(isset($agent1ID)){
			echo $agent1ID. "<br>";
			$agents[] = $agent1ID;
		}
		if(isset($agent2ID)){
			echo $agent2ID. "<br>";
			$agents[] = $agent2ID;
		}
		if(isset($agent3ID)){
			echo $agent3ID. "<br>";
			$agents[] = $agent3ID;
		}
		if(isset($agent4ID)){
			echo $agent4ID. "<br>";
			$agents[] = $agent4ID;
		}
		echo "<br>";
		if (!$db) {
		die("Connection failed: " . mysqli_connect_error());
		}
		else{
			$stmt = $db->prepare("
					INSERT INTO closing (`Date`, `Price`, `Address`)
					VALUES (?,?,?)");
			$stmt->bind_param('sis', $field1, $field2, $field3);
			$field1 = $date;
			$field2 = $price;
			$field3 = $address;
			if ($stmt->execute()) {//Closing Creation
				$stmt->close();
			echo "New closing created successfully <br>";
			//sql to get closing_id
			$lselect = "SELECT LAST_INSERT_ID()";
			$cID =  mysqli_insert_id($db);
			$p = 0;
			if($nAgents == 1){
				$p = 100;
			}else if($nAgents == 2){
				$p = 50;
			}else{//3 & 4 agents
				$p = 25;
			}
			for ($i = 0; $i < $nAgents; $i++) {//Primary involvement insertion
				$stmti = $db->prepare("
				INSERT INTO `agent_involved_in_closing`(`Agent_ID`, `Closing_ID`, `earning`, `workedAs`)
				VALUES (?,?,?,?)");
				$stmti->bind_param('iidi', $f1, $f2, $f3, $f4);
				
				$f1 = $agents[$i];
					$f2 = $cID;
				if($nAgents == 3){
					if($i == 0){
						$p == 50;
					}else{
						$p == 25;
					}
				}
					$f3 = $p*$price/100;
					$f4 = 6*$i + 1;
					if($stmti->execute()){
						$stmti->close();
					echo $i. "Primary Agent Involvement created successfully <br>";
	////////////
					//Secondary involvement insertion
					$cAgentSQL =
						"SELECT branch.President_ID,branch.VicePresident_ID, agent.ImmediateUpline_ID
								FROM branch,agent,agent_branch_employment
								WHERE branch.branch_id = agent_branch_employment.Branch_ID
									AND agent_branch_employment.Agent_ID = agent.Agent_ID
									AND agent_branch_employment.End IS NULL
									AND agent.Agent_ID = " . $agents[$i];
					$cAgentResult = mysqli_query($db, $cAgentSQL);
					$cAgentRow = $cAgentResult->fetch_assoc();
					//President & Vice President
					$PresidentID  = $cAgentRow["President_ID"];
					$VicePresidentID  = $cAgentRow["VicePresident_ID"];
					$ImmediateUplineID = $cAgentRow["ImmediateUpline_ID"];
					$cPresP = $cVPP = "";
					//If there is a president and he's not the primary agent
					if($PresidentID != null && $PresidentID != $agents[$i]){
							$cPresPSQL = //Current President Percentage
							"SELECT Percentage FROM `paypercentages`
								WHERE JobName = 'President' AND ValidityEnd IS NULL";
						$cPresPResult = mysqli_query($db, $cPresPSQL);
						$cPresPRow = $cAgentResult->fetch_assoc();
						$cPresP = $cPresPRow["Percentage"];
						secondaryInvolvementInsertion(
										$db, $PresidentID, $cID, $price, $p, $cPresP, $i, 2);
					}
					//If there is a vice president and he's not the primary agent
					if($VicePresidentID != null && $VicePresidentID != $agents[$i]){
						$cVPPSQL = //Current Vice President Percentage
							"SELECT Percentage FROM `paypercentages`
								WHERE JobName = 'Vice President' AND ValidityEnd IS NULL";
						$cVPPResult = mysqli_query($db, $cVPPSQL);
						$cVPPRow = $cAgentResult->fetch_assoc();
						$cVPP = $cPresPRow["Percentage"];
						secondaryInvolvementInsertion(
										$db, $VicePresidentID, $cID, $price, $p, $cVPP, $i, 3);
					}
					//Uplines
					
					if($ImmediateUplineID != null){//Upline 1
						$UP2IDSQL = //Upline 2 ID and status of upline 1
							"SELECT ImmediateUpline_ID,Status FROM agent WHERE Agent_ID=" . $ImmediateUplineID;
						$UP2IDResult = mysqli_query($db, $UP2IDSQL);
						$UP2IDRow = $UP2IDResult->fetch_assoc();
						$UP2ID = $UP2IDRow["ImmediateUpline_ID"];
						if($UP2IDRow["Status"] == 0){//Upline 1 is fired/not in employment
							secondaryInvolvementInsertion(//Money goes to the company
										$db, 0, $cID, $price, $p, 7, $i, 4);
						}
						if(in_array($ImmediateUplineID , $agents)){//one of the primary agents involved
							if($ImmediateUplineID == $PresidentID ){//Branch President
									secondaryInvolvementInsertion(
											$db, 0, $cID, $price, $p, $cPresP, $i, 2);
								}else if($ImmediateUplineID == $VicePresidentID){//Branch VP
									secondaryInvolvementInsertion(
											$db, 0, $cID, $price, $p, $cVPP, $i, 3);
								}else{//Neither branch pres nor VP
									secondaryInvolvementInsertion(
										$db, 0, $cID, $price, $p, 7, $i, 4);
								}
						}else if($ImmediateUplineID == $PresidentID){//Branch President
							secondaryInvolvementInsertion(
										$db, $ImmediateUplineID, $cID, $price, $p, $cPresP, $i, 2);
						}
						else if($ImmediateUplineID == $VicePresidentID){//Branch VP
							secondaryInvolvementInsertion(
										$db, $ImmediateUplineID, $cID, $price, $p, $cVPP, $i, 3);
						}
						else if($ImmediateUplineID != $PresidentID
							&& $ImmediateUplineID != $VicePresidentID){
							//Not the pres or vp and not one of the primary agents
							secondaryInvolvementInsertion(
										$db, $ImmediateUplineID, $cID, $price, $p, 7, $i, 4);
						}
						//continue for 2nd upline
						if($UP2ID != null){
							$UP3IDSQL = //Upline 3 ID and Upline 2 Status
								"SELECT ImmediateUpline_ID,Status FROM agent WHERE Agent_ID=" . $UP2ID;
							$UP3IDResult = mysqli_query($db, $UP3IDSQL);
							$UP3IDRow = $UP3IDResult->fetch_assoc();
							$UP3ID = $UP3IDRow["ImmediateUpline_ID"];
							if(in_array($UP2ID , $agents)){//one of the primary agents involved
								if($UP2ID == $PresidentID ){//Branch President
										secondaryInvolvementInsertion(
												$db, 0, $cID, $price, $p, $cPresP, $i, 2);
									}else if($UP2ID == $VicePresidentID){//Branch VP
										secondaryInvolvementInsertion(
												$db, 0, $cID, $price, $p, $cVPP, $i, 3);
									}else{//Neither branch pres nor VP
										secondaryInvolvementInsertion(
											$db, 0, $cID, $price, $p, 2, $i, 5);
									}
							}else if($UP2ID == $PresidentID){//Branch President
								secondaryInvolvementInsertion(
											$db, $ImmediateUplineID, $cID, $price, $p, $cPresP, $i, 2);
							}
							else if($UP2ID == $VicePresidentID){//Branch VP
								secondaryInvolvementInsertion(
											$db, $ImmediateUplineID, $cID, $price, $p, $cVPP, $i, 3);
							}
							else if($UP2ID != $PresidentID
								&& $UP2ID != $VicePresidentID){
								//Not the pres or vp and not one of the primary agents
								secondaryInvolvementInsertion(
											$db, $ImmediateUplineID, $cID, $price, $p, 2, $i, 5);
							}
							//continue for 3rd upline if he exists
							if($UP3ID != null){
								$UP3StatusSQL = //Upline 3 Status
									"SELECT ImmediateUpline_ID,Status FROM agent WHERE Agent_ID=" . $UP3ID;
								$UP3StatusResult = mysqli_query($db, $UP3StatusSQL);
								$UP3StatusRow = $UP3StatusResult->fetch_assoc();
								$UP3Status = $UP3StatusRow["ImmediateUpline_ID"];
								if($UP3IDRow["Status"] == 0){//Upline 3 is fired/not in employment
								secondaryInvolvementInsertion(//Money goes to the company
											$db, 0, $cID, $price, $p, 2, $i, 6);
								}
								if(in_array($UP3ID , $agents)){//one of the primary agents involved
									if($UP3ID == $PresidentID ){//Branch President
											secondaryInvolvementInsertion(
													$db, 0, $cID, $price, $p, $cPresP, $i, 2);
										}else if($UP3ID == $VicePresidentID){//Branch VP
											secondaryInvolvementInsertion(
													$db, 0, $cID, $price, $p, $cVPP, $i, 3);
										}else{//Neither branch pres nor VP
											secondaryInvolvementInsertion(
												$db, 0, $cID, $price, $p, 1, $i, 6);
										}
								}else if($UP3ID == $PresidentID){//Branch President
									secondaryInvolvementInsertion(
												$db, $UP3ID, $cID, $price, $p, $cPresP, $i, 2);
								}
								else if($UP3ID == $VicePresidentID){//Branch VP
									secondaryInvolvementInsertion(
												$db, $UP3ID, $cID, $price, $p, $cVPP, $i, 3);
								}
								else if($UP3ID != $PresidentID
									&& $UP3ID != $VicePresidentID){
									//Not the pres or vp and not one of the primary agents
									secondaryInvolvementInsertion(
												$db, $UP3ID, $cID, $price, $p, 1, $i, 6);
								}
							}
						}
					}
					}
					else{
						$stmti->close();
						echo "Error: <br>" . mysqli_error($db);
					}
				}
			} else {
				$stmt->close();
			echo "Error: <br>" . mysqli_error($db);
			}
		}
	}
	//Trims and prevents malicious inputs
	function test_input($data) {
	$data = trim($data);
	$data = stripslashes($data);
	$data = htmlspecialchars($data);
	return $data;
	}
	//Function to add Secondary level involvement of an agent e.g.: Uplines, President & VP
	function secondaryInvolvementInsertion($db, $Agent_ID, $Closing_ID, $price, $agentPercentage,
											$ownPercentage, $agentNumber, $workedAs){
		$insertion = $db->prepare("
		INSERT INTO `agent_involved_in_closing`(`Agent_ID`, `Closing_ID`, `earning`, `workedAs`)
		VALUES (?,?,?,?)");
		
		$insertion->bind_param('iidi', $field1, $field2, $field3, $field4);
		$field1 = $Agent_ID;
		$field2 = $Closing_ID;
		$field3 = $price * $agentPercentage * $ownPercentage / 10000;
		$field4 = 6*$agentNumber + $workedAs;
		
		if($insertion->execute()){
		$insertion->close();
		echo "Secondary Agent Involvement created successfully <br>";
		}else{
			$insertion->close();
			echo "Error: " . mysqli_error($db);
		}
	}
	?>
</body>
</html>