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

            $sql = "SELECT * FROM closing";
    		    $result = mysqli_query($db,$sql);
    		    if ($result->num_rows > 0) {
              echo "<table>";
              echo "<tr> <th>ID</th> <th>Date</th> <th>Price</th> 
                <th>Address</th> <th>Agents Involved</th> </tr>";
    			    while($row = $result->fetch_assoc()) {
            // output data of each row
  			        echo "<tr><td> " . $row["closing_ID"]. " </td>"; 
  			        echo "<td> " . $row["Date"]. " </td>";
  			        echo "<td> " . $row["Price"]. " </td>"; 
                echo "<td> " . $row["Address"]. " </td>";
  			        
                ?> 
                <td>
                  <a class="btn btn-warning" href='closing_agents.php?id=<?php echo $row["closing_ID"]; ?>'>Details</a> 
                </td></tr>
                <?php
    			    }
    			  } else {
    		    	echo "No closing found";
    			  }
        ?>
      </p>
    </body>
</html>