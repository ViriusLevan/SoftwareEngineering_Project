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
		<div class="content">
			<?php include('header.php'); ?>
		</div>
	</body>
</html>