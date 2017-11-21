<?php $pagename='welcome';
include('session.php');
?>
<html>
<head>
	<?php include('htmlhead.php'); ?>
	<title>Welcome</title>
</head>
<body class="mainbody" onload="agentOptions(1)">
	<?php include('sidebar.php'); ?>
	<div class="content" style="background: rgba(255,255,255,1);">
		<?php include('header.php'); ?>
		<div class="maincontent text-center">
			<img src="img/mainlogo-inverse.png" alt="" style="max-width: 350px;">
		</div>
	</div>
</body>
</html>