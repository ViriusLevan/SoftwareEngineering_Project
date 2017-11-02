<?php $pagename='agen'; ?>
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
				Insert agent tree here
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
							<h2>TAMBAH AGEN BARU</h2>
						</header>
						<div class="w3-container">
							<form action="">
								<h5 class="kantormainformlabel">Nama Agen</h5>
								<input class="form-control" type="text" placeholder="Masukkan nama agen">
								<h5 class="kantormainformlabel">No. Telepon</h5>
								<input class="form-control" type="text" placeholder="Masukkan nomor telepon">
								<br>
								<div class="row">
									<div class="col">
										<h5 class="kantormainformlabel">Kantor</h5>
										<select name="kantor" class="form-control kantormainselectvpv">
											<option value="id">Nama Kantor</option>
										</select>
									</div>
									<div class="col">
										<h5 class="kantormainformlabel">Upline</h5>
										<select name="kantor" class="form-control kantormainselectvpv">
											<option value="id">Nama Upline</option>
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
	</body>
</html>