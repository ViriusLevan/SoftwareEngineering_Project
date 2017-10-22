<html>
	<body>


<?php
include('session.php');
?>

	<h1>Add Branch</h1>
	<form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>">
		
		Name: <input type="text" name="name" required><br>
		President:
		<?php
			$sql = "SELECT agent.Name, agent.Agent_ID
					from agent left join branch 
					ON agent.Agent_ID != branch.President_ID 
					AND agent.Agent_ID != branch.VicePresident_ID";
		    $result = mysqli_query($db, $sql);
		    if ($result->num_rows > 0) {
		    	echo "<select name='PresidentID'>";
			    echo "<option value='empty'> Noone </option>";
			    while($row = $result->fetch_assoc()) {
			        echo "<option value=".$row["Agent_ID"]."> ". $row["Name"] ." </option>"; 
			    }
			    echo "</select> <br>";
			}     
			else {
		    	echo "No agents available for assignment <br>";
			}
		?>
		Vice President: 
		<?php 
			$result = mysqli_query($db, $sql);
			if ($result->num_rows > 0) {
		    	echo "<select name='VicePresidentID'>";
		    	echo "<option value='empty'> Noone </option>";
			    while($row = $result->fetch_assoc()) {
			        echo "<option value=".$row["Agent_ID"]."> ". $row["Name"] ." </option>"; 
			    }
			    echo "</select>";
			}     
			else {
		    	echo "No agents available for assignment <br>";
			}
		?>
		<input type="submit" name="submit" value="Submit">
	</form>


<?php
	echo "<h2>Your Input:</h2>";
	if(isset($name))echo $name. "<br>";  
	if(isset($PresidentID))echo $PresidentID. "<br>";  
	if(isset($VicePresidentID))echo $VicePresidentID. "<br>";  
	echo "<br>";


	$name = $PresidentID = $VicePresidentID = "";
	if ($_SERVER["REQUEST_METHOD"] == "POST") {
		$name = test_input($_POST["name"]);
		$PresidentID = test_input($_POST["PresidentID"]);
		$VicePresidentID = test_input($_POST["VicePresidentID"]);

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
	  		echo "A VP cannot be picked without a President";
	  	}
	  	if(count($lines) > 0) {
	  		$duplicate = true;
	  		echo "Branch with the same name already exists <br>";
	  	} 

		if($PresidentID == $VicePresidentID && $PresidentID != NULL){
			$samePerson = true;
			echo "President and Vice President cannot be the same person <br>";
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
				    echo "New branch created successfully";
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
	</body>
</html> 