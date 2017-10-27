<html>
	<body>


<?php
include('session.php');
?>

	<h1>Add Closing</h1>
	<form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>">
		
		Address: <input type="text" name="address" required><br>
		Date : <input type="date" name="date" required><br>
		Price : <input type="number" name="price" required><br>
		Number of Agents : 
		<select name="nAgents" id="nAgents" onchange="agentOptions(this.value)">
			<option value="1">1</option>
			<option value="2">2</option>
			<option value="3">3</option>
			<option value="4">4</option>
		</select>
		<!-- JAVASCRIPT NEEDED -->
		Agent 1 (Main Agent)
		<?php
			$agentSQL = "SELECT agent.Name, agent.Agent_ID from agent where status=1";
		    $agentResult = mysqli_query($db, $agentSQL);
		    if ($agentResult->num_rows > 0) {
		    	echo "<select name='agent1ID' id='agent1Select' 
		    		onchange='agentOptions()' class='agentSelection' >";
			    while($agentRow = $agentResult->fetch_assoc()) {
			        echo "<option value=".$agentRow["Agent_ID"]."> ". $agentRow["Name"] ." </option>"; 
			    }
			    echo "</select> <br>";
			}     
			else {
		    	echo "No agents to assign to <br>";
			}
		?>

		Agent 2
		<?php
			$agentSQL = "SELECT agent.Name, agent.Agent_ID from agent where status=1";
		    $agentResult = mysqli_query($db, $agentSQL);
		    if ($agentResult->num_rows > 0) {
		    	echo "<select name='agent2ID' id='agent2Select' 
		    		onchange='agentOptions()' class='agentSelection'>";
			    while($agentRow = $agentResult->fetch_assoc()) {
			        echo "<option value=".$agentRow["Agent_ID"]."> ". $agentRow["Name"] ." </option>"; 
			    }
			    echo "</select> <br>";
			}     
			else {
		    	echo "No agents to assign to <br>";
			}
		?>

		Agent 3
		<?php
			$agentSQL = "SELECT agent.Name, agent.Agent_ID from agent where status=1";
		    $agentResult = mysqli_query($db, $agentSQL);
		    if ($agentResult->num_rows > 0) {
		    	echo "<select name='agent3ID' id='agent3Select' 
		    		onchange='agentOptions()' class='agentSelection'>";
			    while($agentRow = $agentResult->fetch_assoc()) {
			        echo "<option value=".$agentRow["Agent_ID"]."> ". $agentRow["Name"] ." </option>"; 
			    }
			    echo "</select> <br>";
			}     
			else {
		    	echo "No agents to assign to <br>";
			}
		?>

		Agent 4
		<?php
			$agentSQL = "SELECT agent.Name, agent.Agent_ID from agent where status=1";
		    $agentResult = mysqli_query($db, $agentSQL);
		    if ($agentResult->num_rows > 0) {
		    	echo "<select name='agent4ID' id='agent4Select' 
		    		onchange='agentOptions()' class='agentSelection'>";
			    while($agentRow = $agentResult->fetch_assoc()) {
			        echo "<option value=".$agentRow["Agent_ID"]."> ". $agentRow["Name"] ." </option>"; 
			    }
			    echo "</select> <br>";
			}     
			else {
		    	echo "No agents to assign to <br>";
			}
		?>

		<!-- JAVASCRIPT NEEDED -->

		<input type="submit" name="submit" value="Submit">
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
		if(n==2){
			document.getElementById("agent2Select").disabled = false;
			document.getElementById("agent3Select").disabled = true;
			document.getElementById("agent4Select").disabled = true;
		}if(n==3){
			document.getElementById("agent2Select").disabled = false;
			document.getElementById("agent3Select").disabled = false;
			document.getElementById("agent4Select").disabled = true;
		}
		if(n==4){
			document.getElementById("agent2Select").disabled = false;
			document.getElementById("agent3Select").disabled = false;
			document.getElementById("agent4Select").disabled = false;
		}
	}

	function optionDisabling(){
		var select = document.getElementsByClassName("agentSelection");
		var selections = [];

		for (var i = 0; i < 4; i++) {
			selections.push(select[i].options[select[i].selectedIndex].value);
		}

		for (var i = 0; i<4; i++) {
			var opt = select[i].getElementsByTagName("option");
			for (var j = 0; j < opt.length; j++) {
				if(selections.indexOf(opt[j].value) == -1){
				//not found on selections
					opt[j].disabled = true;
				}else if(opt[j].value != select[i].options[select[i].selectedIndex].value){
				//not the currently selected option
					opt[j].disabled = true; 
				}else{
					opt[j].disabled = false;
				}
			}
		}
	}
	</script>


