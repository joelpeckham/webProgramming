<!-- Resources used:
-->

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>HW04 Joel Peckham</title>
</head>
<style>
   #pictureContainer{
      display:grid;
      grid-template-columns: repeat(4, 1fr); 
   }
   .image{
      width:25vw;
   }
   #mediaType::after{
      content:"for Computer";
   }
   @media(max-width:800px){
      #pictureContainer{
      grid-template-columns: repeat(2, 1fr);
   }
   .image{
      width:50vw;
   }
   #mediaType::after{
      content:"for Cell Phones";
   }
}
</style>
<body>
   <h1>HW04 <span id = "mediaType"></span> - Joel Peckham</h1>
   <div id = "pictureContainer">
      <?php
      $pictures = array("Camel.JPG", "Cheetah.JPG", "CuteKitty.gif", "Meerkat.JPG", "Otter.JPG", "RedPanda.JPG", "Rhino.JPG", "Tiger.gif", "Zebra.JPG");
         foreach ($pictures as $imageURL) {
            echo "<img class = 'image' src='$imageURL'>";
        }
      ?>
   </div>
</body>
</html>