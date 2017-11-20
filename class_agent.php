<?php

	class agent{
	    public $Agent_ID, $branchID, $name, $uplineID, $phone;
	    public $downlines = [];
    
	    public function __construct($Agent_ID, $branchID, $name, $phone, $uplineID=NULL){
	      $this->Agent_ID=$Agent_ID;
	      $this->branchID=$branchID;
	      $this->name=$name;
	   	  $this->uplineID=$uplineID;
	   	  $this->phone=$phone;
	    }   

	    public function printDetails(){
	    	echo '<h1>'.$this->name.'</h1>';
	    	echo '<br>';

	    	echo '<table class="table">';	
	    	echo '<tr>';   	
	    	echo '<td class="tabelkiri">' ."ID : " .'</td>';  
	    	echo '<td>' .$this->Agent_ID .'</td>';   
	    	echo '</tr>';

	    	echo '<tr>';   	
	    	echo '<td class="tabelkiri">' ."Kantor : " .'</td>';  
	    	// echo '<td>' .$this->branchID .'</td>';   
	    	echo '<td>' ."Pake nama cabang dil".'</td>';  
	    	echo '</tr>'; 

	    	echo '<tr>';   	
	    	echo '<td class="tabelkiri">' ."No. Telepon : " .'</td>';  
	    	echo '<td>' .$this->phone .'</td>';   
	    	echo '</tr>'; 

	    	echo '<tr>';   	
	    	echo '<td class="tabelkiri">' ."Upline : " .'</td>';  
	    	// echo '<td>' .$this->uplineID .'</td>';   
	    	echo '<td>' ."Pake nama orangnya dil" .'</td>'; 
	    	echo '</tr>';
	    	echo '</table>';

	    }

	    public function getEarningTotal($db){

	    	$earningSQL = "SELECT SUM(earning) as total FROM `agent_involved_in_closing` WHERE Agent_ID = " . $this->Agent_ID;
			$earningResult = mysqli_query($db, $earningSQL);
			$earningRow = $earningResult->fetch_assoc();

			$total = $earningRow["total"];
			echo '<h3>'."Total Komisi : Rp. " . $total . "</h3>";
	    }

	    public function getDownline($db){
	    	$downlineSQL = 
	            "SELECT Agent.Agent_ID, Agent.PhoneNumber, Agent.ImmediateUpline_ID, Agent.Name  
	            FROM Agent_Has_Downline, Agent
	            WHERE Agent.Agent_ID = Agent_Has_Downline.Downline_ID 
	            AND Agent_Has_Downline.Agent_ID = $this->Agent_ID"; //YOU KNOW WHAT TO DO

		    $downlineResult = mysqli_query($db, $downlineSQL);

		    if ($downlineResult->num_rows > 0) {
		    // output data of each row
			    while($row = $downlineResult->fetch_assoc()) {
		            $iteration = new Agent($row["Agent_ID"], $row["Name"],
		            	$row["PhoneNumber"], $row["ImmediateUpline_ID"]);
		            $downlines[] = clone $iteration;
			    }
			    print_r($downlines);
  			} else {
  		    	echo "Agent has no downlines";
  			}
	    }
	}
?>