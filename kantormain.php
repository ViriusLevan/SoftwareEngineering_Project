<?php
	$pagename='cabangproduk';
	include('session.php');
?>
<html>
	<head>
		<?php include('htmlhead.php'); ?>
		<title>Cabang</title>
	</head>
	<body class="mainbody">
		<?php include('sidebar.php'); ?>
		<div class="content">
			<?php include('header.php'); 
				if(isset($_POST["addBName"])){
					//Branch Add
					$bAddSQL = $db->prepare("INSERT INTO `branch`
						(`President_ID`, `VicePresident_ID`, `status`, `Name`, `address`) 
						VALUES (?,?,1,?,?)");
					$bAddSQL->bind_param('iiss',$f1,$f2,$f3,$f4);
					if($_POST["addPID"]=="empty")
						$PID = NULL;
					else 
						$PID = $_POST["addPID"];
					if($_POST["addVPID"]=="empty")
						$VPID = NULL;
					else 
						$VPID = $_POST["addVPID"];
					$f1 = $PID;
					$f2 = $VPID;
					$f3 = $_POST["addBName"];
					$f4 = $_POST["addBAddress"];

					if($bAddSQL->execute()){
						$bAddSQL->close();
						// echo "Cabang berhasil ditambah";
					}else{
						$bAddSQL->close();
						echo "Error: <br>" . mysqli_error($db);
					}
				}
			?>
			<div class="maincontent">
				<div class="kantormainbtn">
					<button onclick="document.getElementById('tambah').style.display='block'" class="btn kantormaintambahbtn" data-toggle="modal" data-target="#exampleModal">TAMBAH</button>
					<a href="kantordaftar.php" class="btn kantormainprodukbtn">DAFTAR CABANG</a>
					<button onclick="printDiv('printableArea')" class="btn printbtn">CETAK</button>
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
				
				<div class="kantormaintabel" id="printableArea">
					<div class="kantormaintabelheader"><h4>Hasil Produktivitas Cabang</h4></div>
					<table class="table sortable" id="produktable">
						<tr>
							<th>Nama Cabang</th>
							<th>Unit</th>
							<th>Total Closing</th>
							<th>Pendapatan Cabang dari Closing (Rp)</th>
						</tr>
						<?php
							
							$branchSQL = "SELECT branch.Name AS Name, IFNULL(UNIT,0) AS Unit, 
												IFNULL(Productivity,0) AS Productivity, IFNULL(Earnings,0) AS Earnings
												FROM branch LEFT OUTER JOIN
													(SELECT branch.Name, branch.branch_id,
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
														FROM agent_involved_in_closing, branch, agent,
																Agent_Branch_Employment, closing,
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
															AND agent_involved_in_closing.Closing_ID = closing.closing_ID
         													AND (agent_branch_employment.End IS NULL 
         														OR (DATEDIFF(agent_branch_employment.End, closing.Date)<=0 
         															AND DATEDIFF(agent_branch_employment.Started, closing.Date)>=0)
         													)
															AND Agent.Agent_ID != 0
															AND aCount.cID = agent_involved_in_closing.Closing_ID	
															GROUP BY branch.branch_id) pro
												ON pro.branch_id = branch.branch_id
												WHERE branch.status = 1";
							if(isset($_POST["bfrDate"]) && isset($_POST["aftDate"])) {
								if($_POST["bfrDate"]!= NULL && $_POST["aftDate"]!= NULL){
									$bfrDate = $_POST["bfrDate"];
									$aftDate = $_POST["aftDate"];
									$bfrDate =  str_replace("-","", $bfrDate); //remove "-" from date
									$aftDate =  str_replace("-","", $aftDate);
									$branchSQL = "SELECT branch.Name AS Name, IFNULL(UNIT,0) AS Unit, 
													IFNULL(Productivity,0) AS Productivity, IFNULL(Earnings,0) AS Earnings
													FROM branch LEFT OUTER JOIN
														(SELECT branch.Name, branch.branch_id,
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
															FROM agent_involved_in_closing, branch, agent,
																	Agent_Branch_Employment, closing,
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
																AND agent_involved_in_closing.Closing_ID = closing.closing_ID
	         		AND (agent_branch_employment.End IS NULL OR agent_branch_employment.End >= closing.Date)
																AND Agent.Agent_ID != 0
																AND aCount.cID = agent_involved_in_closing.Closing_ID
																AND closing.closing_ID = agent_involved_in_closing.Closing_ID
																AND DATEDIFF(closing.Date,$bfrDate)>=0
																AND DATEDIFF(closing.Date,$aftDate)<=0
																GROUP BY branch.branch_id) pro
													ON pro.branch_id = branch.branch_id
													WHERE branch.status = 1";
								}
							}
							$result = mysqli_query($db,$branchSQL);
								if ($result->num_rows > 0) {//Table data printing
									while($row = $result->fetch_assoc()) {
										echo "<tr>";
											echo "<td>". $row["Name"] ."</td>";
											echo "<td>". $row["Unit"] ."</td>";
											echo "<td>". $row["Productivity"] ."</td>";
											echo '<td class="pull-right">'. number_format($row["Earnings"]) ."</td>";
										echo "</tr>";
									}
								} else {
									// echo "Tidak ada hasil";
								}
						?>
					</table>
				</div>
				<script>
			function sortTable(n) {
			  var table, rows, switching, i,a, b, x, y, shouldSwitch, dir, switchcount = 0;
			  table = document.getElementById("produktable");
			  switching = true;
			  dir = "asc"; 
			  while (switching) {
			    switching = false;
			    rows = table.getElementsByTagName("TR");
			    for (i = 1; i < (rows.length - 1); i++) {
			      shouldSwitch = false;
			      x = rows[i].getElementsByTagName("TD")[n];
			      y = rows[i + 1].getElementsByTagName("TD")[n];
			      if (n==0) {
					a=x.innerHTML;
					b=y.innerHTML;
			      } else{
			      	a = parseInt(x.innerHTML);
			      	b = parseInt(y.innerHTML);
			      }
			      
			      if (dir == "asc") {
			        if (a> b) {
			          shouldSwitch= true;
			          break;
			        }
			      } else if (dir == "desc") {
			        if (a< b) {
			          shouldSwitch= true;
			          break;
			        }
			      }
			    }
			    if (shouldSwitch) {
			      rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
			      switching = true;
			      switchcount ++; 
			    } else {
			      if (switchcount == 0 && dir == "asc") {
			        dir = "desc";
			        switching = true;
			      }
			    }
			  }
			}
			</script>
				<div id="tambah" class="w3-modal" data-backdrop="">
					<div class="w3-modal-content w3-animate-top w3-card-4">
						<header class="w3-container modalheader">
							<span onclick="document.getElementById('tambah').style.display='none'"
							class="w3-button w3-display-topright">&times;</span>
							<h2>TAMBAH CABANG BARU</h2>
						</header>
						<div class="w3-container">
							<form method="POST" action=<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>
								onsubmit="return validateAddForm()" name="addForm">
								<h5 class="kantormainformlabel">Nama Cabang</h5>
								<input name="addBName" class="form-control" type="text" placeholder="Masukkan nama kantor">
								<h5 class="kantormainformlabel">Alamat Cabang</h5>
								<input name="addBAddress" class="form-control" type="text" placeholder="Masukkan alamat kantor">
								<br>
								<div class="row">
									<div class="col">
										<h5 class="kantormainformlabel">Kepala Cabang</h5>
										<?php
											$sql = "SELECT agent.Name, agent.Agent_ID
														from agent left join branch
														ON agent.Agent_ID != branch.President_ID
														AND agent.Agent_ID != branch.VicePresident_ID
														WHERE agent.Agent_ID != 0";
											$result = mysqli_query($db, $sql);
											if ($result->num_rows > 0) {
												echo "<select name='addPID' class='form-control kantormainselectvpv'
													onchange='optionDisabling()'>";
												echo "<option value='empty'> -Tidak Ada- </option>";
												while($row = $result->fetch_assoc()) {
													echo "<option value=".$row["Agent_ID"]."> ". $row["Name"] ." </option>";
												}
												echo "</select> <br>";
											}
											else {
												echo "Tidak ada agen yang dapat dipilih <br>";
											}
										?>
									</div>
									<div class="col">
										<h5 class="kantormainformlabel">Wakil Kepala Cabang</h5>
										<?php
											$result = mysqli_query($db, $sql);
											if ($result->num_rows > 0) {
												echo "<select name='addVPID' class='form-control kantormainselectvpv'
													onchange='optionDisabling()'>";
												echo "<option value='empty'> -Tidak Ada- </option>";
												while($row = $result->fetch_assoc()) {
													echo "<option value=".$row["Agent_ID"]."> ". $row["Name"] ." </option>";
												}
												echo "</select>";
											}
											else {
												echo "Tidak ada agen yang dapat dipilih <br>";
											}
										?>
									</div>
								</div>
								<br>
								<div class="modalfooter">
									<button type="submit" class="btn modalleftbtn" onclick="document.getElementById('tambah').style.display='none'">BATAL</button>
									<button type="submit" class="btn modalrightbtn">SIMPAN</button>
								</div>
							</form>
							<script type="text/javascript">
								function validateAddForm() {//Exactly what it says
								    var x = document.forms["addForm"]["addVPID"];
								    var y = document.forms["addForm"]["addPID"];
								    if (x.selectedIndex != 0 && y.selectedIndex == 0) {
								        alert("Kantor tidak dapat memiliki Wakil Kepala Cabang sebelum memiliki Kepala Cabang");
								        return false;
								    }
								}
								function optionDisabling(){//Disabling options on other selects based on what is selected
								var select = document.getElementsByClassName("form-control kantormainselectvpv");
								var selections = [];

								for (var i = 0; i<2; i++) {
									if(select[i].disabled == false && 
										select[i].selectedIndex != 0){
										selections.push(select[i].value);
									}
								}

								for (var i = 0; i<2; i++) {
									var opt = select[i].getElementsByTagName("option");
									for (var j = 0; j < opt.length; j++) {
										if(selections.indexOf(opt[j].value) == -1){
										//not found on selections
											opt[j].disabled = false;
										}else if(opt[j].value == select[i].value){
										//the currently selected option for this select box
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
		<?php
			$name = $PresidentID = $VicePresidentID = "";
			if ($_SERVER["REQUEST_METHOD"] == "POST") {
				$name = test_input($_POST["name"]);
				$PresidentID = test_input($_POST["PresidentID"]);
				$VicePresidentID = test_input($_POST["VicePresidentID"]);
				echo "<h2>Your Input:</h2>";
				if(isset($name))echo $name. "<br>";
				if(isset($PresidentID))echo $PresidentID. "<br>";
				if(isset($VicePresidentID))echo $VicePresidentID. "<br>";
				echo "<br>";
				$check = $db->prepare("SELECT Branch_ID FROM branch where status = 1 AND name = ?");
				$check->bind_param('s', $field1);
				$field1 = $name;
				$check->execute();
				$lines = $check->num_rows;
				$duplicate = false;
				$samePerson = false;
				$vpBastard = false;
				if ($PresidentID == "empty") {$PresidentID = null;}
				if ($VicePresidentID == "empty") {$VicePresidentID = null;}
		//ERROR CHECKS
				if($PresidentID == NULL && $VicePresidentID != NULL){
					$vpBastard=true;
					// echo "Kantor tidak dapat memiliki Wakil Kepala Cabang sebelum memiliki Kepala Cabang";
				}
				if(count($lines) > 0) {
					$duplicate = true;
					// echo "Cabang dengan nama tersebut sudah terdaftar <br>";
				}
				if($PresidentID == $VicePresidentID && $PresidentID != NULL){
					$samePerson = true;
					// echo "Kepala Cabang dan Wakil Kepala Cabang tidak boleh orang yang sama<br>";
				}
				$check->close();
				if(!$samePerson && !$duplicate && !$vpBastard){
					if (!$db) {
						die("Connection failed: " . mysqli_connect_error());
					}
					else{
						$stmt = $db->prepare("INSERT INTO branch (President_ID, VicePresident_ID, Name, status)
								VALUES (?, ?, ?, 1)");
						$stmt->bind_param('iis', $field1, $field2, $field3);
						$field1 = $PresidentID;
						$field2 = $VicePresidentID;
						$field3 = $name;
						if ($stmt->execute()) {
							$stmt->close();
							// echo "Cabang berhasil ditambah";
						} else {
							$stmt->close();
							echo "Error: <br>" . mysqli_error($db);
						}
					}
				}
			}
			function test_input($data) {
			$data = trim($data);
			$data = stripslashes($data);
			$data = htmlspecialchars($data);
			return $data;
			}
		?>

		<script>
			document.getElementById("startDate").addEventListener("change", function() {
			    var input = this.value;
			    var startDateEntered = new Date(input);
				document.getElementById("endDate").setAttribute("min", input);
			});
			document.getElementById("endDate").addEventListener("change", function() {
			    var input = this.value;
			    var endDateEntered = new Date(input);
				document.getElementById("startDate").setAttribute("max", input);
			});

			function printDiv(divName) {
			    var printContents = document.getElementById(divName).innerHTML;
			    var originalContents = document.body.innerHTML;

			    document.body.innerHTML = printContents;

			    window.print();

			    document.body.innerHTML = originalContents;
			}
		</script>
	</body>
</html>