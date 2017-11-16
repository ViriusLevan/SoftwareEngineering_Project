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


        $sql = "SELECT agent.Agent_ID, agent.Name, agent.PhoneNumber,agent.ImmediateUpline_ID,
        			branch.status AS bStatus
        			FROM `agent`,agent_branch_employment,branch
					WHERE agent.Agent_ID = agent_branch_employment.Agent_ID
					AND agent_branch_employment.Branch_ID = branch.branch_id
					AND agent.status = 1";
		    $result = mysqli_query($db,$sql);
		    if ($result->num_rows > 0) {
		    	echo "<table>";
		    	echo "<tr> <th>ID</th> <th>Name</th> <th>Phone</th> 
		    				<th>Upline</th> <th>Details</th> </tr>";
			    while($row = $result->fetch_assoc()) {//Output data
			    	if($row["bStatus"] == 0)
			    		echo"<tr class = 'Unstationed'>"
			    	else
			    		echo "<tr>";
			        echo "<td> " . $row["Agent_ID"]. " </td>"; 
              		echo "<td> " . $row["Name"]. " </td>"; 
			        echo "<td> " . $row["PhoneNumber"]. " </td>";
			        if($row["ImmediateUpline_ID"] == null){
			        	echo "<td>  Noone </td>"; 
			        }else{
			        	$IUSQL =//Getting name of Immediate Upline
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

			//if an agent is clicked, go to agent details using id

        ?>

      </p>
    </body>
</html>