<?php 
	$pagename='kantor'; 
	include('session.php');
?>
<html>
	<head>
		<?php include('htmlhead.php'); ?>
		<title>Login</title>
	</head>
	<body class="mainbody">
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
						$sql = "SELECT * FROM branch where status = 1";
					   $result = mysqli_query($db,$sql);
					   if ($result->num_rows > 0) {
					    // output data of each row
						    while($row = $result->fetch_assoc()) {
						        echo "<tr> <td>". $row["Name"] . "</td>";
						        echo "<td>". $row["address"] ."</td>"; 
						        if($row["President_ID"] == null){
						        	echo "<td> Noone </td>"; 
						        }else{
						        	echo "<td> " . $row["President_ID"]. "</td>"; 
						    	}
						    	if($row["VicePresident_ID"] == null){
						        	echo "<td> Noone </td>";
						        }else{
						        	echo "<td> " . $row["VicePresident_ID"]. "</td>";
						    	}?><td>
						    	<button type="submit" class="btn kantordaftarubah" onclick="document.getElementById('tambah').style.display='block'">UBAH</button>
						    	<?php 
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
										<h5 class="kantormainformlabel">Principal</h5>
										<select name="kantor" class="form-control kantormainselectvpv">
											<option value="id">Nama Principal</option>
										</select>
									</div>
									<div class="col">
										<h5 class="kantormainformlabel">Vice Principal</h5>
										<select name="kantor" class="form-control kantormainselectvpv">
											<option value="id">Nama Vice Principal</option>
										</select>
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
	</body>
</html>