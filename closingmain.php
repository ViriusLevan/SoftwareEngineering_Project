<?php 
	$pagename='closing'; 
	include('session.php');
?>
<html>
	<head>
		<?php include('htmlhead.php'); ?>
		<title>Closing</title>
	</head>
	<body class="mainbody" onload="agentOptions(1)">
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
						<!-- <select name="bfrbulan" class="form-control propertyselect">
							<option value="agenA">Agen A</option>
						</select> -->
						<?php
							$sql = "SELECT * FROM agent where status = 1 AND Agent_ID!=0";
						    $result = mysqli_query($db, $sql);
						    if ($result->num_rows > 0) {
						    	echo "<select name='AgentName'>";
							    echo "<option value='empty'> Noone </option>";
							    while($row = $result->fetch_assoc()) {
							        echo "<option value=".$row["Name"]."> ". $row["Name"] ." </option>"; 
							    }
							    echo "</select> <br>";
							}     
							else {
						    	echo "No agents available<br>";
							}
						?>
						<!-- JS Not implemented YET -->
						<br>
					</form>
				</div>
				<br>
				<div class="kantormaintabel">
					<div class="kantormaintabelheader"><h4>Hasil Closing</h4></div>
					<table class="table">
						<tr>
							<th>Alamat</th>
							<th>Harga (Rp)</th>
							<th>Tanggal</th>
						</tr>
						<?php
							$closingSQL = "SELECT * FROM closing";
			    		    $closingResult = mysqli_query($db,$closingSQL);
			    		    if ($closingResult->num_rows > 0) {
			    			    while($row = $closingResult->fetch_assoc()) {
			            // output data of each row
			  			        echo "<tr><td> " . $row["Address"]. " </td>"; 
			  			        echo "<td> " . $row["Price"]. " </td>"; 
			                echo "<td> " . $row["Date"]. " </td>";
			  			        
			                ?> 
			                <td>
			                  <a class="btn btn-warning" 
			                  href='closing_agents.php?id=<?php echo $row["closing_ID"]; ?>'>Details</a> 
			                </td></tr>
			                <?php
			    			    }
			    			  } else {
			    		    	echo "No closing found";
			    			  }
				        ?>
					</table>
				</div>
				<div id="tambah" class="w3-modal" data-backdrop="">
					<div class="w3-modal-content w3-animate-top w3-card-4">
						<header class="w3-container modalheader">
							<span onclick="document.getElementById('tambah').style.display='none'"
							class="w3-button w3-display-topright">&times;</span>
							<h2>TAMBAH CLOSING BARU</h2>
						</header>
						<div class="w3-container">
							<form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>">
								<h5 class="kantormainformlabel">Alamat</h5>
								<input type="text" name="address" required><br>
								<h5 class="kantormainformlabel">Tanggal : </h5>
								<input type="date" name="date" required><br>
								<h5 class="kantormainformlabel">Harga (Rp)</h5>
								<input class="form-control" placeholder="Masukkan harga properti"
									type="number" name="price" min=100000.00 step="any" required>
								<br>
								<h5 class="kantormainformlabel">Number of Agents : </h5>
								<select name="nAgents" id="nAgents" onchange="agentOptions(this.value)">
									<option value="1">1</option>
									<option value="2">2</option>
									<option value="3">3</option>
									<option value="4">4</option>
								</select><br>
								<h5 class="kantormainformlabel">Agent 1 (Main Agent)</h5>
								<?php
									$agentSQL = "SELECT agent.Name, agent.Agent_ID from agent where status=1 AND Agent_ID != 0";
								    $agentResult = mysqli_query($db, $agentSQL);
								    if ($agentResult->num_rows > 0) {
								    	echo "<select name='agent1ID' id='agent1Select' 
								    		onchange='optionDisabling()' class='agentSelection' required>";
									    while($agentRow = $agentResult->fetch_assoc()) {
									        echo "<option value=".$agentRow["Agent_ID"]."> ". $agentRow["Name"] ." </option>"; 
									    }
									    echo "</select> <br>";
									}     
									else {
								    	echo "No agents to assign to <br>";
									}
								?>

								<h5 class="kantormainformlabel">Agent 2</h5>
								<?php
								    $agentResult = mysqli_query($db, $agentSQL);
								    if ($agentResult->num_rows > 0) {
								    	echo "<select name='agent2ID' id='agent2Select' 
								    		onchange='optionDisabling()' class='agentSelection' required>";
									    while($agentRow = $agentResult->fetch_assoc()) {
									        echo "<option value=".$agentRow["Agent_ID"]."> ". $agentRow["Name"] ." </option>"; 
									    }
									    echo "</select> <br>";
									}     
									else {
								    	echo "No agents to assign to <br>";
									}
								?>

								<h5 class="kantormainformlabel">Agent 3</h5>
								<?php
								    $agentResult = mysqli_query($db, $agentSQL);
								    if ($agentResult->num_rows > 0) {
								    	echo "<select name='agent3ID' id='agent3Select' 
								    		onchange='optionDisabling()' class='agentSelection' required>";
									    while($agentRow = $agentResult->fetch_assoc()) {
									        echo "<option value=".$agentRow["Agent_ID"]."> ". $agentRow["Name"] ." </option>"; 
									    }
									    echo "</select> <br>";
									}     
									else {
								    	echo "No agents to assign to <br>";
									}
								?>

								<h5 class="kantormainformlabel">Agent 4</h5>
								<?php
								    $agentResult = mysqli_query($db, $agentSQL);
								    if ($agentResult->num_rows > 0) {
								    	echo "<select name='agent4ID' id='agent4Select' 
								    		onchange='optionDisabling()' class='agentSelection' required>";
									    while($agentRow = $agentResult->fetch_assoc()) {
									        echo "<option value=".$agentRow["Agent_ID"]."> ". $agentRow["Name"] ." </option>"; 
									    }
									    echo "</select> <br>";
									}     
									else {
								    	echo "No agents to assign to <br>";
									}
								?>
								<br>
								<div class="modalfooter">
									<button type="submit" class="btn modalleftbtn" onclick="document.getElementById('tambah').style.display='none'">BATAL</button>
									<button type="submit" class="btn modalrightbtn">SIMPAN</button>
								</div>
							</form>
							<script type="text/javascript">
								function agentOptions(n){
							// Agent Select disabling
							// probably needs improvement
									if(n == 1){
										document.getElementById("agent2Select").disabled = true;
										document.getElementById("agent3Select").disabled = true;
										document.getElementById("agent4Select").disabled = true;
									}
									if(n == 2){
										document.getElementById("agent2Select").disabled = false;
										document.getElementById("agent3Select").disabled = true;
										document.getElementById("agent4Select").disabled = true;
									}if(n == 3){
										document.getElementById("agent2Select").disabled = false;
										document.getElementById("agent3Select").disabled = false;
										document.getElementById("agent4Select").disabled = true;
									}
									if(n == 4){
										document.getElementById("agent2Select").disabled = false;
										document.getElementById("agent3Select").disabled = false;
										document.getElementById("agent4Select").disabled = false;
									}
									optionDisabling();
								}

								function optionDisabling(){
									var select = document.getElementsByClassName("agentSelection");
									var selections = [];

									for (var i = 0; i < 4; i++) {
										if(select[i].disabled == false){
											selections.push(select[i].options[select[i].selectedIndex].value);
										}
									}

									for (var i = 0; i<4; i++) {
										var opt = select[i].getElementsByTagName("option");
										for (var j = 0; j < opt.length; j++) {
											if(selections.indexOf(opt[j].value) == -1){
											//not found on selections
												opt[j].disabled = false;
											}else if(opt[j].value == select[i].options[select[i].selectedIndex].value){
											//the currently selected option
												opt[j].disabled = false; 
											}else{//Found on selection and not the currently selected option
												opt[j].disabled = true;
											}
										}
									}
								}
							</script>
						</div>
					</div>
				</div>
			</div>
		</div>
	</body>
</html>