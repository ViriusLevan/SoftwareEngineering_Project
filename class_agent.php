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

	    public function getDownline($db){
	    	$downlineSQL = 
	            "SELECT Agent.Agent_ID, Agent.PhoneNumber, Agent.ImmediateUpline_ID, Agent.Branch_ID  
	            FROM Agent_Has_Downline, Agent
	            WHERE Agent.Agent_ID = Agent_Has_Downline.Downline_ID 
	            AND Agent_Has_Downline.Agent_ID = $this->Agent_ID"; //YOU KNOW WHAT TO DO

		    $downlineResult = mysqli_query($db, $downlineSQL);

		    if ($downlineResult->num_rows > 0) {
		    // output data of each row
			    while($row = $downlineResult->fetch_assoc()) {
		            $iteration = new Agent($row["Agent_ID"], $row["Branch_ID"], 
		            	$row["ImmediateUpline_ID"], $row["PhoneNumber"]);
		            $downlines =  clone $iteration;
		            print_r($downlines);
			    }
  			} else {
  		    	echo "Agent has no downlines";
  			}
	    }
	}
?>