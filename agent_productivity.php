<?php
	$pagename='agen';
	include('session.php');
?>
<html>
	<head>
		<?php include('htmlhead.php'); ?>
		<title>Agen</title>
	</head>
	<body class="mainbody">
		<?php include('sidebar.php'); ?>
		<div class="content">
			<?php include('header.php'); ?>
			<div class="maincontent">
				<div class="kantormainbtn">
					<button onclick="document.getElementById('tambah').style.display='block'" class="btn kantormaintambahbtn" data-toggle="modal" data-target="#exampleModal">TAMBAH</button>
					<a href="agenmain.php" class="btn kantormainprodukbtn">DAFTAR AGEN</a>
				</div>
				<br>
				<div class="kantormainfilter">
					<h2>Filter</h2>
					<form action=<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?> method="post">
						<h5 class="kantormainformlabel">Tanggal&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:</h5>
						<input type="date" name="bfrDate"
							id="startDate" class="form-control kantormainselect">
						<h5 class="kantormainformlabel">s/d</h5>
						<input type="date" name="aftDate"
							id="endDate" class="form-control kantormainselect">
						<input type="submit" name="submit" class="btn kantormainfiltersubmit">
					</form>
				</div>
				<br>
				<div class="kantormaintabel">
					<div class="kantormaintabelheader"><h4>Hasil Produktivitas Agen</h4></div>
					<table class="table" id="produktable">
						<tr>
							<th>Nama Agen</th>
							<th>Unit</th>
							<th>Total Closing</th>
							<th>Pendapatan agen dari Closing (Rp)</th>
						</tr>
					
				
	<?php 
		$AgentProSQL = 
		"SELECT agent.Name AS Name, IFNULL(UNIT, 0) AS Unit, 
			IFNULL(Productivity,0) AS Pro, IFNULL(Earnings,0) AS Earn 
			FROM agent LEFT OUTER JOIN
				(SELECT agent.Name, agent.Agent_ID,
					SUM(CASE 
		             WHEN aCount.nAgents = 1 THEN 1
		             WHEN aCount.nAgents = 2 THEN 0.5
		             WHEN agent_involved_in_closing.workedAs = 1
		             	&& aCount.nAgents = 3 THEN 0.5
		             WHEN agent_involved_in_closing.workedAs IN (7,13)
		             	&& aCount.nAgents = 3 THEN 0.25
		             WHEN aCount.nAgents = 4 THEN 0.25
		            END) AS Unit,
					COUNT(DISTINCT agent_involved_in_closing.Closing_ID) AS Productivity,
					SUM(agent_involved_in_closing.earning) AS Earnings
					FROM agent_involved_in_closing, branch, agent, Agent_Branch_Employment,
		                    (
		                    	SELECT closing.closing_ID AS cID, 
		                        	COUNT(agent_involved_in_closing.Agent_ID) AS nAgents
		                        FROM agent_involved_in_closing, closing
		                        WHERE agent_involved_in_closing.Closing_ID = closing.closing_ID
		                            AND agent_involved_in_closing.workedAs IN (1,7,13,19)
		                        GROUP BY closing.closing_ID
		                    )aCount
					WHERE agent_involved_in_closing.workedAs IN (1,7,13,19)
						AND agent_involved_in_closing.Agent_ID = agent.Agent_ID
						AND agent.Agent_ID = Agent_Branch_Employment.Agent_ID
						AND Agent_Branch_Employment.Branch_ID = branch.Branch_ID
						AND Agent.Agent_ID != 0
						AND aCount.cID = agent_involved_in_closing.Closing_ID
						GROUP BY agent.Agent_ID) pro
			ON pro.Agent_ID = agent.Agent_ID
			WHERE agent.Agent_ID !=0";
			$AgentProResult = mysqli_query($db, $AgentProSQL);

			if ($AgentProResult->num_rows > 0) {
	            while($AgentProRow = $AgentProResult->fetch_assoc()) { 

	              // output data of each row
	              echo "<tr><td> " . $AgentProRow["Name"]. " </td>"; 
	              echo "<td> " . $AgentProRow["Unit"]. " </td>"; 
	              echo "<td> " . $AgentProRow["Pro"] . " </td>"; 
	              echo "<td> " .  $AgentProRow["Earn"] . " </td>";

	            }
            } else {
	            //SHOULD NEVER HAPPEN
	            echo "No agents found";
            }
	?>
					</table>
				</div>
			</div>
			<div id="tambah" class="w3-modal" data-backdrop="">
					<div class="w3-modal-content w3-animate-top w3-card-4">
						<header class="w3-container modalheader">
							<span onclick="document.getElementById('tambah').style.display='none'"
							class="w3-button w3-display-topright">&times;</span>
							<h2>TAMBAH AGEN BARU</h2><!--Agent_add.php -->
						</header>
						<div class="w3-container">
							<form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>">
								<h5 class="kantormainformlabel">Nama Agen</h5>
								<input class="form-control" type="text" placeholder="Masukkan nama agen"
									name="name" required>
								<h5 class="kantormainformlabel">Nomor Telepon</h5>
								<input class="form-control" placeholder="Masukkan nomor telepon" 
									type="tel" name="phone" pattern="[0-9]+" required>
								<br>
								<div class="row">
									<div class="col">
										<h5 class="kantormainformlabel">Kantor</h5>
										<!-- <select name="kantor" class="form-control kantormainselectvpv">
											<option value="id">Nama Kantor</option>
										</select> -->
										<?php 
											$branchSQL = "SELECT branch_id,Name FROM `branch` where status=1";
											$branchResult = mysqli_query($db, $branchSQL);
											if ($branchResult->num_rows > 0) {
										    	echo "<select name='BranchID' class='form-control kantormainselectvpv'>";
											    while($row = $branchResult->fetch_assoc()) {
											        echo "<option value=".$row["branch_id"]."> ". $row["Name"] ." </option>"; 
											    }
											    echo "</select> <br>";
											}     
											else {//THIS SHOULD NOT HAPPEN
										    	echo "No branches found<br>";
											}
										?>
									</div>
									<div class="col">
										<h5 class="kantormainformlabel">Upline</h5>
										<!-- <select name="kantor" class='form-control kantormainselectvpv'>
											<option value="id">Nama Upline</option>
										</select> -->
										<?php
											$agentSQL = "SELECT agent.Name, agent.Agent_ID from agent 
												where status=1 AND Agent_ID != 0";
										    $agentResult = mysqli_query($db, $agentSQL);
										    if ($agentResult->num_rows > 0) {
										    	echo "<select name='UplineID' class='form-control kantormainselectvpv'>";
											    echo "<option value='empty'> Noone </option>";
											    while($agentRow = $agentResult->fetch_assoc()) {
											        echo "<option value=".$agentRow["Agent_ID"]."> ". $agentRow["Name"] ." </option>"; 
											    }
											    echo "</select> <br>";
											}     
											else {
										    	echo "No agents to assign to <br>";
											}
										?>
									</div>
								</div>
								<br>
								<div class="modalfooter">
									<button type="button" class="btn modalleftbtn" onclick="document.getElementById('tambah').style.display='none'">BATAL</button>
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