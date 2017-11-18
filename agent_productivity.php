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
		$AgentProSQL = 
		"SELECT agent.Name AS Name, IFNULL(UNIT, 0) AS Unit, 
			IFNULL(Productivity,0) AS Pro, IFNULL(Earnings,0) AS Earn 
			FROM agent LEFT OUTER JOIN
				(SELECT agent.Name, agent.Agent_ID,
					SUM(CASE 
		             WHEN aCount.nAgents = 1 THEN 1
		             WHEN aCount.nAgents = 2 THEN 0.5
		             WHEN agent_involved_in_closing.workedAs = 1
		             	&& aCount.nAgents = 3 THEN 0.5
		             WHEN agent_involved_in_closing.workedAs IN (7,13)
		             	&& aCount.nAgents = 3 THEN 0.25
		             WHEN aCount.nAgents = 4 THEN 0.25
		            END) AS Unit,
					COUNT(DISTINCT agent_involved_in_closing.Closing_ID) AS Productivity,
					SUM(agent_involved_in_closing.earning) AS Earnings
					FROM agent_involved_in_closing, branch, agent, Agent_Branch_Employment,
		                    (
		                    	SELECT closing.closing_ID AS cID, 
		                        	COUNT(agent_involved_in_closing.Agent_ID) AS nAgents
		                        FROM agent_involved_in_closing, closing
		                        WHERE agent_involved_in_closing.Closing_ID = closing.closing_ID
		                            AND agent_involved_in_closing.workedAs IN (1,7,13,19)
		                        GROUP BY closing.closing_ID
		                    )aCount
					WHERE agent_involved_in_closing.workedAs IN (1,7,13,19)
						AND agent_involved_in_closing.Agent_ID = agent.Agent_ID
						AND agent.Agent_ID = Agent_Branch_Employment.Agent_ID
						AND Agent_Branch_Employment.Branch_ID = branch.Branch_ID
						AND Agent.Agent_ID != 0
						AND aCount.cID = agent_involved_in_closing.Closing_ID
						GROUP BY agent.Agent_ID) pro
			ON pro.Agent_ID = agent.Agent_ID";
			$AgentProResult = mysqli_query($db, $AgentProSQL);

			if ($AgentProResult->num_rows > 0) {
	            echo "<table>";
	            echo "<tr> <th>Name</th> <th>Unit</th> <th>Productivity</th> <th>Earnings</th> </tr>";
	            while($AgentProRow = $AgentProResult->fetch_assoc()) { 

	              // output data of each row
	              echo "<tr><td> " . $AgentProRow["Name"]. " </td>"; 
	              echo "<td> " . $AgentProRow["Unit"]. " </td>"; 
	              echo "<td> " . $AgentProRow["Pro"] . " </td>"; 
	              echo "<td> " .  $AgentProRow["Earn"] . " </td>";

	            }
            } else {
	            //SHOULD NEVER HAPPEN
	            echo "No agents found";
            }
	?>
	</p>
 </body>
</html>