<?php
	

	$address = $date = $price = $nAgents = $agent1ID = "";
	$password = $passwordCon = "";
	if ($_SERVER["REQUEST_METHOD"] == "POST") {
		$address = test_input($_POST["address"]);
		$date = $_POST["date"];
		$price = test_input($_POST["price"]);
		$nAgents = test_input($_POST["nAgents"]);
		$agent1ID = test_input($_POST["agent1ID"]);
		$agent1ID = test_input($_POST["agent2ID"]);
		$agent1ID = test_input($_POST["agent3ID"]);
		$agent1ID = test_input($_POST["agent4ID"]);

		$agents = [];

		echo "<h2>Your Input:</h2>";
		if(isset($address))echo $address. "<br>";  
		if(isset($date))echo $date. "<br>";  
		if(isset($price))echo $price. "<br>";  
		if(isset($nAgents))echo $nAgents. "<br>";
		if(isset($agent1ID)){
			echo $agent1ID. "<br>";
			$agents = $agent1ID;
		}
		if(isset($agent2ID)){
			echo $agent2ID. "<br>";
			$agents = $agent2ID;
		}
		if(isset($agent3ID)){
			echo $agent3ID. "<br>";
			$agents = $agent3ID;
		}
		if(isset($agent4ID)){
			echo $agent4ID. "<br>";
			$agents = $agent4ID;
		} 
		echo "<br>";


//ERROR CHECKS
/*	  	var_dump($passCheck, $password, $passwordCon);
	  	if($passCheck === 0){//HOW THE FUCK DOES THIS NOT WORK
	  		$differentPasswords = true;
	  		echo "The passwords you've entered did not match";
	  	}*/

	  	// if(!$differentPasswords){
		  	if (!$db) {
		    	die("Connection failed: " . mysqli_connect_error());
			}
			else{
				$stmt = $db->prepare("
						INSERT INTO closing (`Date`, `Price`, `Address`) 
						VALUES (?,?,?)");
				$stmt->bind_param('sis', $field1, $field2, $field3);

				$field1 = $date;
				$field2 = $price;
				$field3 = $address;

				if ($stmt->execute()) {//Closing Creation
					$stmt->close();
				    echo "New closing created successfully";

				    //sql to get closing_id
				    $lselect = "SELECT LAST_INSERT_ID();"
				    $cID = mysqli_query($db, $agentSQL);

				    $p = 0;
				    if($nAgents == 1){
				    	$p = 100;
				    }else if($nAgents == 2){
				    	$p = 50;
				    }else{//3 & 4 agents
				    	$p = 25;
				    }

				    for ($i = 0; $i < $nAgents; $i++) {//Primary involvement insertion
					    echo $i;
					    $stmti = $db->prepare("
					    INSERT INTO `agent_involved_in_closing`(`Agent_ID`, `Closing_ID`, `earning`, `workedAs`) 
					    VALUES (?,?,?,?)");
	    				$stmti->bind_param('iidi', $f1, $f2, $f3, $f4);
	    				
	    				$f1 = $agents[$i];
						$f2 = $cID;
	    				if($nAgents == 3){
	    					if($i == 0){
	    						$p == 50;
	    					}else{
	    						$p == 25;
	    					}
	    				}

						$f3 = $p*$price/100;
						$f4 = 6*$i + 1;

						if($stmti->execute()){
							$stmti->close();
						    echo "Primary Agent Involvement created successfully";

						    //Secondary involvement insertion
						    $cAgentSQL = 
							    "SELECT branch.President_ID,branch.VicePresident_ID, agent.ImmediateUpline_ID 
									FROM branch,agent 
									WHERE branch.branch_id = agent.Branch_ID 
										AND agent.Agent_ID " .$Agents[$i];
		    				$cAgentResult = mysqli_query($db, $cAgentSQL);
		    				$cAgentRow = $cAgentResult->fetch_assoc();

						    //President & Vice President
		    				$PresidentID  = $cAgentRow["President_ID"];
		    				$VicePresidentID  = $cAgentRow["VicePresident_ID"];
		    				$ImmediateUplineID = $cAgentRow["ImmediateUpline_ID"];

		    				if($PresidentID != null){
								$cPresPSQL = //Current President Percentage
								    "SELECT Percentage FROM `paypercentages` WHERE JobName = 'President'";
			    				$cPresPResult = mysqli_query($db, $cPresPSQL);
			    				$cPresPRow = $cAgentResult->fetch_assoc();
			    				$cPresP = $cPresPRow["Percentage"];

			    				secondaryInvolvementInsertion(
				    							$db, $PresidentID, $cID, $price, $p, $cPresP, $i, 2);
								/*$stmtis = $db->prepare("
								    INSERT INTO `agent_involved_in_closing`(`Agent_ID`, `Closing_ID`, `earning`, `workedAs`) 
								    VALUES (?,?,?,?)");
			    				$stmtis->bind_param('iidi', $f1, $f2, $f3, $f4);
			    				$f1 = $PresidentID;
			    				$f2 = $cID;
			    				$f3 = $price * $p * $cPresP / 100;
			    				$f4 = 6*$i + 2;
			    				if($stmtis->execute()){
								$stmtis->close();
							    	echo "Agent Involved In Closing (president) created successfully";
								}else{
									$stmtis->close();
									echo "Error: <br>" . mysqli_error($db);
								}*/
		    				}
		    				if($VicePresidentID != null){
		    					$cVPPSQL = //Current Vice President Percentage
							    	"SELECT Percentage FROM `paypercentages` WHERE JobName = 'Vice President'";
			    				$cVPPResult = mysqli_query($db, $cVPPSQL);
			    				$cVPPRow = $cAgentResult->fetch_assoc();
			    				$cVPP = $cPresPRow["Percentage"];

			    				secondaryInvolvementInsertion(
				    							$db, $VicePresidentID, $cID, $price, $p, $cVPP, $i, 3);
		    					/*$stmtis = $db->prepare("
								    INSERT INTO `agent_involved_in_closing`(`Agent_ID`, `Closing_ID`, `earning`, `workedAs`) 
								    VALUES (?,?,?,?)");
			    				$stmtis->bind_param('iidi', $f1, $f2, $f3, $f4);
			    				$f1 = $VicePresidentID;
			    				$f2 = $cID;
			    				$f3 = $price * $p * $cVPP / 100;
			    				$f4 = 6*$i + 3;
			    				if($stmtis->execute()){
								$stmtis->close();
							    	echo "Agent Involved In Closing (vice president) created successfully";
								}else{
									$stmtis->close();
									echo "Error: <br>" . mysqli_error($db);
								}*/
		    				}

		    				//Uplines
		    				
		    				if($ImmediateUplineID != null){//Upline 1
		    					if($ImmediateUplineID != $PresidentID 
		    						&& $ImmediateUplineID != $VicePresidentID){//Insert Agent Involved In Closing
		    						secondaryInvolvementInsertion(
				    							$db, $ImmediateUplineID, $cID, $price, $p, 7, $i, 4);
		    						/*$stmtis = $db->prepare("
									    INSERT INTO `agent_involved_in_closing`(`Agent_ID`, `Closing_ID`, `earning`, `workedAs`) 
									    VALUES (?,?,?,?)");
				    				$stmtis->bind_param('iidi', $f1, $f2, $f3, $f4);
				    				$f1 = $ImmediateUplineID;
				    				$f2 = $cID;
				    				$f3 = $price * $p * 7 / 100;
				    				$f4 = 6*$i + 4;
				    				if($stmtis->execute()){
									$stmtis->close();
								    	echo "Agent Involved In Closing (upline 1) created successfully";
									}else{
										$stmtis->close();
										echo "Error: <br>" . mysqli_error($db);
									}*/
		    					}
		    					//continue for 2nd upline
		    					$UP2IDSQL = //Upline 2 ID
							    	"SELECT ImmediateUpline_ID FROM agent WHERE Agent_ID=" . $ImmediateUplineID;
			    				$UP2IDResult = mysqli_query($db, $UP2IDSQL);
			    				$UP2IDRow = $UP2IDResult->fetch_assoc();
			    				$UP2ID = $UP2IDRow["ImmediateUplineID"];

			    				if($UP2ID != null){
			    					if($UP2ID != $PresidentID 
		    							&& $UP2ID != $VicePresidentID){
			    						secondaryInvolvementInsertion(
				    							$db, $UP2ID, $cID, $price, $p, 2, $i, 5;
			    						/*$stmtis2 = $db->prepare("
										    INSERT INTO `agent_involved_in_closing`(`Agent_ID`, `Closing_ID`, `earning`, `workedAs`) 
										    VALUES (?,?,?,?)");
					    				$stmtis2->bind_param('iidi', $f1, $f2, $f3, $f4);
					    				$f1 = $UP2ID;
					    				$f2 = $cID;
					    				$f3 = $price * $p * 2 / 100;
					    				$f4 = 6*$i + 5;
					    				if($stmtis2->execute()){
										$stmtis2->close();
									    	echo "Agent Involved In Closing (upline 2) created successfully";
										}else{
											$stmtis2->close();
											echo "Error: <br>" . mysqli_error($db);
										}*/
		    						}
		    						//continue for 3rd upline
									$UP3IDSQL = //Upline 2 ID
								    	"SELECT ImmediateUpline_ID FROM agent WHERE Agent_ID=" . $UP2ID;
				    				$UP3IDResult = mysqli_query($db, $UP3IDSQL);
				    				$UP3IDRow = $UP3IDResult->fetch_assoc();
				    				$UP3ID = $UP3IDRow["ImmediateUplineID"];

				    				if($UP3ID != null){
				    					if($UP3ID != $PresidentID 
		    								&& $UP3ID != $VicePresidentID){
				    						secondaryInvolvementInsertion(
				    							$db, $UP3ID, $cID, $price, $p, 1, $i, 6);
				    						/*$stmtis3 = $db->prepare("
											    INSERT INTO `agent_involved_in_closing`(`Agent_ID`, `Closing_ID`, `earning`, `workedAs`) 
											    VALUES (?,?,?,?)");
						    				$stmtis3->bind_param('iidi', $f1, $f2, $f3, $f4);
						    				$f1 = $UP3ID;
						    				$f2 = $cID;
						    				$f3 = $price * $p * 1 / 100;
						    				$f4 = 6*$i + 6;
						    				if($stmtis3->execute()){
											$stmtis3->close();
										    	echo "Agent Involved In Closing (upline 3) created successfully";
											}else{
												$stmtis3->close();
												echo "Error: <br>" . mysqli_error($db);
											}
*/		
		    							}
				    				}
			    				}
		    				}


						}else{
							$stmti->close();
							echo "Error: <br>" . mysqli_error($db);
						}
					}

				} else {
					$stmt->close();
				    echo "Error: <br>" . mysqli_error($db);
				}
			}
		// }

	}

	function test_input($data) {
	  $data = trim($data);
	  $data = stripslashes($data);
	  $data = htmlspecialchars($data);
	  return $data;
	} 

	function secondaryInvolvementInsertion($db, $Agent_ID, $Closing_ID, $price, $agentPercentage,
											$ownPercentage, $agentNumber, $workedAs){
		$insertion = $db->prepare("
		    INSERT INTO `agent_involved_in_closing`(`Agent_ID`, `Closing_ID`, `earning`, `workedAs`) 
		    VALUES (?,?,?,?)");
		$insertion->bind_param('iidi', $field1, $field2, $field3, $field4);
		$field1 = $Agent_ID;
		$field2 = $Closing_ID;
		$field3 = $price * $agentPercentage * $ownPercentage / 100;
		$field4 = 6*$agentNumber + $workedAs;
		if($insertion->execute()){
		$insertion->close();
	    	echo "Secondary Agent Involvement created successfully";
		}else{
			$insertion->close();
			echo "Error: <br>" . mysqli_error($db);
		}
	}
?>
	</body>
</html> 