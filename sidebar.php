<div class="side">
	<img class="avatar" src="img/defaultava.png" alt="">
	<a href=""><h3 class="editprofil">EDIT PROFIL</h3></a>
	<br><hr>
	<a href="kantormain.php"><div class="sidenav <?php if ($pagename==='kantor') {echo 'active';} ?>"><h3>KANTOR</h3></div><hr></a>
	<a href=""><div class="sidenav <?php if ($pagename==='agen') {echo 'active';} ?>"><h3>AGEN</h3></div><hr></a>
	<a href=""><div class="sidenav <?php if ($pagename==='properti') {echo 'active';} ?>"><h3>PROPERTI</h3></div><hr></a>
	<a href=""><div class="sidenav <?php if ($pagename==='closing') {echo 'active';} ?>"><h3>CLOSING</h3></div><hr></a>
	<a href=""><div class="logout"><h3>LOG OUT</h3></div></a>
</div>