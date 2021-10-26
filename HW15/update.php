<?php
session_start();
$back = isset($_SESSION['returnFile']) ? $_SESSION['returnFile'] : "index.php";
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}
// Include config file
require_once "config.php";
 
// Define variables and initialize with empty values
$location = $dayofweek = $opentime = $closetime = "";
$location_err = $dayofweek_err = $opentime_err = $closetime_err = "";
 
// Processing form data when form is submitted
if(isset($_POST["sessnum"]) && !empty($_POST["sessnum"])){
    // Get hidden input value
    $sessnum = $_POST["sessnum"];
    
    // Example $_POST data: Array ( [location] => 1 [dayofweek] => 1 [opentime] => 05:00 [closetime] => 06:00 )
    $location = trim($_POST["location"]);
    $dayofweek = trim($_POST["dayofweek"]);

    // Validate Opening Time
    $opentime = trim($_POST["opentime"]);
    if($opentime == ""){
        $opentime_err = "Please enter opening time.";     
    }
    // Validate Closing Time
    $closetime = trim($_POST["closetime"]);
    if($closetime == ""){
        $closetime_err = "Please enter closing time.";     
    }
    if(strtotime($closetime) < strtotime($opentime)){
        $closetime_err = "Closing time must be after opening time.";
    }
    
    // Check input errors before inserting in database
    if($location_err == "" && $dayofweek_err == "" && $opentime_err == "" && $closetime_err == ""){
        // Prepare an update statement
        $sql = "UPDATE `session` SET `location`=?, `dayofweek`=?, `begintime`=?, `endtime`=? WHERE sessnum=?";
         
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "iiiii", $param_location, $param_dayofweek, $param_opentime, $param_closetime, $param_sessnum);
            
            // Set parameters
            $param_location = $location;
            $param_dayofweek = $dayofweek;
            $param_opentime = date("Hi", strtotime($opentime));
            $param_closetime = date("Hi", strtotime($closetime));
            $param_sessnum = $sessnum;
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Records updated successfully. Redirect to landing page
                header("location: $back");
                exit();
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }
        }
         
        // Close statement
        mysqli_stmt_close($stmt);
    }
} else{
    // Check existence of id parameter before processing further
    if(isset($_GET["sessnum"]) && !empty(trim($_GET["sessnum"]))){
        // Get URL parameter
        $sessnum =  trim($_GET["sessnum"]);
        
        // Prepare a select statement
        $sql = "SELECT * FROM `session` WHERE `sessnum` = ?";
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "i", $param_sessnum);
            
            // Set parameters
            $param_sessnum = $sessnum;
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                $result = mysqli_stmt_get_result($stmt);
    
                if(mysqli_num_rows($result) == 1){
                    /* Fetch result row as an associative array. Since the result set
                    contains only one row, we don't need to use while loop */
                    $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
                    // Retrieve individual field value
                    $location = $row["location"];
                    $dayofweek = $row["dayofweek"];
                    $opentime = datetime::createfromformat('Hi', str_pad(strval($row["begintime"]), 4, '0', STR_PAD_LEFT))->format('H:i');
                    $closetime = datetime::createfromformat('Hi', str_pad(strval($row["endtime"]), 4, '0', STR_PAD_LEFT))->format('H:i');
    
                } else{
                    // URL doesn't contain valid id. Redirect to error page
                    header("location: error.php");
                    exit();
                }
                
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }
        }
        
        // Close statement
        mysqli_stmt_close($stmt);
    }  else{
        // URL doesn't contain id parameter. Redirect to error page
        header("location: error.php");
        exit();
    }
}
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Update Session</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .wrapper{
            width: 600px;
            margin: 0 auto;
        }
    </style>
</head>
<body>
<?php include 'nav.php'; ?>
    <div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <h2 class="mt-5">Update Session</h2>
                    <p>Please edit the input values and submit to update the session record.</p>
                    <form action="<?php echo htmlspecialchars(basename($_SERVER['REQUEST_URI'])); ?>" method="post">
                        <div class="form-group">
                            <label>Location</label>
                            <!-- HTML select generated from location table in database. All `l-name` values in the table are displayed 
                            as options in the select menu. The `location` number is the POST value. -->
                            <?php
                                $sql = "SELECT * FROM `location`";
                                $result = mysqli_query($link, $sql);
                                echo "<select class='form-control' name='location'>";
                                while($row = mysqli_fetch_array($result)){
                                    if($row['location'] == $location){
                                        echo "<option value='" . $row['location'] . "' selected>" . $row['l-name'] . "</option>";
                                    } else{
                                        echo "<option value='" . $row['location'] . "'>" . $row['l-name'] . "</option>";
                                    }
                                }
                                echo "</select>";
                            ?>
                            <span class="text-danger"><?php echo $location_err;?></span>
                        </div>
                        <div class="form-group">
                            <label>Day Of Week</label>
                            <?php
                                echo "<select class='form-control' name='dayofweek'>";
                                $daysOfWeek = array("Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday");
                                for($i = 1; $i <= 7; $i++){
                                    $selected = ($i == $dayofweek) ? "selected" : "";
                                    echo "<option value='" . $i . "' $selected>" . $daysOfWeek[$i-1] . "</option>";
                                }
                                echo "</select>";
                            ?>
                            <span class="text-danger"><?php echo $dayofweek_err;?></span>
                        </div>
                        <div class="form-group">
                            <label>Open Time</label>
                            <input type="time" name="opentime" class="form-control" value="<?php echo $opentime; ?>">
                            <span class="text-danger"><?php echo $opentime_err;?></span>
                        </div>
                        <div class="form-group">
                            <label>Close Time</label>
                            <input type="time" name="closetime" class="form-control" value="<?php echo $closetime; ?>">
                            <span class="text-danger"><?php echo $closetime_err;?></span>
                        </div>
                        <input type="hidden" name="sessnum" value="<?php echo $sessnum; ?>"/>
                        <input type="submit" class="btn btn-primary" value="Submit">
                        <a href="<?php echo $back;?>" class="btn btn-secondary ml-2">Cancel</a>
                    </form>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>
<?php
// Close connection
mysqli_close($link);
?>