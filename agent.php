<?php 
	include('session.php');
	include('class_agent.php');
?>
<html>
    <head>
   <title> Agent List </title>
      <link type='text/css' rel='stylesheet' href='style.css'/>
 </head>
 <body>
      <p>
        <?php


        $sql = "SELECT * FROM agent where status = 1";
		    $result = mysqli_query($db,$sql);
		    if ($result->num_rows > 0) {
		    	echo "<table>";
		    	echo "<tr> <th>ID</th> <th>Name</th> <th>Phone</th> 
		    				<th>Upline</th> <th>Details</th> </tr>";
			    while($row = $result->fetch_assoc()) {//Output data
			        echo "<tr><td> " . $row["Agent_ID"]. " </td>"; 
              		echo "<td> " . $row["Name"]. " </td>"; 
			        echo "<td> " . $row["PhoneNumber"]. " </td>";
			        if($row["ImmediateUpline_ID"] == null){
			        	echo "<td>  Noone </td>"; 
			        }else{
			        	$IUSQL =
					    	"SELECT Name FROM agent WHERE Agent_ID=" . $row["ImmediateUpline_ID"];
	    				$IUResult = mysqli_query($db, $IUSQL);
	    				$IURow = $IUResult->fetch_assoc();
	    				$IU = $IURow["Name"];
			        	echo "<td> " . $IU . " </td>"; 
			    	}?>
			    	<td>
			    		<a class="btn btn-warning" href='agent_details.php?id=<?php echo $row["Agent_ID"]; ?>'><?php echo $row["Agent_ID"]; ?></a> 
			    	</td></tr>
			    	<?php
			    }
			    echo"</table>";
			} else {
		    	echo "0 results";
			}

			//if an agent is clicked, go to agent details, with the agent class in hand

        ?>

      </p>
    </body>
</html>