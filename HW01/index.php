<!-- Resources used:
https://developer.mozilla.org/en-US/docs/Web/API/Window/resize_event
https://www.w3schools.com/jsref/prop_win_innerheight.asp
https://www.tutorialspoint.com/php/index.htm
https://www.geeksforgeeks.org/how-to-identify-server-ip-address-in-php/
-->

<html>
   <head>
      <title>HW01 Joel Peckham</title>
   </head>
   <body>
      <h1>Joel Peckham</h1>
      <p>
         <?php $ip_server = $_SERVER['SERVER_ADDR']; echo "Server address is: $ip_server"?><br>
         <?php $ip_remote = $_SERVER['REMOTE_ADDR']; echo "Client address is: $ip_remote"?><br>
         Window inner height: <span id="pgHeight"></span><br>
         Window inner width: <span id="pgWidth"></span>
      </p>
   </body>
   <script>
      function reportWindowSize(){
         document.getElementById("pgHeight").innerText = window.innerHeight;
         document.getElementById("pgWidth").innerText = window.innerWidth;
      }
      window.onresize = reportWindowSize;
      reportWindowSize();
   </script>
</html>