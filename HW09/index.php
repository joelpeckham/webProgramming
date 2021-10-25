<!-- Resources Used:

-->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HW09 - Joel Peckham</title>
</head>
<body>
<h1>HW09 - COVID Check - Joel Peckham</h1>
    <?php 
        // Get current day of week as number where Sunday is 1 and Saturday is 7.
        $dayNum = date('N');
        if ($dayNum == 7) {
            $dayNum = 1;
        } else {
            $dayNum++;
        }
        // Connect to database.
        $conn = new mysqli("localhost", "jpeckham", "biJxy45.20x9", "jpeckham_screening");
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
        $sql = "select * from session INNER JOIN location on session.location = location.location\n"
        . "WHERE `dayofweek` = $dayNum\n"
        . "ORDER BY `session`.`begintime` ASC";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            //Create title for table with current date. Format is like "Monday, April 21, 2020."
            $titleDate = date('l, F j, Y');
            echo "<h2>Check sessions currently open on</br>" . $titleDate . "</h2>";
            echo "<table border='1'>\n";
            //Create HTML table with check session locations name, open times, and closing times.
            echo "<tr><th>Location</th><th>Open</th><th>Close</th></tr>\n";
            while($row = $result->fetch_assoc()) {
                $currentFormattedTime = intval(strval(date('G')) . date('i'));
                $rowStartTime = $row["begintime"];
                $rowEndTime = $row["endtime"];
                $rowLocation = $row["l-name"];
                // If the current time is between the start and end time of the session, give background color.
                if ($currentFormattedTime >= $rowStartTime && $currentFormattedTime <= $rowEndTime) {
                    echo "<tr style='background-color:#b0ffda'><td>" . $rowLocation . "</td><td>" . $rowStartTime . "</td><td>" . $rowEndTime . "</td></tr>\n";
                } else {
                    echo "<tr><td>" . $rowLocation . "</td><td>" . $rowStartTime . "</td><td>" . $rowEndTime . "</td></tr>\n";
                }
            }
            echo "</table>\n";
        } else {
            echo "0 results";
        }
    ?>
</body>
</html>