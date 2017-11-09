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
					<form action="">
						<h5 class="kantormainformlabel">Bulan&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:</h5>
						<!-- 
						BOOM
						<select name="bfrbulan" class="form-control kantormainselect">
							<option value="bfrjan">Januari</option>
							<option value="bfrfeb">Februari</option>
							<option value="bfrmar">Maret</option>
							<option value="bfrapr">April</option>
							<option value="bfrmay">Mei</option>
							<option value="bfrjun">Juni</option>
							<option value="bfrjul">Juli</option>
							<option value="bfraug">Agustus</option>
							<option value="bfrsep">September</option>
							<option value="bfroct">Oktober</option>
							<option value="bfrnov">November</option>
							<option value="bfrdec">Desember</option>
						</select>
						<select name="bfrtahun" class="form-control kantormainselect">
							<option value="bfr10">2010</option>
							<option value="bfr11">2011</option>
							<option value="bfr12">2012</option>
							<option value="bfr13">2013</option>
							<option value="bfr14">2014</option>
							<option value="bfr15">2015</option>
							<option value="bfr16">2016</option>
							<option value="bfr17">2017</option>
						</select>
						<h5 class="kantormainformlabel">s/d</h5>
						<select name="aftbulan" class="form-control kantormainselect">
							<option value="aftjan">Januari</option>
							<option value="aftfeb">Februari</option>
							<option value="aftmar">Maret</option>
							<option value="aftapr">April</option>
							<option value="aftmay">Mei</option>
							<option value="aftjun">Juni</option>
							<option value="aftjul">Juli</option>
							<option value="aftaug">Agustus</option>
							<option value="aftsep">September</option>
							<option value="aftoct">Oktober</option>
							<option value="aftnov">November</option>
							<option value="aftdec">Desember</option>
						</select>
						<select name="afttahun" class="form-control kantormainselect">
							<option value="aft10">2010</option>
							<option value="aft11">2011</option>
							<option value="aft12">2012</option>
							<option value="aft13">2013</option>
							<option value="aft14">2014</option>
							<option value="aft15">2015</option>
							<option value="aft16">2016</option>
							<option value="aft17">2017</option>
						</select> -->
						<br>
						<h5 class="kantormainformlabel">Kantor&nbsp;&nbsp;&nbsp;&nbsp;:</h5><br>
						<!-- <select name="kantor" class="form-control  kantormainselectkantor">
							<option value="id">Nama kantor</option>
						</select> -->
						<?php 
							$sql = "SELECT * FROM branch where status = 1";
						   $result = mysqli_query($db,$sql);
						   if ($result->num_rows > 0) {
						    // output data of each row
							    while($row = $result->fetch_assoc()) {
							        echo "ID: " . $row["branch_id"]. "<br>"; 
							        if($row["President_ID"] == null){
							        	echo "President: Noone <br>"; 
							        }else{
							        	echo "President: " . $row["President_ID"]. "<br>"; 
							    	}
							    	if($row["VicePresident_ID"] == null){
							        	echo " VP: Noone <br>";
							        }else{
							        	echo " VP: " . $row["VicePresident_ID"]. "<br>";
							    	}
							        echo "-------------<br>";
							    }
							} else {
						    	echo "0 results";
							}

						?>
					</form>
				</div>
				<br>
				Move productivity to branch details <br>
				Having it here will MURDER THE SERVER
				<!-- <div class="kantormaintabel">
					<div class="kantormaintabelheader"><h4>Hasil Produktivitas Kantor</h4></div>
					<table class="table">
						<tr>
							<th>Kantor</th>
							<th>Total Transaksi</th>
							<th>Unit Terjual</th>
							<th>Total Komisi (Rp)</th>
						</tr>
						<tr>
							<td>Kantor A</td>
							<td>10</td>
							<td>20</td>
							<td>30</td>
						</tr>
					</table>
				</div> -->
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