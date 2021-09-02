<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HW13 - Joel Peckham</title>
</head>
<body>
    <style>
        table, th, td {
            border: 1px solid black;
        }
        td{
            width:1.5em;
            height:1.5em;
            text-align: center; 
            vertical-align: middle;
            background-color:red;
        }
    </style>
    <h1>HW13 - Javascript Tricks - Joel Peckham</h1>
    <table>
        <?php
        $size = 4;
        $val = 1;
        foreach (range(1,$size) as $i) {
            echo "<tr>";
            foreach (range(1,$size) as $j) {
                echo "<td onclick = 'clicked($val)' id = '$val'>$val</td>";
                $val++;
            }
            echo "</tr>";
        }
        ?>
    </table>
    <p id="count"></p>
    <script>
        let clickedarr = new Array(16).fill(false);
        function clicked(id){
            if (!clickedarr[id-1]){
                let elem = document.getElementById(id);
                elem.style.backgroundColor = "lightBlue";
                clickedarr[id-1] = true;
            }
            let n = 0;
            clickedarr.forEach(i=>{if (i) n++;})
            document.getElementById("count").innerText = n;
            if (n==16) window.location.href = "done.html";
        }
    </script>
</body>
</html>