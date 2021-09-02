<!-- Resources used:
https://www.w3schools.com/php/php_arrays.asp
https://www.php.net/manual/en/tutorial.forms.php
https://www.php.net/manual/en/function.base-convert.php
-->

<html>
   <head>
      <title>HW03 Joel Peckham</title>
   </head>
   <body>
      <?php
      $background = "white";
      $base = 16;
      if ($_SERVER["REQUEST_METHOD"] == "POST") {
         if (isset($_POST['base'])) {
            $base = $_POST['base'];
        }
        if (isset($_POST['background'])) {
         $background = $_POST['background'];
     }
      }
      echo  "<style>
               table, th, td {
               border: 1px solid black;
               background-color: $background;
               }
            </style>"
      ?>
      <h1>HW03 - Multiplication Table - Joel Peckham</h1>
      <form action="index.php" method="POST">
      <label for="background">Background Color: </label>
         <select name="background">
            <?php
            $colors = array("white", "lightblue", "yellow", "coral", "lightgreen");
            foreach ($colors as &$color) {
               $selectedProp = "";
               if ($color == $background){
                  $selectedProp = "selected";
               }
               echo"<option $selectedProp value='$color'>". ucfirst($color) ."</option>";
           }
            ?>
         </select> <br>
         <label>Base:</label>
         <?php
            for ($i = 2; $i <= 16; $i++) {
               echo "<button type='submit' value='$i' name='base'>$i</button>";
            }
         ?>
      </form>
      <table>
         <?php
         $tableSize = 20; //Size of table (NxN) in decimal. Excludes headers.

         // Header Rows
         echo "<tr> <th colspan='2' rowspan='2'>Multiply</th>";
         for ($i = 1; $i <= $tableSize; $i++) {
            echo "<th>$i</th>";
         }
         echo "</tr> <tr>";
         for ($i = 1; $i <= $tableSize; $i++) {
            $newBase = $i;
            echo "<th>".base_convert($newBase, 10, $base)."</th>";
         }
         echo "</tr>";

         // Non Header Rows
         for ($i = 1; $i <= $tableSize; $i++) {
            $newBasei = $i;
            echo "<tr> <th> $i </th>"."<th>".base_convert($newBasei, 10, $base)."</th>";
            for ($j = 1; $j <= $tableSize; $j++) {
               $item = $i * $j;
               echo "<td>" . base_convert($item, 10, $base) ."</td>";
            }
            echo "</tr>";
         }
         ?>
      </table>
   </body>
</html>