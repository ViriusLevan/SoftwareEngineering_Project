<div class="header">
	<div class="pagetitle">
		<h1>
		<?php
		if ($pagename==='kantor') {echo 'KANTOR';}
		elseif ($pagename==='agen') {echo 'AGEN';}
		elseif ($pagename==='properti') {echo 'PROPERTI';}
		elseif ($pagename==='closing') {echo 'CLOSING';}?>
		</h1>
	</div>
	<div class="headersearchform">
		<form action="">
			<input type="text" class="form-control searchform" placeholder="Cari...">
			<button type="submit" class="btn searchbtn"><i class="fa fa-search" aria-hidden="true"></i></button>
		</form>
	</div>
</div>