<!-- Resources used:
https://www.w3schools.com/php/php_file_upload.asp
-->

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>HW07 - Joel Peckham</title>
</head>
<style>
   table, th, td {
      border: 1px solid black;
   }
</style>
<body>
<h1>HW07 - Uploading - Joel Peckham</h1>
<form action="index.php" method="post" enctype="multipart/form-data">
  Select image to upload:
  <input type="file" name="fileToUpload" id="fileToUpload">
  <input type="submit" value="Upload Image" name="submit">
</form>
<?php 

   if ($_SERVER["REQUEST_METHOD"] == "POST"){
      if(isset($_FILES["fileToUpload"])) {
         $target_dir = "uploads/";
         $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
         $uploadOk = 1;
         $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

         // Check if image file is a actual image or fake image
         if(isset($_POST["submit"])) {
         $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
         if($check !== false) {
            // echo "File is an image - " . $check["mime"] . ".";
            $uploadOk = 1;
         } else {
            // echo "File is not an image.";
            $uploadOk = 0;
         }
         }

         // Check if file already exists
         if (file_exists($target_file)) {
         echo "Sorry, file already exists.";
         $uploadOk = 0;
         }

         // Check file size
         if ($_FILES["fileToUpload"]["size"] > 500000) {
         echo "Sorry, your file is too large.";
         $uploadOk = 0;
         }

         // Allow certain file formats
         if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
         && $imageFileType != "gif" ) {
         echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
         $uploadOk = 0;
         }

         // Check if $uploadOk is set to 0 by an error
         if ($uploadOk == 0) {
         echo "Sorry, your file was not uploaded.";
         // if everything is ok, try to upload file
         } else {
         if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
            echo "The file ". htmlspecialchars( basename( $_FILES["fileToUpload"]["name"])). " has been uploaded.";
         } else {
            echo "Sorry, there was an error uploading your file.";
         }
         }
      }
      if(isset($_POST["deleteFile"])) {
         if (file_exists($_POST["deleteFile"])) {unlink($_POST["deleteFile"]);}
      }
      if(isset($_POST["publishFile"])) {
         if (file_exists("uploads/".$_POST["publishFile"])) {rename("uploads/".$_POST["publishFile"], "published/".$_POST["publishFile"]);}
      }
   }

   $directory = 'uploads/';
   $fileNames = array();
   if ($handle = opendir($directory)) {
      while (false !== ($entry = readdir($handle))) {
         if ($entry != "." && $entry != "..") {
            array_push($fileNames, $entry);
         }
      }
   }
   if(count($fileNames) > 0){
      echo "<h3>Current uploads:</h3><table>
            <tr><th> </th><th>File Name</th><th>Size</th><th>Preview</th></tr>";
         foreach ($fileNames as $fileName) {
            $fileSize = filesize($directory.$fileName);
            echo "<form action = 'index.php' method = 'POST'>";
            echo "<tr><td><button name = 'deleteFile' value = '$directory$fileName'type='submit'>Delete</button><br><button name = 'publishFile' value = '$fileName'type='submit'>Publish</button></td><td>$fileName</td><td>$fileSize</td><td><img src='$directory$fileName' width = '200px'></td></tr>";
         }
         echo "</table></form>";
   }

   $directory = 'published/';
   $fileNames = array();
   if ($handle = opendir($directory)) {
      while (false !== ($entry = readdir($handle))) {
         if ($entry != "." && $entry != "..") {
            array_push($fileNames, $entry);
         }
      }
   }
   if(count($fileNames) > 0){
      echo "<h3>Published:</h3><table>
            <tr><th> </th><th>File Name</th><th>Size</th><th>Preview</th></tr>";
         foreach ($fileNames as $fileName) {
            $fileSize = filesize($directory.$fileName);
            echo "<form action = 'index.php' method = 'POST'>";
            echo "<tr><td><button name = 'deleteFile' value = '$directory$fileName'type='submit'>Delete</button></td><td>$fileName</td><td>$fileSize</td><td><img src='$directory$fileName' width = '200px'></td></tr>";
         }
         echo "</table></form>";
   }

?>
</body>
</html>