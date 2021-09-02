<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>HW06 Joel Peckham</title>
</head>
<style>
</style>
<body>
<h1>HW06 - Phone Exchanges - Joel Peckham</h1>
<?php 
$searchState = $searchCity = "";
$output = array();
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['state']) && isset($_POST['city'])) {
   $searchCity = $_POST['city'];
   $searchState = $_POST['state'];
   $lines = file('allutlzd.txt');
   $myfile = fopen("results.txt", "w");
   for($i = 1; $i < count($lines); $i++){
      $tokens = $parts = preg_split("/\t+/", $lines[$i]);
      $state = trim($tokens[0]);
      $numbers = explode("-",trim($tokens[1]));
      $city = trim($tokens[4]);
      if($searchCity == $city && $searchState == $state){
         array_push($output,"$numbers[0]:$numbers[1]:$state:$city");
         fwrite($myfile, "$numbers[0]:$numbers[1]:$state:$city\r\n");
      }
   }
   fclose($myfile);        
}
?>
   <form action="index.php" method="post">
      <label for="city">City: </label><input name="city" type="text" value = <?php echo "'$searchCity'";?>>
      <label for="state">State: </label><input name="state" type="text" value = <?php echo "'$searchState'";?>>
      <button type="submit">Submit</button>
   </form>
   <p>
<?php
   if(count($output) > 0){
      echo 
      "
      <table>
      <tr><th><a href='download.php' target='_blank' rel='noopener noreferrer'><button type='button'>Download</button></a></th></tr>
      ";
      foreach ($output as $item) {
         echo "<tr><td>$item</td></tr>";
      }
      echo "</table>";
   }
?>
</p>
</body>
</html>