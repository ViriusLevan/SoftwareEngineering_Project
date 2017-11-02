<?php $pagename='properti'; ?>
<html>
	<head>
		<?php include('htmlhead.php'); ?>
		<title>Properti</title>
	</head>
	<body class="mainbody">
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
						<h5 class="kantormainformlabel">Nama Agen&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:</h5>
						<select name="bfrbulan" class="form-control propertyselect">
							<option value="agenA">Agen A</option>
						</select>
						<br>
						<h5 class="kantormainformlabel">Nama Properti&nbsp;&nbsp;&nbsp;:</h5>
						<select name="kantor" class="form-control  propertyselect">
							<option value="id">Properti A</option>
						</select>
					</form>
				</div>
				<br>
				<div class="kantormaintabel">
					<div class="kantormaintabelheader"><h4>Hasil Properti</h4></div>
					<table class="table">
						<tr>
							<th>Nama Agen</th>
							<th>Nama Properti</th>
							<th>Status</th>
							<th>Harga (Rp)</th>
							<th>Opsi</th>
						</tr>
						<tr>
							<td>Agen A</td>
							<td>Kantor A</td>
							<td>Terjual</td>
							<td>300</td>
							<td>
								<button type="submit" class="btn kantordaftarubah" onclick="document.getElementById('tambah').style.display='block'">UBAH</button>
								<button type="submit" class="btn kantordaftarhapus" onclick="document.getElementById('hapus').style.display='block'">HAPUS</button>
							</td>
						</tr>
					</table>
				</div>
				<div id="tambah" class="w3-modal" data-backdrop="">
					<div class="w3-modal-content w3-animate-top w3-card-4">
						<header class="w3-container modalheader">
							<span onclick="document.getElementById('tambah').style.display='none'"
							class="w3-button w3-display-topright">&times;</span>
							<h2>TAMBAH PROPERTI BARU</h2>
						</header>
						<div class="w3-container">
							<form action="">
								<h5 class="kantormainformlabel">Nama Properti</h5>
								<input class="form-control" type="text" placeholder="Masukkan nama properti">
								<h5 class="kantormainformlabel">Alamat Properti</h5>
								<input class="form-control" type="text" placeholder="Masukkan alamat properti">
								<br>
								<h5 class="kantormainformlabel">Agen 1</h5>
								<select name="kantor" class="form-control kantormainselectvpv">
									<option value="id">Nama Agen 1</option>
								</select>
								<h5 class="kantormainformlabel">Agen 2</h5>
								<select name="kantor" class="form-control kantormainselectvpv">
									<option value="id">Nama Agen 2</option>
								</select>
								<h5 class="kantormainformlabel">Agen 3</h5>
								<select name="kantor" class="form-control kantormainselectvpv">
									<option value="id">Nama Agen 3</option>
								</select>
								<h5 class="kantormainformlabel">Agen 4</h5>
								<select name="kantor" class="form-control kantormainselectvpv">
									<option value="id">Nama Agen 4</option>
								</select>
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