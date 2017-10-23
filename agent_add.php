<html>
	<body>


<?php
include('session.php');
?>

	<h1>Add Branch</h1>
	<form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>">
		
		Name: <input type="text" name="name" required><br>
		Phone Number : <input type="tel" name="phone" pattern="[0-9]+" required><br>
		Password: <input type="password" name="password" required><br>
		<!-- Confirm Password: <input type="password" name="passwordCon" required><br> -->
		Immediate Upline:
		<?php
			$agentSQL = "SELECT agent.Name, agent.Agent_ID from agent where status=1";
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
		$password = test_input($_POST["password"]);
		// $passwordCon = test_input($_POST["passwordCon"]);

		echo "<h2>Your Input:</h2>";
		if(isset($name))echo $name. "<br>";  
		if(isset($phone))echo $phone. "<br>";  
		if(isset($UplineID))echo $UplineID. "<br>";  
		if(isset($BranchID))echo $BranchID. "<br>";
		if(isset($password))echo "-" .$password. "-<br>"; 
		// if(isset($passwordCon))echo "-" .$passwordCon. "-<br>"; 
		echo "<br>";

		

	  	// $differentPasswords = false;
	  	// $passCheck = strcmp($password, $passwordCon);

	  	if ($UplineID == "empty") {$UplineID = null;}


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
					$stmt = $db->prepare("INSERT INTO branch (President_ID, VicePresident_ID, Name, status)
							VALUES (?, ?, ?, ?, 1)");
					$stmt = $db->prepare("
						INSERT INTO agent (Branch_ID, Name, ImmediateUpline_ID, Status, PhoneNumber, Password) 
						VALUES (?,?,?,1,?,?)");
					$stmt->bind_param('isiss', $field1, $field2, $field3, $field4, $field5);

					$field1 = $BranchID;
					$field2 = $name;
					$field3 = $UplineID;
					$field4 = $phone;
					$field5 = $password;

				if ($stmt->execute()) {
					$stmt->close();
				    echo "New agent created successfully";
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