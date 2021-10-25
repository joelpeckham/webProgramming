<?php
session_start();
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
if($_SERVER["REQUEST_METHOD"] == "POST"){
    //Print post data
    print_r($_POST);
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
        // Prepare an insert statement
        $sql = "INSERT INTO `session` (`sessnum`, `location`, `dayofweek`, `begintime`, `endtime`) VALUES (null,?,?,?,?)";
         
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "iiii", $param_location, $param_dayofweek, $param_opentime, $param_closetime);
            
            // Set parameters
            $param_location = $location;
            $param_dayofweek = $dayofweek;
            $param_opentime = date("Hi", strtotime($opentime));
            $param_closetime = date("Hi", strtotime($closetime));

            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Records created successfully. Redirect to landing page
                header("location: index.php");
                exit();
            } else{
                //Log Error
                echo "Oops! Something went wrong. Please try again later.";
            }
        }
         
        // Close statement
        mysqli_stmt_close($stmt);
    }
    
}

?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create Record</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .wrapper{
            width: 600px;
            margin: 0 auto;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <h2 class="mt-5">Create Session</h2>
                    <p>Please fill this form and submit to add session record to the database.</p>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
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
                            <!-- HTML select where the days of week are displayed as options. Numbers are sent to server as POST values.
                            Where Sunday = 1, Monday = 2, ... Saturday = 7 -->
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
                        <input type="submit" class="btn btn-primary" value="Submit">
                        <a href="index.php" class="btn btn-secondary ml-2">Cancel</a>
                    </form>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>
<?php
    mysqli_close($link);
?>