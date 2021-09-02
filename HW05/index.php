<!-- Resources used:
https://www.php.net/manual/en/function.readdir.php
https://stackoverflow.com/questions/24570744/remove-extra-spaces-but-not-space-between-two-words
-->

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>HW05 Joel Peckham</title>
</head>
<style>
   table, th, td {
  border: 1px solid black;
}
</style>
<body>
   <h1>HW05 - Text Analysis - Joel Peckham</h1>
   <?php
      $directory = 'verbiage/';
      if ($handle = opendir($directory)) {
         $fileNames = array();
         while (false !== ($entry = readdir($handle))) {
            if ($entry != "." && $entry != "..") {
               array_push($fileNames, $entry);
            }
         }
         closedir($handle);
         asort($fileNames);
         echo 
         "
         <p>List of files:</p>
         <table>
         <tr><th>File Name</th><th>Size</th></tr>
         ";
         foreach ($fileNames as $file) {
            echo "<tr><td>$file</td><td>".filesize($directory.$file)."</td></tr>";
         }
         echo "</table>";
         $wordCounts = array();
         foreach ($fileNames as $fileName) {
            $file = file_get_contents($directory.$fileName);
            $wordList = explode(" ",strtolower(preg_replace('/^\s+|\s+$|\s+(?=\s)/', '',str_replace(".","",str_replace(",","",preg_replace('/\s\s+/', " ", $file))))));
            foreach ($wordList as $word) {
               // echo "$word<br>";
               if (isset($wordCounts[$word])){
                  $wordCounts[$word] = $wordCounts[$word] + 1;
               }
               else{
                  $wordCounts[$word] = 1;
               }
               // echo "word: $word number: $wordList[$word]<br>";
            }
         }
            arsort($wordCounts);
            echo 
         "
         <p>Word list:</p>
         <table>
         <tr><th>Word</th><th>Number</th></tr>
         ";
         foreach ($wordCounts as $word => $count) {
            echo "<tr><td>$word</td><td>$count</td></tr>";
         }
         echo "</table>";
         
      }
      else{
         echo "Error. Files not opened.";
      }
   ?>

</body>
</html>