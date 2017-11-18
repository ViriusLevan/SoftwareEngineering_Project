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

		if(isset($_GET["name"])){
			?>
			<form method='POST' action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>">
			<?php
			echo "<input name='name' type='hidden' value=".$_GET["name"].">";
			echo "<input name='oldPercent' type='hidden' value=".$_GET["percent"].">";
			echo "<input name='percent' type='number' min='0' required>";
			echo "<button type='submit'>SUBMIT</button></form>";
		}
		if($_SERVER["REQUEST_METHOD"] == "POST"){
			if ($_POST["percent"] != $_POST["oldPercent"]) {
				$date = date('ymd');
				$newDate = new Datetime($date);
			    $newDate->modify('+1 day');
			    $newDateStr = $newDate->format('Ymd');

			    $PPUpdateSQL = $db->prepare("UPDATE `paypercentages` 
									SET `ValidityEnd`= ?  
									WHERE JobName = ?
									AND ValidityEnd IS NULL");
				$PPUpdateSQL->bind_param('ss',$f1,$f2);
				$f1 = $date;
				$f2 = $_POST["name"];
				if($PPUpdateSQL->execute()){
					$PPUpdateSQL->close();
					echo "Validity Sucessfully Ended";

			    	$PPInsertSQL = $db->prepare("INSERT INTO `paypercentages`
			    		(`JobName`, `Percentage`, `ValidityStart`, `ValidityEnd`) VALUES (?,?,?,NULL)");

					$PPInsertSQL->bind_param('sis',$f1,$f2,$f3);
					$f1 = $_POST["name"];
					$f2 = $_POST["percent"];
					$f3 = $newDateStr;

					if($PPInsertSQL->execute()){
						$PPInsertSQL->close();
						echo "New Percentage Sucessfully Inserted";
					}else{
						$PPInsertSQL->close();
						echo "Error: <br>" . mysqli_error($db);
					}

				}else{
					$PPUpdateSQL->close();
					echo "Error: <br>" . mysqli_error($db);
				}
			}
		}
		
		$PPSQL = 
			"SELECT `JobName` AS job,`Percentage` AS per
				FROM paypercentages 
				WHERE ValidityEnd IS NULL";
		$PPResult = mysqli_query($db, $PPSQL);

		if ($PPResult->num_rows > 0) {
            echo "<table>";
            echo "<tr> <th>JobName</th> <th>Percentage</th> <th>EDIT</th> </tr>";
            while($PPRow = $PPResult->fetch_assoc()) { 

              // output data of each row
              echo "<tr><td> " . $PPRow["job"]. " </td>"; 
              echo "<td> " . $PPRow["per"]. " </td>"; 
              echo "<td><a href='PayPercentages.php?name=".$PPRow["job"]."
              			&percent=".$PPRow["per"]."'>EDIT</a></td>";
            }
        } else {
            //SHOULD NEVER HAPPEN
            echo "No agents found";
        }
	?>
	</p>
 </body>
</html>