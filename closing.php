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
                public $name, $branchID, $uplineID, $phone;
                
                public function __construct($name, $branchID, $uplineID, $phone){
                    $this->name=$name;
                    $this->branchID=$branchID;
               		$this->uplineID=$uplineID;
               		$this->phone=$phone;
                }   
                
            }

            $sql = "SELECT * FROM closing";
    		    $result = mysqli_query($db,$sql);
    		    if ($result->num_rows > 0) {
    		    // output data of each row
    			    while($row = $result->fetch_assoc()) {
  			        echo "ID: " . $row["Closing_ID"]. "<br>"; 
  			        echo "Date: " . $row["Date"]. "<br>";
  			        echo "Price: " . $row["Price"]. "<br>"; 
                echo "Address: " . $row["Address"]. "<br>";
  			        echo "-------------<br>";
    			    }
    			  } else {
    		    	echo "0 results";
    			  }
        ?>
      </p>
    </body>
</html>