<!DOCTYPE html>
<html>
    <head>
   <title> Agent List </title>
      <link type='text/css' rel='stylesheet' href='style.css'/>
 </head>
 <body>
      <p>
        <?php
        	include('session.php');
          	class agent{
                public $Agent_ID, $branchID, $uplineID, $phone;
                public $downlines = [];
                
                public function __construct($Agent_ID, $branchID, $uplineID, $phone){
                  $this->Agent_ID=$Agent_ID;
                  $this->branchID=$branchID;
               		$this->uplineID=$uplineID;
               		$this->phone=$phone;
                }   
            }


            $sql = "SELECT * FROM agent where status = 1";
		    $result = mysqli_query($db,$sql);
		    if ($result->num_rows > 0) {
		    // output data of each row
			    while($row = $result->fetch_assoc()) {
			        echo "ID: " . $row["Agent_ID"]. "<br>"; 
			        echo "Phone: " . $row["PhoneNumber"]. "<br>";
			        if($row["ImmediateUpline_ID"] == null){
			        	echo "Upline: Noone <br>"; 
			        }else{
			        	echo "Upline: " . $row["ImmediateUpline_ID"]. "<br>"; 
			    	}
			        echo "-------------<br>";
			    }
			} else {
		    	echo "0 results";
			}

			//if an agent is clicked, go to agent details, with the agent class in hand

        ?>
      </p>
    </body>
</html>