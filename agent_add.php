<html>
	<body>


<?php
include('session.php');
?>

	<h1>Add Agent</h1>
	<form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>">
		
		Name: <input type="text" name="name" required><br>
		Phone Number : <input type="tel" name="phone" pattern="[0-9]+" required><br>
		Immediate Upline:
		<?php
			$agentSQL = "SELECT agent.Name, agent.Agent_ID from agent 
				WHERE status=1 AND agent.Agent_ID !=0";
		    $agentResult = mysqli_query($db, $agentSQL);
		    if ($agentResult->num_rows > 0) {
		    	echo "<select name='UplineID'>";
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
		Branch: 
		<?php 
			$branchSQL = "SELECT branch_id,Name FROM `branch` where status=1";
			$branchResult = mysqli_query($db, $branchSQL);
			if ($branchResult->num_rows > 0) {
		    	echo "<select name='BranchID'>";
			    while($row = $branchResult->fetch_assoc()) {
			        echo "<option value=".$row["branch_id"]."> ". $row["Name"] ." </option>"; 
			    }
			    echo "</select> <br>";
			}     
			else {//THIS SHOULD NOT HAPPEN
		    	echo "No branches found<br>";
			}
		?>

		<input type="submit" name="submit" value="Submit">
	</form>


<?php
	

	$name = $phone = $UplineID = $BranchID = "";
	$password = $passwordCon = "";
	if ($_SERVER["REQUEST_METHOD"] == "POST") {
		$name = test_input($_POST["name"]);
		$phone = $_POST["phone"];
		$UplineID = test_input($_POST["UplineID"]);
		$BranchID = test_input($_POST["BranchID"]);

		echo "<h2>Your Input:</h2>";
		if(isset($name))echo $name. "<br>";  
		if(isset($phone))echo $phone. "<br>";  
		if(isset($UplineID))echo $UplineID. "<br>";  
		if(isset($BranchID))echo $BranchID. "<br>";
		echo "<br>";

	  	if ($UplineID == "empty") {$UplineID = null;}

	  	if (!$db) {
	    	die("Connection failed: " . mysqli_connect_error());
		}
		else{
				$stmt = $db->prepare("
					INSERT INTO agent (Name, ImmediateUpline_ID, Status, PhoneNumber) 
					VALUES (?,?,1,?)");
				$stmt->bind_param('sis', $field2, $field3, $field4);

				$field2 = $name;
				$field3 = $UplineID;
				$field4 = $phone;

			if ($stmt->execute()) {
				$stmt->close();
			    echo "New agent created successfully <br>";
				
				$agentID =  mysqli_insert_id($db);//get last inserted AUTO_INCREMENT (which is agent ID)
				//Branch Employment Insertion
				$employmentSTMT = $db->prepare("
			    		INSERT INTO `agent_branch_employment`(`Agent_ID`, `Branch_ID`, `Started`, `End`) 
			    		VALUES (?,?,?,NULL)");
				$employmentSTMT->bind_param('iis', $f1, $f2, $f3);
				$f1 = $agentID;
				$f2 = $BranchID;
				$f3 = date("Y-m-d");
				if($employmentSTMT->execute()){
			    		$employmentSTMT->close();
			    		echo "Employment entry created successfully <br>";
		    	}else{
		    		$employmentSTMT->close();
		    		echo "Error: <br>" . mysqli_error($db);
		    	}

				//Recursive Insertion for downline relation
			    while($UplineID != NULL){
			    	$upSTMT = $db->prepare("
			    		INSERT INTO `agent_has_downline`(`Agent_ID`, `Downline_ID`) 
			    		VALUES (?,?)");
			    	$upSTMT->bind_param('ii' ,$Up, $Down);
			    	$Up = $UplineID;
			    	$Down = $agentID;

			    	
			    	if($upSTMT->execute()){
			    		$upSTMT->close();
			    		echo "Downline relation created successfully <br>";
			    	}else{
			    		$upSTMT->close();
			    		echo "Error: <br>" . mysqli_error($db);
			    	}

			    	$cAgentSQL = 
						    "SELECT agent.ImmediateUpline_ID FROM agent 
								WHERE agent.Agent_ID = " . $UplineID;
	    			$cAgentResult = mysqli_query($db, $cAgentSQL);
	    			$cAgentRow = $cAgentResult->fetch_assoc();
	    			$UplineID = $cAgentRow["ImmediateUpline_ID"];
			    }

			} else {
				$stmt->close();
			    echo "Error: <br>" . mysqli_error($db);
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
	</body>
</html> 