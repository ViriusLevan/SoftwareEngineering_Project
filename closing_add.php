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
		<select name="nAgents">
			<option value="1">1</option>
			<option value="2">2</option>
			<option value="3">3</option>
			<option value="4">4</option>
		</select>
		<!-- JAVASCRIPT NEEDED -->
		Agent 1
		<?php
			$agentSQL = "SELECT agent.Name, agent.Agent_ID from agent where status=1";
		    $agentResult = mysqli_query($db, $agentSQL);
		    if ($agentResult->num_rows > 0) {
		    	echo "<select name='agent1ID'>";
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


<?php
	

	$name = $phone = $UplineID = $BranchID = "";
	$password = $passwordCon = "";
	if ($_SERVER["REQUEST_METHOD"] == "POST") {
		$address = test_input($_POST["address"]);
		$date = $_POST["date"];
		$price = test_input($_POST["price"]);
		$nAgents = test_input($_POST["nAgents"]);
		$agent1ID = test_input($_POST["agent1ID"]);
		// $passwordCon = test_input($_POST["passwordCon"]);

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
			echo $agent1ID. "<br>";
			$agents = $agent2ID;
		}
		if(isset($agent3ID)){
			echo $agent1ID. "<br>";
			$agents = $agent3ID;
		}
		if(isset($agent4ID)){
			echo $agent1ID. "<br>";
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

				if ($stmt->execute()) {
					$stmt->close();
				    echo "New closing created successfully";

				    //sql to get closing_id

				    if($nAgents == 1){
				    	$p = 100;
				    }else if($nAgents == 2){
				    	$p = 50;
				    }else if($nAgents == 3){
				    	$p = 25;
				    }else {
				    	$p = 25;
				    }

				    for ($i = 0; $i < $nAgents; $i++) {
					    echo $i;
					    $stmti = $db->prepare("
					    INSERT INTO `agent_has_closing`(`Agent_ID`, `Closing_ID`, `Percentage`) 
					    VALUES (?,?,?)");
	    				$stmti->bind_param('iii', $field1, $field2, $field3);
	    				if($i == 0 && $nAgents == 3){
		    				$f1 = $agents[$i];
							$f3 = $p;
	    				}else{
		    				$f1 = $agents[$i];
							$f3 = $p;
	    				}

						$f2 = $;//closing id

						if($stmti->execute()){
							$stmti->close();
						    echo "Agent has closing created successfully";
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
?>
	</body>
</html> 