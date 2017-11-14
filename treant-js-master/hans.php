<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width">
    <title> ES </title>
    <link rel="stylesheet" href="Treant.css">
    <link rel="stylesheet" href="es-tree.css">
    
</head>
<body>
    <div class="chart" id="basic-example"></div>

    <?php
        include('../config.php');
        $chosenAgentId = $_GET['id'];

        $sql = "SELECT * FROM agent where status = 1";
        $result = mysqli_query($db,$sql);

        $agentInvolved = array();
        $data = array();
        $uplineID = -1;
        $count = 2;

        while($row = $result->fetch_assoc()) {
            if ($row['Agent_ID'] == $chosenAgentId) {
                $data['agentID'] = $row['Agent_ID'];
                $data['agentName'] = $row['Name'];
                $data['uplineID'] = $row['ImmediateUpline_ID'];
                $uplineID = $row['ImmediateUpline_ID'];
                $data['agentStatus'] = $row['Status'];
                $data['agentPhone'] = $row['PhoneNumber'];
                $agentInvolved[0] = $data;
            } else if ($row['ImmediateUpline_ID'] == $chosenAgentId) {
                $data['agentID'] = $row['Agent_ID'];
                $data['agentName'] = $row['Name'];
                $data['uplineID'] = $row['ImmediateUpline_ID'];
                $data['agentStatus'] = $row['Status'];
                $data['agentPhone'] = $row['PhoneNumber'];
                $agentInvolved[$count] = $data;
                $count++;
            }
        }

        if ($uplineID!=null) {
            $sql = "SELECT * FROM agent where Agent_ID = $uplineID AND status = 1";
            $result = mysqli_query($db,$sql);
            $row = $result->fetch_assoc();

            $data['agentID'] = $row['Agent_ID'];
            $data['agentName'] = $row['Name'];
            $data['uplineID'] = $row['ImmediateUpline_ID'];
            $data['agentStatus'] = $row['Status'];
            $data['agentPhone'] = $row['PhoneNumber'];
            $agentInvolved[1] = $data;
        } else {
            $agentInvolved[1] = null;
        }

        // echo json_encode($agentInvolved);
        $json_data = json_encode($agentInvolved);
        file_put_contents('agents.json', $json_data);
    ?>

    <script src="vendor/raphael.js"></script>
    <script src="Treant.js"></script>
    
    <script src="es-tree.js"></script>
    <script>
        new Treant( chart_config );
    </script>
</body>
</html>