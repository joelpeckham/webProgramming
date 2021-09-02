<!-- Resources used:
https://www.computerhope.com/issues/ch001655.htm
https://www.w3schools.com/php/php_variables.asp
https://www.w3schools.com/php/php_looping_for.asp
https://www.php.net/manual/en/function.dechex.php
https://stackoverflow.com/questions/4436739/how-to-create-html-table-in-php
https://www.w3schools.com/css/css_table.asp
-->

<html>
   <head>
      <title>HW02 Joel Peckham</title>
   </head>
   <style>
      table, th, td {
      border: 1px solid black;
      background-color: lightblue;
      }
   </style>
   <body>
      <h1>HW02 - Multiplication Table - Joel Peckham</h1>
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
            echo "<th>".dechex($i)."</th>";
         }
         echo "</tr>";

         // Non Header Rows
         for ($i = 1; $i <= $tableSize; $i++) {
            echo "<tr> <th> $i </th>"."<th>".dechex($i)."</th>";
            for ($j = 1; $j <= $tableSize; $j++) {
               $item = $i * $j;
               echo "<td>" . dechex($item) ."</td>";
            }
            echo "</tr>";
         }
         ?>
      </table>
   </body>
</html>