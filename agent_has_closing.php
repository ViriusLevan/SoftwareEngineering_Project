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

            $idSQL = "SELECT Closing_ID from Agent_has_closing where Agent_ID = 1";//YOU KNOW WHAT TO DO
            $idResults = mysqli_query($db,$idSQL);

            if ($idResults->num_rows > 0) {
            // output data of each row
              while($idRow = $idResults->fetch_assoc()) {
                $closingSQL = "SELECT * FROM closing where Closing_ID = ". $idRow["Closing_ID"];
                $closingResults = mysqli_query($db,$closingSQL);
                if ($closingResults->num_rows > 0) {
                // output data of each row
                  while($closingRow = $closingResults->fetch_assoc()) {
                    echo "ID: " . $closingRow["Closing_ID"]. "<br>"; 
                    echo "Date: " . $closingRow["Date"]. "<br>";
                    echo "Price: " . $closingRow["Price"]. "<br>"; 
                    echo "Address: " . $closingRow["Address"]. "<br>";
                    echo "-------------<br>";
                  }
                } else {
                  echo "0 results";
                }
              }
            } else {
              //shud be impossible to reach
              echo "0 results";
            }
              
        ?>
      </p>
    </body>
</html>