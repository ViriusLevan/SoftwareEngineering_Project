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

            $pass = $_GET["id"];

            $closingSQL = "SELECT * FROM  Agent_involved_in_closing where Agent_ID = " . $pass;
            $closingResults = mysqli_query($db,$closingSQL);
            if ($closingResults->num_rows > 0) {
            // output data of each row
              while($closingRow = $closingResults->fetch_assoc()) {
                $workedAs = setWorkedAs($closingRow["workedAs"]);
                echo "Closing ID: " . $closingRow["Closing_ID"]. "<br>"; 
                echo "Earned    : " . $closingRow["earning"]. "<br>";
                echo "Worked As : " . $workedAs. "<br>"; 
                echo "-------------<br>";
              }
            } else {
              echo "0 results";
            }

            //Get Closing Details but who cares about this
            /*$idSQL = "SELECT Closing_ID from Agent_involved_in_closing where Agent_ID = " . $pass;
            $idResults = mysqli_query($db, $idSQL);

            if ($idResults->num_rows > 0) {
            // output data of each row
              while($idRow = $idResults->fetch_assoc()) {
                $closingSQL = "SELECT * FROM closing where Closing_ID = ". $idRow["Closing_ID"];
                $closingResults = mysqli_query($db,$closingSQL);
                if ($closingResults->num_rows > 0) {
                // output data of each row
                  while($closingRow = $closingResults->fetch_assoc()) {
                    echo "ID: " . $closingRow["closing_ID"]. "<br>"; 
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
              echo "Agent not involved in any closing";
            }*/
              
          function setWorkedAs($code){
              $workedAs = "";
              if($code>18){
                $workedAs = "Agent 4";
              }else if($code>12){
                $workedAs = "Agent 3";
              }else if($code>6){
                $workedAs = "Agent 2";
              }else{
                $workedAs = "Agent 1";
              }

              if($code%6==0){
                $workedAs .= "'s 3rd upline";
              }else if($code%6==1){
                //The actual agent
              }else if($code%6==2){
                $workedAs .= "'s Branch President";
              }else if($code%6==3){
                $workedAs .= "'s Vice President";
              }else if($code%6==4){
                $workedAs .= "'s 1st upline";
              }else {
                $workedAs .= "'s 2nd upline";
              }

              return $workedAs;
            }
        ?>
      </p>
    </body>
</html>