<!-- Resources used:
https://stackoverflow.com/questions/23719791/php-parsing-explode-bible-search-string-into-variables-or-tokens
https://github.com/openbibleinfo/Bible-Passage-Reference-Parser
-->

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>HW08 - Joel Peckham</title>
   <script src="js/en_bcv_parser.min.js" charset="UTF-8"></script>
</head>
<body>
<?php

// Create connection
$conn = new mysqli("localhost", "jpeckham", "biJxy45.20x9", "jpeckham_bible");
// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT * FROM `key_abbreviations_english`";
$result = $conn->query($sql);
$abbreviations = array();
while($row = $result->fetch_assoc()) {
  $abbreviations = array_merge($abbreviations, array(strtolower($row["a"]) => $row["b"]));
}

$sql = "SELECT * FROM `bible_version_key`";
$result = $conn->query($sql);
$versions = array();
$versionTable = array();
$versionLong = array();
while($row = $result->fetch_assoc()) {
  array_push($versions, $row["abbreviation"]);
  $versionTable = array_merge($versionTable, array($row["abbreviation"] => $row["table"]));
  $versionLong = array_merge($versionLong, array($row["abbreviation"] => $row["version"]));
}
$selectedVersion = "KJV"; 
$selectedRef = "";
$fetchedVerses = array();
if ($_SERVER["REQUEST_METHOD"] == "POST"){
  if(isset($_POST["version"]) && isset($_POST["reference"]) && isset($_POST["verses"])) {
    $selectedVersion = $_POST["version"];
    $selectedRef = $_POST["reference"];
    $verses = json_decode($_POST["verses"],true);
    foreach ($verses as $ref) {
      $sql = "SELECT `t` FROM " . $versionTable[$selectedVersion] . " WHERE b = " . $abbreviations[strtolower($ref['b'])] . " AND c = " . $ref['c'] . " AND v = ". $ref['v'];
      $result = $conn->query($sql);
      while($row = $result->fetch_assoc()) {
        array_push($fetchedVerses, array("b"=>$ref['b'], "c"=>$ref['c'], "v"=>$ref['v'], "t"=>$row['t']));
      }
    }
  }
}

$conn->close();
?>
<h1>HW08 - Bible Text Lookup - Joel Peckham</h1>
<form action="index.php" method="post" id = "verseForm">
<label for="version" >Version: </label>
<select name='version' id= "version">
  <?php foreach ($versions as $v) { 
    $s = ($v == $selectedVersion) ? "selected":"";
    echo "<option $s value='$v'>$v</option>";
    }?>
</select>
<label for="reference">Citation: </label>
<input type="text" name="reference" id="reference" value = "<?php echo $selectedRef; ?>">
<button type="submit">Submit</button>
</form>

<?php
if (count($fetchedVerses) > 0){
  echo "<h3>". $versionLong[$selectedVersion] ."</h3>";
  foreach ($fetchedVerses as $verse) {
    echo "<h5>".$verse['b']." ".$verse['c'].":".$verse['v']."</h5>";
    echo "<p>".$verse['t']."<br><p>";
  }
}
?>

<script>
  let targetForm = document.getElementById("verseForm")
  targetForm.addEventListener('submit', event => {
    // event.preventDefault();
    var bcv = new bcv_parser;
    let ents = bcv.parse(document.getElementById("reference").value).parsed_entities()[0].entities;
    let verseList = [];
    ents.forEach(ent => {
      for (let i = ent.start.v; i <= ent.end.v; i++) {
        verseList.push({b:ent.start.b,c:ent.start.c,v:i});
      }
    });
    var input = document.createElement('input');
    input.setAttribute('name', "verses"); input.setAttribute('value', JSON.stringify(verseList)); input.setAttribute('type', "hidden");
    targetForm.appendChild(input);
  })
</script>

</body>
</html>