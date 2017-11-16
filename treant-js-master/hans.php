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
    <div class="chart" id="agent-tree"></div>

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

    $arrayLength = sizeof($agentInvolved);
        // echo json_encode($agentInvolved);
    $json_data = json_encode($agentInvolved);
    file_put_contents('agents.json', $json_data);

        // $coba = $agentInvolved[1];
        // echo($coba['agentName']);
        // echo(($agentInvolved[1])['uplineID']);
    ?>

    <script src="vendor/raphael.js"></script>
    <script src="Treant.js"></script>
    
    <!-- <script src="agents.json"></script> -->
    <script type="text/javascript">
        var abc = <?php echo $arrayLength; ?>;
        var chart_config = {
            chart: {
                container: "#agent-tree",

                connectors: {
                    type: 'step'
                },
                node: {
                    HTMLclass: 'nodeExample1'
                }
            },
            nodeStructure: {
                text: {
                    title: "Direct Upline",
                    <?php echo 'name: "'.($agentInvolved[1])["agentName"].'"'; ?>,
                    <?php echo 'contact: "'.($agentInvolved[1])["agentPhone"].'"'; ?>,
                },
                image: "examples/headshots/2.jpg",
                link: {
                    <?php echo 'href: "hans.php?id='.($agentInvolved[0])["uplineID"].'"'; ?>
                },
                children: [
                {
                    text:{
                        <?php echo 'name: "'.($agentInvolved[0])["agentName"].'"'; ?>,
                    },
                    image: "examples/headshots/1.jpg",
                    stackChildren: true,
                    children: [
                    <?php 
                    for ($i = 2; $i < $arrayLength; $i++) {
                        ?>
                        {
                            text:{
                                title: "Downline",
                                <?php echo 'name: "'.($agentInvolved[$i])["agentName"].'"'; ?>,
                                <?php echo 'contact: "'.($agentInvolved[$i])["agentPhone"].'"'; ?>
                            },
                            image: "examples/headshots/8.jpg",
                            link: {
                                <?php echo 'href: "hans.php?id='.($agentInvolved[$i])["agentID"].'"'; ?>
                            }
                        },
                        <?php
                    }
                    ?>                  
                    ]
                }
                ]
            }
        };
    </script>
    <!-- <script src="es-tree.js"></script> -->
    <script>
        new Treant( chart_config );
    </script>
</body>
</html>