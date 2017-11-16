<?php
	$pagename='kantor';
	include('session.php');
?>
<html>
	<head>
		<?php include('htmlhead.php'); ?>
		<title>Kantor</title>
	</head>
	<body class="mainbody">
		<?php include('sidebar.php'); ?>
		<div class="content">
			<?php include('header.php'); ?>
			<div class="maincontent">
				<div class="kantormainbtn">
					<button onclick="document.getElementById('tambah').style.display='block'" class="btn kantormaintambahbtn" data-toggle="modal" data-target="#exampleModal">TAMBAH</button>
					<a href="kantordaftar.php" class="btn kantormainprodukbtn">DAFTAR KANTOR</a>
				</div>
				<br>
				<div class="kantormainfilter">
					<h2>Filter</h2>
					<form action=<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?> method="post">
						<h5 class="kantormainformlabel">Tanggal&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:</h5>
						<input type="date" name="bfrDate"
						id="startDate" onchange="closingFilter()" class="form-control kantormainselect">
						<h5 class="kantormainformlabel">s/d</h5>
						<input type="date" name="aftDate"
						id="endDate" onchange="closingFilter()" class="form-control kantormainselect">
<!-- 						<input type="submit" name="submit" class="btn kantormainfiltersubmit">
 -->					</form>
				</div>
				<script>
							function closingFilter() {
								// window.alert("shit");
							var startDt = document.getElementById("startDate").value;
							var endDt = document.getElementById("endDate").value;
							// Declare variables
							var table, td, i;
							table = document.getElementById("produktable");
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
				<br>
				<div class="kantormaintabel">
					<div class="kantormaintabelheader"><h4>Hasil Produktivitas Kantor</h4></div>
					<table class="table" id="produktable">
						<tr>
							<th>Nama Kantor/Branch</th>
							<th>Total Closing</th>
							<th>Pendapatan cabang dari Closing (Rp)</th>
						</tr>
						<?php
							if (isset($_POST["bfrDate"]) && isset($_POST["aftDate"])){//filter still doesnt work
								$bfrDate = $_POST["bfrDate"];
								$aftDate = $_POST["aftDate"];
								$branchSQL = "SELECT branch.Name,
												COUNT(DISTINCT agent_involved_in_closing.Closing_ID) AS Productivity,
												SUM(agent_involved_in_closing.earning) AS Earnings
												FROM agent_involved_in_closing, branch, agent,
													Agent_Branch_Employment, closing
												WHERE agent_involved_in_closing.workedAs IN (1,7,13,19)
													AND agent_involved_in_closing.Agent_ID = agent.Agent_ID
												AND agent.Agent_ID = Agent_Branch_Employment.Agent_ID
												AND Agent_Branch_Employment.Branch_ID = branch.Branch_ID
												AND agent_branch_employment.End IS NULL
													AND Agent.Agent_ID != 0
													AND agent_involved_in_closing.Closing_ID = closing.closing_ID
													AND closing.Date >=$bfrDate
													AND closing.Date <=$aftDate
													GROUP BY branch.branch_id";
							}else{
								$branchSQL = "SELECT branch.Name,
												COUNT(DISTINCT agent_involved_in_closing.Closing_ID) AS Productivity,
												SUM(agent_involved_in_closing.earning) AS Earnings
												FROM agent_involved_in_closing, branch, agent,
														Agent_Branch_Employment
												WHERE agent_involved_in_closing.workedAs IN (1,7,13,19)
													AND agent_involved_in_closing.Agent_ID = agent.Agent_ID
												AND agent.Agent_ID = Agent_Branch_Employment.Agent_ID
												AND Agent_Branch_Employment.Branch_ID = branch.Branch_ID
												AND agent_branch_employment.End IS NULL
													AND Agent.Agent_ID != 0
													GROUP BY branch.branch_id";
							}
						$result = mysqli_query($db,$branchSQL);
						if ($result->num_rows > 0) {
							while($row = $result->fetch_assoc()) {
								echo "<tr>";
									echo "<td>". $row["Name"] ."</td>";
									echo "<td>". $row["Productivity"] ."</td>";
									echo "<td>". $row["Earnings"] ."</td>";
							echo "</tr>";
							}
							} else {
							echo "0 results";
							}
						?>
					</table>
				</div>
				<div id="tambah" class="w3-modal" data-backdrop="">
					<div class="w3-modal-content w3-animate-top w3-card-4">
						<header class="w3-container modalheader">
							<span onclick="document.getElementById('tambah').style.display='none'"
							class="w3-button w3-display-topright">&times;</span>
							<h2>TAMBAH KANTOR BARU</h2>
						</header>
						<div class="w3-container">
							<form action="">
								<h5 class="kantormainformlabel">Nama Kantor</h5>
								<input class="form-control" type="text" placeholder="Masukkan nama kantor">
								<h5 class="kantormainformlabel">Alamat Kantor</h5>
								<input class="form-control" type="text" placeholder="Masukkan alamat kantor">
								<br>
								<div class="row">
									<div class="col">
										<h5 class="kantormainformlabel">President</h5>
										<!-- <select name="kantor" class="form-control kantormainselectvpv">
												<option value="id">Nama President</option>
										</select> -->
										<?php
											$sql = "SELECT agent.Name, agent.Agent_ID
													from agent left join branch
													ON agent.Agent_ID != branch.President_ID
													AND agent.Agent_ID != branch.VicePresident_ID
													WHERE agent.Agent_ID != 0";
										$result = mysqli_query($db, $sql);
										if ($result->num_rows > 0) {
											echo "<select name='PresidentID' class='form-control kantormainselectvpv'>";
												echo "<option value='empty'> Noone </option>";
												while($row = $result->fetch_assoc()) {
												echo "<option value=".$row["Agent_ID"]."> ". $row["Name"] ." </option>";
												}
											echo "</select> <br>";
											}
											else {
											echo "No agents available for assignment <br>";
											}
										?>
									</div>
									<div class="col">
										<h5 class="kantormainformlabel">Vice President</h5>
										<!-- <select name="kantor" class="form-control kantormainselectvpv">
												<option value="id">Nama Vice President</option>
										</select> -->
										<?php
											$result = mysqli_query($db, $sql);
											if ($result->num_rows > 0) {
											echo "<select name='VicePresidentID' class='form-control kantormainselectvpv'>";
												echo "<option value='empty'> Noone </option>";
												while($row = $result->fetch_assoc()) {
												echo "<option value=".$row["Agent_ID"]."> ". $row["Name"] ." </option>";
												}
											echo "</select>";
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
			</div>
		</div>
		<?php
			$name = $PresidentID = $VicePresidentID = "";
			if ($_SERVER["REQUEST_METHOD"] == "POST") {
				$name = test_input($_POST["name"]);
				$PresidentID = test_input($_POST["PresidentID"]);
				$VicePresidentID = test_input($_POST["VicePresidentID"]);
				echo "<h2>Your Input:</h2>";
				if(isset($name))echo $name. "<br>";
				if(isset($PresidentID))echo $PresidentID. "<br>";
				if(isset($VicePresidentID))echo $VicePresidentID. "<br>";
				echo "<br>";
				$check = $db->prepare("SELECT Branch_ID FROM branch where status = 1 AND name = ?");
				$check->bind_param('s', $field1);
				$field1 = $name;
				$check->execute();
				$lines = $check->num_rows;
				$duplicate = false;
				$samePerson = false;
				$vpBastard = false;
				if ($PresidentID == "empty") {$PresidentID = null;}
				if ($VicePresidentID == "empty") {$VicePresidentID = null;}
		//ERROR CHECKS
				if($PresidentID == NULL && $VicePresidentID != NULL){
					$vpBastard=true;
					echo "A VP cannot be picked without a President";
				}
				if(count($lines) > 0) {
					$duplicate = true;
					echo "Branch with the same name already exists <br>";
				}
				if($PresidentID == $VicePresidentID && $PresidentID != NULL){
					$samePerson = true;
					echo "President and Vice President cannot be the same person <br>";
				}
				$check->close();
				if(!$samePerson && !$duplicate && !$vpBastard){
					if (!$db) {
					die("Connection failed: " . mysqli_connect_error());
					}
					else{
							$stmt = $db->prepare("INSERT INTO branch (President_ID, VicePresident_ID, Name, status)
									VALUES (?, ?, ?, 1)");
							$stmt->bind_param('iis', $field1, $field2, $field3);
							$field1 = $PresidentID;
							$field2 = $VicePresidentID;
							$field3 = $name;
						if ($stmt->execute()) {
							$stmt->close();
						echo "New branch created successfully";
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
	</body>
</html>