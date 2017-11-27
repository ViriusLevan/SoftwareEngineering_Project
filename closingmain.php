<?php
$pagename='closing';
include('session.php');
?>
<html>
<head>
	<?php include('htmlhead.php'); ?>
	<title>Closing</title>
</head>
<body class="mainbody" 
<?php
if(isset($_GET["id"])){
	echo "onload ='agentOptions(1);showDelete();'";
}elseif(isset($_GET["detailid"])){
	echo "onload ='agentOptions(1);showDetail();'";
}else{
	echo "onload ='agentOptions(1)'";
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
	if (isset($_POST["cID"])) {
		$deletionSQL = $db->prepare("DELETE FROM `closing` WHERE closing_ID = ?");
		$deletionSQL->bind_param('i',$f1);
		$f1 = $_POST["cID"];

		if($deletionSQL->execute()){
			$deletionSQL->close();
						// echo "Closing berhasil dihapus";
		}else{
			$deletionSQL->close();
			echo "Error: <br>" . mysqli_error($db);
		}

	}
	else if(isset($_POST["address"])){
		$address = $date = $price = $nAgents = $agent1ID = "";
		$password = $passwordCon = "";

		$address = test_input($_POST["address"]);
		$date = $_POST["date"];
		$price = test_input($_POST["price"]);
		$nAgents = test_input($_POST["nAgents"]);
		$agent1ID = test_input($_POST["agent1ID"]);

		if($nAgents>1)$agent2ID = test_input($_POST["agent2ID"]);
		if($nAgents>2)$agent3ID = test_input($_POST["agent3ID"]);
		if($nAgents>3)$agent4ID = test_input($_POST["agent4ID"]);
		$agents = [];
					// echo "<h2>Your Input:</h2>";

					// if(isset($address))echo $address. "<br>";
					// if(isset($date))echo $date. "<br>";
					// if(isset($price))echo $price. "<br>";
					// if(isset($nAgents))echo $nAgents. "<br>";
		if(isset($agent1ID)){
						// echo $agent1ID. "<br>";
			$agents[] = $agent1ID;
		}
		if(isset($agent2ID)){
						// echo $agent2ID. "<br>";
			$agents[] = $agent2ID;
		}
		if(isset($agent3ID)){
						// echo $agent3ID. "<br>";
			$agents[] = $agent3ID;
		}
		if(isset($agent4ID)){
						// echo $agent4ID. "<br>";
			$agents[] = $agent4ID;
		}
					// echo "<br>";

		if (!$db) {
			die("Connection failed: " . mysqli_connect_error());
		}
		else{
			$stmt = $db->prepare("INSERT INTO closing (`Date`, `Price`, `Address`)
				VALUES (?,?,?)");
			$stmt->bind_param('sis', $field1, $field2, $field3);
			$field1 = $date;
			$field2 = $price;
			$field3 = $address;
						if ($stmt->execute()) {//Closing Creation
							$stmt->close();
							// echo "Closing berhasil dibuat <br>";
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
								if($nAgents == 3){//
									if($i == 0){
										$p = 50;
									}else{
										$p = 25;
									}
								}
								$f3 = $p*$price/100;
								$f4 = 6*$i + 1;
								if($stmti->execute()){
									$stmti->close();
									// echo "Agen utama berhasil dibuat <br>";
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

									$cPresPSQL = //Current President Percentage
											"SELECT Percentage FROM `paypercentages`
											WHERE JobName = 'President' AND ValidityEnd IS NULL";
										$cPresPResult = mysqli_query($db, $cPresPSQL);
										$cPresPRow = $cPresPResult->fetch_assoc();
										$cPresP = $cPresPRow["Percentage"];
									//If there is a president and he's not the primary agent
									if($PresidentID != null && !in_array($PresidentID , $agents)){
										secondaryInvolvementInsertion(
											$db, $PresidentID, $cID, $price, $p, $cPresP, $i, 2);
										$agents[] = $PresidentID;
									}else{
										secondaryInvolvementInsertion(
											$db, 0, $cID, $price, $p, $cPresP, $i, 2);
									}


									$cVPPSQL = //Current Vice President Percentage
											"SELECT Percentage FROM `paypercentages`
											WHERE JobName = 'VicePresident' AND ValidityEnd IS NULL";
										$cVPPResult = mysqli_query($db, $cVPPSQL);
										$cVPPRow = $cVPPResult->fetch_assoc();
										$cVPP = $cVPPRow["Percentage"];
									//If there is a vice president and he's not the primary agent
									if($VicePresidentID != null && !in_array($VicePresidentID , $agents)){
										secondaryInvolvementInsertion(
											$db, $VicePresidentID, $cID, $price, $p, $cVPP, $i, 3);
										$agents[] = $VicePresidentID;
									}else{
										secondaryInvolvementInsertion(
											$db, 0, $cID, $price, $p, $cVPP, $i, 3);
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
										}else if(in_array($ImmediateUplineID , $agents)){//involvement already exists
											secondaryInvolvementInsertion(
												$db, 0, $cID, $price, $p, 7, $i, 4);
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
											if($UP3IDRow["Status"] == 0){//Upline 2 is fired/not in employment
												secondaryInvolvementInsertion(//Money goes to the company
													$db, 0, $cID, $price, $p, 2, $i, 5);
											}else if(in_array($UP2ID , $agents)){//involvement already exists
												secondaryInvolvementInsertion(
													$db, 0, $cID, $price, $p, 2, $i, 5);
											}else if($UP2ID == $PresidentID){//Branch President
												secondaryInvolvementInsertion(
													$db, $UP2ID, $cID, $price, $p, $cPresP, $i, 2);
											}
											else if($UP2ID == $VicePresidentID){//Branch VP
												secondaryInvolvementInsertion(
													$db, $UP2ID, $cID, $price, $p, $cVPP, $i, 3);
											}
											else if($UP2ID != $PresidentID
												&& $UP2ID != $VicePresidentID){
												//Not the pres or vp and not one of the primary agents
												secondaryInvolvementInsertion(
													$db, $UP2ID, $cID, $price, $p, 2, $i, 5);
											}
											//continue for 3rd upline if he exists
											if($UP3ID != null){
												$UP3StatusSQL = //Upline 3 Status
												"SELECT ImmediateUpline_ID,Status FROM agent WHERE Agent_ID=" . $UP3ID;
												$UP3StatusResult = mysqli_query($db, $UP3StatusSQL);
												$UP3StatusRow = $UP3StatusResult->fetch_assoc();
												$UP3Status = $UP3StatusRow["ImmediateUpline_ID"];
												if($UP3StatusRow["Status"] == 0){//Upline 3 is fired/not in employment
													secondaryInvolvementInsertion(//Money goes to the company
														$db, 0, $cID, $price, $p, 1, $i, 6);
												}else if(in_array($UP3ID , $agents)){//involvement already exists
													secondaryInvolvementInsertion(
														$db, 0, $cID, $price, $p, 1, $i, 6);
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
											}else{
												secondaryInvolvementInsertion(//Upline 3 company
													$db, 0, $cID, $price, $p, 1, $i, 6);
											}
										}else{
											secondaryInvolvementInsertion(//Upline 2 company
												$db, 0, $cID, $price, $p, 2, $i, 5);
											secondaryInvolvementInsertion(//Upline 3 company
												$db, 0, $cID, $price, $p, 1, $i, 6);
										}
									}else{
										secondaryInvolvementInsertion(//Upline 1 company
												$db, 0, $cID, $price, $p, 7, $i, 4);
										secondaryInvolvementInsertion(//Upline 2 company
												$db, 0, $cID, $price, $p, 2, $i, 5);
										secondaryInvolvementInsertion(//Upline 3 company
												$db, 0, $cID, $price, $p, 1, $i, 6);
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
			}
			?>
			>
			<?php include('sidebar.php'); ?>
			<div class="content">
				<?php include('header.php'); ?>
				<div class="maincontent">
					<div class="kantormainbtn">
						<button onclick="document.getElementById('tambah').style.display='block'" class="btn kantormaintambahbtn" data-toggle="modal" data-target="#exampleModal">TAMBAH</button>
						<button onclick="printDiv('printableArea')" class="btn printbtn">CETAK</button>
					</div>
					<br>
					<div class="kantormainfilter">
						<h2>Filter</h2>
						<p id="date_filter">
							<span id="date-label-from" class="date-label">From: </span><input class="date_range_filter date" type="text" id="datepicker_from" />
							<span id="date-label-to" class="date-label">To:<input class="date_range_filter date" type="text" id="datepicker_to" />
							</p>
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
				<br>
				<div class="kantormaintabel" id="printableArea">
					<div class="kantormaintabelheader"><h4>Hasil Closing</h4></div>
					<table class="table sortable" id="datatable">
						<thead>
							<tr>
								<th>Alamat</th>
								<th>Tanggal</th>
								<th>Harga (Rp)</th>
								<th>Opsi</th>
							</tr>
						</thead>
						<?php
						$closingSQL = "SELECT * FROM closing";
						$closingResult = mysqli_query($db,$closingSQL);
						if ($closingResult->num_rows > 0) {
							while($row = $closingResult->fetch_assoc()) {
									// output data of each row
								echo "<tr><td> " . $row["Address"]. " </td>";
								echo "<td>   " . $row["Date"]. " </td>";
								$asd = $row["closing_ID"];
								echo "<td class='pull-right'> " . number_format($row["Price"]) . " </td>";
								?>
								<td>
									<a href='closingmain.php?detailid=<?php echo $row["closing_ID"]; ?>' class="btn closingdetailedit">DETAIL</a>
									<a class="btn closingdetaildelete" 
									href='closingmain.php?id=<?php echo $row["closing_ID"];?>
									&cAddress=<?php echo $row["Address"];?>'
									>HAPUS</a>	
								</td>
							</tr>
							<?php
						}
					} else {
						echo "Tidak ada closing";
					}
					?>
				</table>
			</div>
		<div id="delete" class="w3-modal" data-backdrop="">
			<div class="w3-modal-content w3-animate-top w3-card-4">
				<header class="w3-container modalheader">
					<span onclick="document.getElementById('delete').style.display='none'"
					class="w3-button w3-display-topright">&times;</span>
					<h2>HAPUS CLOSING</h2>
				</header>
				<div class="w3-container">
					<h5 class="kantormainformlabel">Apakah anda yakin mau menghapus closing 
						<?php echo $_GET["cAddress"];?>?</h5>
						<br>
						<div class="modalfooter">
							<form method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>">
								<input type="hidden" name="cID" value="<?php echo $_GET["id"];?>">
								<button type="button" class="btn modalleftbtn" 
								onclick="document.getElementById('delete').style.display='none'">BATAL</button>
								<button type="submit" class="btn kantormodalhapus"
								onclick="document.getElementById('delete').style.display='none'">HAPUS</button>
							</form>
						</div>
					</div>
				</div>
			</div>
			<script type="text/javascript">
				function showDelete(){
					document.getElementById('delete').style.display='block'
				}
				function showDetail(){
					document.getElementById('detail').style.display='block'
				}
			</script>
			<div id="edit" class="w3-modal" data-backdrop="">
				<div class="w3-modal-content w3-animate-top w3-card-4">
					<header class="w3-container modalheader">
						<span onclick="document.getElementById('edit').style.display='none'"
						class="w3-button w3-display-topright">&times;</span>
						<h2>EDIT CLOSING</h2>
					</header>
					<div class="w3-container">
						<form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>">
							<h5 class="kantormainformlabel">Alamat</h5>
							<input class="form-control" type="text" name="address" placeholder="Masukkan alamat properti" required>
							<div class="row">
								<div class="col">
									<h5 class="kantormainformlabel">Tanggal</h5>
									<input class="form-control" type="date" name="date" id="tanggalClosing" required>
								</div>
								<div class="col">
									<h5 class="kantormainformlabel">Harga (Rp)</h5>
									<input class="form-control" placeholder="Masukkan harga properti" id="fadielGanteng" type="number" name="price" min=100000.00 step="any" data-a-sign="" data-a-dec="," data-a-sep="." required>
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
									<!-- <?php
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
													onchange='optionDisabling()' class='agentSelection 
													form-control' required>";
											while($agentRow = $agentResult->fetch_assoc()) {
												echo "<option value=".$agentRow["Agent_ID"]."> "
													. $agentRow["Name"] ." </option>";
											}
												echo "</select>";
										}
										else {
											echo "Tidak ada agen <br>";
										}
										?> -->
									</div>
									<div class="col">
										<h5 class="kantormainformlabel">Agen 2</h5>
									<!-- <?php
										$agentResult = mysqli_query($db, $agentSQL);
										if ($agentResult->num_rows > 0) {
											echo "<select name='agent2ID' id='agent2Select'
													onchange='optionDisabling()' class='agentSelection 
													form-control' required>";
											while($agentRow = $agentResult->fetch_assoc()) {
												echo "<option value=".$agentRow["Agent_ID"]."> ". $agentRow["Name"] ." </option>";
											}
											echo "</select>";
										}
										else {
											echo "Tidak ada agen <br>";
										}
										?> -->
									</div>
								</div>
								<div class="row">
									<div class="col">
										<h5 class="kantormainformlabel">Agen 3</h5>
									<!-- <?php
										$agentResult = mysqli_query($db, $agentSQL);
										if ($agentResult->num_rows > 0) {
											echo "<select name='agent3ID' id='agent3Select'
													onchange='optionDisabling()' class='agentSelection 
													form-control' required>";
											while($agentRow = $agentResult->fetch_assoc()) {
												echo "<option value=".$agentRow["Agent_ID"]."> ". $agentRow["Name"] ." </option>";
											}
											echo "</select>";
										}
										else {
											echo "Tidak ada agen <br>";
										}
										?> -->
									</div>
									<div class="col">
										<h5 class="kantormainformlabel">Agen 4</h5>
									<!-- <?php
									$agentResult = mysqli_query($db, $agentSQL);
										if ($agentResult->num_rows > 0) {
											echo "<select name='agent4ID' id='agent4Select'
													onchange='optionDisabling()' class='agentSelection 
													form-control' required>";
											while($agentRow = $agentResult->fetch_assoc()) {
												echo "<option value=".$agentRow["Agent_ID"]."> ". $agentRow["Name"] ." </option>";
											}
											echo "</select>";
										}
										else {
											echo "Tidak ada agen <br>";
										}
										?> -->
									</div>
								</div>
								<br>
								<div class="modalfooter">
									<button type="button" class="btn modalleftbtn" onclick="document.getElementById('edit').style.display='none'">BATAL</button>
									<button type="submit" class="btn modalrightbtn">SIMPAN</button>
								</div>
							</form>
						</div>
					</div>
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
										<input class="form-control" type="date" name="date" value="<?php echo date('Y-m-d');?>" 	min="2000-01-01" max="<?php echo date('Y-m-d');?>" required>
									</div>
									<div class="col">
										<h5 class="kantormainformlabel">Harga (Rp)</h5>
										<input class="form-control" placeholder="Masukkan harga properti" type="number" id="fadielGanteng" data-a-sign="" data-a-dec="," data-a-sep="." name="price" min=100000.00 step="any" required>
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
										AND agent_branch_employment.End IS NULL
										AND branch.status = 1";
										$agentResult = mysqli_query($db, $agentSQL);
										if ($agentResult->num_rows > 0) {
											echo "<select name='agent1ID' id='agent1Select'
											onchange='optionDisabling()' class='agentSelection 
											form-control' required>";
											while($agentRow = $agentResult->fetch_assoc()) {
												echo "<option value=".$agentRow["Agent_ID"]."> "
												. $agentRow["Name"] ." </option>";
											}
											echo "</select>";
										}
										else {
											echo "Tidak ada agen <br>";
										}
										?>
									</div>
									<div class="col">
										<h5 class="kantormainformlabel">Agen 2</h5>
										<?php
										$agentResult = mysqli_query($db, $agentSQL);
										if ($agentResult->num_rows > 0) {
											echo "<select name='agent2ID' id='agent2Select'
											onchange='optionDisabling()' class='agentSelection 
											form-control' required>";
											while($agentRow = $agentResult->fetch_assoc()) {
												echo "<option value=".$agentRow["Agent_ID"]."> ". $agentRow["Name"] ." </option>";
											}
											echo "</select>";
										}
										else {
											echo "Tidak ada agen <br>";
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
											onchange='optionDisabling()' class='agentSelection 
											form-control' required>";
											while($agentRow = $agentResult->fetch_assoc()) {
												echo "<option value=".$agentRow["Agent_ID"]."> ". $agentRow["Name"] ." </option>";
											}
											echo "</select>";
										}
										else {
											echo "Tidak ada agen <br>";
										}
										?>
									</div>
									<div class="col">
										<h5 class="kantormainformlabel">Agen 4</h5>
										<?php
										$agentResult = mysqli_query($db, $agentSQL);
										if ($agentResult->num_rows > 0) {
											echo "<select name='agent4ID' id='agent4Select'
											onchange='optionDisabling()' class='agentSelection 
											form-control' required>";
											while($agentRow = $agentResult->fetch_assoc()) {
												echo "<option value=".$agentRow["Agent_ID"]."> ". $agentRow["Name"] ." </option>";
											}
											echo "</select>";
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
						for (var i = 0; i < select.length; i++) {
							if(select[i].disabled == false){
								selections.push(select[i].value);
							}
						}
						for (var i = 0; i<select.length; i++) {
							var opt = select[i].getElementsByTagName("option");
							for (var j = 0; j < opt.length; j++) {
								if(selections.indexOf(opt[j].value) == -1){
										//not found on selections
										opt[j].disabled = false;
									}else if(opt[j].value == select[i].value){
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
			<div id="detail" class="w3-modal" data-backdrop="">
				<div class="w3-modal-content w3-animate-top w3-card-4">
					<header class="w3-container modalheader">
						<span onclick="document.getElementById('detail').style.display='none'"
						class="w3-button w3-display-topright">&times;</span>
						<h2>DETAIL HASIL CLOSING</h2>
					</header>
					<div class="w3-container">
						<?php
						$row = $_GET["detailid"];

						$PPSQL = "SELECT Percentage, JobName
						                          FROM `paypercentages`,closing
						                          WHERE (DATEDIFF (closing.Date,ValidityEnd)<=0 OR ValidityEnd IS NULL)
						                            AND DATEDIFF (closing.Date,ValidityStart)>=0
						                            AND closing.closing_ID = $row";
				        $PPResults = mysqli_query($db, $PPSQL);
				        $PresP ="";
				        $VPP = "";
				        while ($PPRow = $PPResults->fetch_assoc()) {
				            if($PPRow["JobName"] == "President"){
				              $PresP = $PPRow["Percentage"];
				            }else if($PPRow["JobName"] == "VicePresident"){
				              $VPP = $PPRow["Percentage"];
				            }
				        }

						$idSQL = "SELECT Agent.Agent_ID, Agent.Name, agent_involved_in_closing.earning, 
			                      agent_involved_in_closing.workedAs as workedAs, 
			                      branch.Name AS bName, PhoneNumber, aCount.nAgents AS ac
			                    FROM Agent_involved_in_closing, Agent, branch, agent_branch_employment,
			                      (SELECT closing.closing_ID AS cID, closing.Date As cDate,
			                          COUNT(agent_involved_in_closing.Agent_ID) AS nAgents
			                        FROM agent_involved_in_closing, closing
			                        WHERE agent_involved_in_closing.Closing_ID = closing.closing_ID
			                            AND agent_involved_in_closing.workedAs IN (1,7,13,19)
			                        GROUP BY closing.closing_ID) aCount
			                    WHERE Agent.Agent_ID = agent_involved_in_closing.Agent_ID
			                    AND aCount.cID = agent_involved_in_closing.Closing_ID
			                    AND DATEDIFF(aCount.cDate, agent_branch_employment.Started)>=0
			                    AND (DATEDIFF(aCount.cDate, agent_branch_employment.End)<=0 OR agent_branch_employment.End IS NULL)
			                    AND agent_branch_employment.Branch_ID = branch.branch_id
			                    AND agent_branch_employment.Agent_ID = agent.Agent_ID
			                    AND agent_involved_in_closing.Closing_ID = $row 
			                    ORDER BY workedAs ASC";

						$idResults = mysqli_query($db, $idSQL);

						if ($idResults->num_rows > 0) {
							while($agentRow = $idResults->fetch_assoc()) { 
								if($agentRow["workedAs"] == 1 || $agentRow["workedAs"] == 7 
									|| $agentRow["workedAs"] == 13 || $agentRow["workedAs"] == 19){
									
									echo '</table>'; 
									echo '<table class="table">';
									echo "<tr> <th>Nama</th> <th>Komisi</th> <th>Persentase</th> <th>Sebagai</th> 
									<th>No. Telepon</th> <th>Opsi</th> </tr>";
								}

								$workedAs = "";
              					$Percentage = "";
								$workedAs = setWorkedAs($agentRow["workedAs"]);
								$Percentage = setPercentage($agentRow["workedAs"],$agentRow["ac"],$PresP,$VPP);

					              // output data of each row
								echo "<tr><td> " . $agentRow["Name"]. " </td>"; 
								echo '<td class="pull-right"> ' . number_format($agentRow["earning"]). " </td>";
              					echo "<td> " . $Percentage . "% </td>"; 
								echo "<td> " .  $workedAs . " </td>";
								echo "<td> " . $agentRow["PhoneNumber"]. " </td>";
								?> 
								<td>

									<a class="btn closingdetailagen" 
										href='agent_details.php?id=<?php echo $agentRow["Agent_ID"]; ?>'>DETAIL</a> 
								</td></tr>
								<?php

							}
							echo "</table>";
						} else {
					            //SHOULD NEVER HAPPEN
							echo "Tidak ada agen";
						}
						?>

						<?php 
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

						    function createEmptyRow($workedAs, $percentage){
						    	echo "<tr><td>-----------</td>"; 
								echo "<td>-----------</td>";
              					echo "<td> " . $percentage . "% </td>"; 
								echo "<td> " .  $workedAs . " </td>";
								echo "<td>-----------</td>";
								echo "<td>-----------</td> </tr>";
						    }
						?>
					</div>
					<div class="modalfooterclosing">
						<button type="submit" class="btn closingkembalibtn" 
						onclick="document.getElementById('detail').style.display='none'">
					KEMBALI</button>
				</div>
			</div>						
		</div>
	</div>
</div>
</div>
<?php



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
	$insertion = $db->prepare("INSERT INTO `agent_involved_in_closing`
		(`Agent_ID`, `Closing_ID`, `earning`, `workedAs`)
		VALUES (?,?,?,?)");

	$insertion->bind_param('iidi', $field1, $field2, $field3, $field4);
	$field1 = $Agent_ID;
	$field2 = $Closing_ID;
	$field3 = $price * $agentPercentage * $ownPercentage / 10000;
	$field4 = 6*$agentNumber + $workedAs;

	if($insertion->execute()){
		$insertion->close();
			// echo "Agen pendukung berhasil dibuat <br>";
	}else{
		$insertion->close();
		echo "Error: " . mysqli_error($db);
	}
}
?>


</body>
</html>