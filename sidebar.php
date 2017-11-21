<div class="side">
	<a href="welcome.php"><img class="avatar" src="img/defaultava.png" alt=""></a>
	<br><br><br><br><br><hr>
	<a href="kantordaftar.php"><div class="sidenav <?php if ($pagename==='cabangdaftar') {echo 'active';} elseif ($pagename==='cabangproduk') {echo 'active';}?>"><h3>CABANG</h3></div><hr></a>
	<a href="agenmain.php"><div class="sidenav <?php if ($pagename==='agendaftar') {echo 'active';} elseif ($pagename==='agenproduk') {echo 'active';} elseif ($pagename==='agendetail') {echo 'active';}?>"><h3>AGEN</h3></div><hr></a>
	<a href="closingmain.php"><div class="sidenav <?php if ($pagename==='closing') {echo 'active';} ?>"><h3>CLOSING</h3></div><hr></a>
	<a href="logout.php"><div class="logout"><h3>LOG OUT</h3></div></a>
</div>