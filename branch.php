<?php 
	include('session.php');
	$sql = "SELECT * FROM branch where status = 1";
   $result = mysqli_query($db,$sql);
   if ($result->num_rows > 0) {
    // output data of each row
	    while($row = $result->fetch_assoc()) {
	        echo "ID: " . $row["branch_id"]. "<br>"; 
	        if($row["President_ID"] == null){
	        	echo "President: Noone <br>"; 
	        }else{
	        	echo "President: " . $row["President_ID"]. "<br>"; 
	    	}
	    	if($row["VicePresident_ID"] == null){
	        	echo " VP: Noone <br>";
	        }else{
	        	echo " VP: " . $row["VicePresident_ID"]. "<br>";
	    	}
	        echo "-------------<br>";
	    }
	} else {
    	echo "0 results";
	}

?>