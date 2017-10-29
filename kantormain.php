<?php $pagename='kantor'; ?>
<html>
	<head>
		<?php include('htmlhead.php'); ?>
		<title>Login</title>
	</head>
	<body class="kantormain">
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
						</select>
						<br>
						<h5 class="kantormainformlabel">Kantor&nbsp;&nbsp;&nbsp;&nbsp;:</h5>
						<select name="kantor" class="form-control  kantormainselectkantor">
							<option value="id">Nama kantor</option>
						</select>
					</form>
				</div>
				<br>
				<div class="kantormaintabel">
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
				</div>
				<div id="tambah" class="w3-modal" data-backdrop="">
					<div class="w3-modal-content w3-animate-top w3-card-4">
						<header class="w3-container">
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
								<div class="kantormaintambahbatalsimpan">
									<button type="submit" class="btn kantormaintambahbatal" onclick="document.getElementById('tambah').style.display='none'">BATAL</button>
									<button type="submit" class="btn kantormaintambahsimpan">SIMPAN</button>
								</div> 
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
	</body>
</html>