<div class="header">
	<div class="pagetitle">
		<h1>
		<?php
		if ($pagename==='cabangdaftar') {echo 'DAFTAR CABANG';}
		elseif ($pagename==='cabangproduk') {echo 'PRODUKTIVITAS CABANG';}
		elseif ($pagename==='agendaftar') {echo 'DAFTAR AGEN';}
		elseif ($pagename==='agenproduk') {echo 'PRODUKTIVITAS AGEN';}
		elseif ($pagename==='agendetail') {echo 'DETAIL AGEN';}
		elseif ($pagename==='closing') {echo 'CLOSING';}
		elseif ($pagename==='welcome') {echo 'Welcome, '; echo $login_session; echo "!";}?>
		</h1>
	</div>
</div>