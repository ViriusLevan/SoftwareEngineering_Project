<?php 
	$pagename='agen'; 
	include('session.php');
?>
<html>
	<head>
		<?php include('htmlhead.php'); ?>
		<title>Agen</title>
	</head>
	<body class="mainbody">
		<?php include('sidebar.php'); ?>
		<div class="content">
			<?php include('header.php'); ?>
			<div class="agentree">
				Insert agent tree here -raka <br>
				Or shud i put agent listing here, and get hans to put the tree on agent details - fadiel
				<br>
				<button class="btn" onclick="document.getElementById('agendetail').style.display='block'">Agen A</button>
			</div>
			<div class="agenfooter">
				<button class="btn agentambahbtn" onclick="document.getElementById('tambah').style.display='block'">Tambah Agen</button>
			</div>
			<div id="tambah" class="w3-modal" data-backdrop="">
					<div class="w3-modal-content w3-animate-top w3-card-4">
						<header class="w3-container modalheader">
							<span onclick="document.getElementById('tambah').style.display='none'"
							class="w3-button w3-display-topright">&times;</span>
							<h2>TAMBAH AGEN BARU</h2><!--Agent_add.php -->
						</header>
						<div class="w3-container">
							<form action="">
								<h5 class="kantormainformlabel">Nama Agen</h5>
								<input class="form-control" type="text" placeholder="Masukkan nama agen"
									name="name" required>
								<h5 class="kantormainformlabel">Nomor Telepon</h5>
								<input class="form-control" placeholder="Masukkan nomor telepon" 
									type="tel" name="phone" pattern="[0-9]+" required>
								<br>
								<div class="row">
									<div class="col">
										<h5 class="kantormainformlabel">Kantor</h5>
										<!-- <select name="kantor" class="form-control kantormainselectvpv">
											<option value="id">Nama Kantor</option>
										</select> -->
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
										    	echo "No branches found<br>";
											}
										?>
									</div>
									<div class="col">
										<h5 class="kantormainformlabel">Upline</h5>
										<!-- <select name="kantor" class='form-control kantormainselectvpv'>
											<option value="id">Nama Upline</option>
										</select> -->
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
						</div>
					</div>
				</div>
			<div id="agendetail" class="w3-modal" data-backdrop="">
				<div class="w3-modal-content w3-animate-top w3-card-4">
					<header class="w3-container modalheader">
						<span onclick="document.getElementById('agendetail').style.display='none'"
						class="w3-button w3-display-topright">&times;</span>
						<h1>AGEN A</h1>
					</header>
					<div class="w3-container agencontainer">
						<div class="container">
							<div class="row">
								<div class="col">
									<h2>ID</h2>
									<h2>Kantor</h2>
									<h2>Upline</h2>
									<h2>No. Telepon</h2>
								</div>
								<div class="col-8">
									<h2>: A-123</h2>
									<h2>: Kantor A</h2>
									<h2>: Agen X</h2>
									<h2>: 0812345696969</h2>
								</div>
							</div>
						</div>
					</div>
					<div class="agenkembalihapusubah">
						<button type="submit" class="btn agenkembali" onclick="document.getElementById('agendetail').style.display='none'">KEMBALI</button>
						<button type="submit" class="btn agenhapus" onclick="document.getElementById('hapus').style.display='block'">HAPUS</button>
						<button type="submit" class="btn agenubah">UBAH</button>
					</div>
				</div>
			</div>
			<div id="hapus" class="w3-modal" data-backdrop="">
					<div class="w3-modal-content w3-animate-top w3-card-4">
						<header class="w3-container">
							<h2>Apakah anda yakin ingin menghapus item ini?</h2>
						</header>
						<div class="w3-container">							
							<div class="modalfooter">
								<button type="submit" class="btn modalleftbtn" onclick="document.getElementById('hapus').style.display='none'">TIDAK</button>
								<button type="submit" class="btn modalrightbtn">IYA</button>
							</div>
						</div>
					</div>
				</div>
		</div>
		<?php
			$name = $phone = $UplineID = $BranchID = "";
			$password = $passwordCon = "";
			if ($_SERVER["REQUEST_METHOD"] == "POST") {
				$name = test_input($_POST["name"]);
				$phone = $_POST["phone"];
				$UplineID = test_input($_POST["UplineID"]);
				$BranchID = test_input($_POST["BranchID"]);

				echo "<h2>Your Input:</h2>";
				if(isset($name))echo $name. "<br>";  
				if(isset($phone))echo $phone. "<br>";  
				if(isset($UplineID))echo $UplineID. "<br>";  
				if(isset($BranchID))echo $BranchID. "<br>";
				echo "<br>";

			  	if ($UplineID == "empty") {$UplineID = null;}


		//ERROR CHECKS

			  	if (!$db) {
			    	die("Connection failed: " . mysqli_connect_error());
				}
				else{
						$stmt = $db->prepare("
							INSERT INTO agent (Branch_ID, Name, ImmediateUpline_ID, Status, PhoneNumber 
							VALUES (?,?,?,1,?)");
						$stmt->bind_param('isiss', $field1, $field2, $field3, $field4);

						$field1 = $BranchID;
						$field2 = $name;
						$field3 = $UplineID;
						$field4 = $phone;

					if ($stmt->execute()) {
						$stmt->close();
					    echo "New agent created successfully";
					} else {
						$stmt->close();
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
	</body>

</html>

