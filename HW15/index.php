<?php
session_start();
$_SESSION['returnFile'] = $_SERVER['PHP_SELF']; 
$authenticated = isset($_SESSION['user']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>HW15 - Joel Peckham</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <style>
        .wrapper{
            max-width: 800px;
            margin: 0 auto;
        }
        table tr td:last-child{
            width: 120px;
        }
    </style>
    <script>
        $(document).ready(function(){
            $('[data-toggle="tooltip"]').tooltip();   
        });
    </script>
</head>
<body>
    <?php include 'nav.php'; ?>
    <div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="mt-5 mb-3 clearfix">
                        <h2 class="pull-left">Today's Sessions</h2>
                        <?php if($authenticated){
                            echo '<a href="create.php" class="btn btn-success pull-right"><i class="fa fa-plus"></i> New Session</a>';
                        } ?>
                    </div>
                    <?php
                    // Include config file
                    require_once "config.php";
                    $dayNum = date('N');
                    if ($dayNum == 7) {
                        $dayNum = 1;
                    } else {
                        $dayNum++;
                    }
                    $currentTime = intval(date('Hi'));
                    // Attempt select query execution
                    $sql = "select * from session INNER JOIN location on session.location = location.location\n"
                    . "WHERE `dayofweek` = $dayNum\n"
                    . "ORDER BY `session`.`begintime` ASC";
                    if($result = mysqli_query($link, $sql)){
                        if(mysqli_num_rows($result) > 0){
                            echo '<table class="table table-bordered table-striped">';
                                echo "<thead>";
                                    echo "<tr>";
                                        echo "<th>Location</th>";
                                        echo "<th>Open</th>";
                                        echo "<th>Close</th>";
                                        if ($authenticated) {echo "<th>Action</th>";}
                                    echo "</tr>";
                                echo "</thead>";
                                echo "<tbody>";
                                while($row = mysqli_fetch_array($result)){
                                    $currentlyOpen = ($currentTime >= $row['begintime'] && $currentTime <= $row['endtime']) ? "style='font-weight:bold'" : "";
                                    echo "<tr>";
                                        echo "<td $currentlyOpen>" . $row['l-name'] . "</td>";
                                        echo "<td $currentlyOpen align='right' char=':'>" . datetime::createfromformat('Hi', str_pad(strval($row["begintime"]), 4, '0', STR_PAD_LEFT))->format('g:i A') . "</td>";
                                        echo "<td $currentlyOpen align='right' char=':'>" . datetime::createfromformat('Hi', str_pad(strval($row["endtime"]), 4, '0', STR_PAD_LEFT))->format('g:i A') . "</td>";
                                        if ($authenticated){
                                            echo "<td>";
                                            // echo '<a href="read.php?sessnum='. $row['sessnum'] .'" class="mr-3" title="View Record" data-toggle="tooltip"><span class="fa fa-eye"></span></a>';
                                            echo '<a href="update.php?sessnum='. $row['sessnum'] .'" class="mr-3" title="Update Record" data-toggle="tooltip"><span class="fa fa-pencil"></span></a>';
                                            echo '<a href="delete.php?sessnum='. $row['sessnum'] .'" title="Delete Record" data-toggle="tooltip"><span class="fa fa-trash"></span></a>';
                                            echo "</td>";
                                        }
                                    echo "</tr>";
                                }
                                echo "</tbody>";                            
                            echo "</table>";
                            // Free result set
                            mysqli_free_result($result);
                        } else{
                            echo '<div class="alert alert-danger"><em>No records were found.</em></div>';
                        }
                    } else{
                        echo "Oops! Something went wrong. Please try again later.";
                    }
                    // Close connection
                    mysqli_close($link);
                    ?>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>