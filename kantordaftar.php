<?php $pagename='kantor'; ?>
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
							<th>Principal</th>
							<th>Vice Principal</th>
							<th>Opsi</th>
						</tr>
						<tr>
							<td>Kantor A</td>
							<td>Sepanjang Jl.Kenangan Kita Selalu Bergandeng Tangan</td>
							<td>Hans</td>
							<td>Fadiel</td>
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
		</div>
	</body>
</html>