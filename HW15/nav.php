<nav class="navbar navbar-expand-lg navbar-light bg-light">
  <a class="navbar-brand" href="/jpeckham/HW15/index.php">Covid Screening</a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>
  <div class="collapse navbar-collapse" id="navbarSupportedContent">
    <ul class="navbar-nav mr-auto">
      <li class="nav-item active">
        <a class="nav-link" href="/jpeckham/HW15/index.php">Today's Sessions</a>
      </li>
    </ul>
    <?php 
    if(isset($_SESSION['user'])) {
        echo "<a class='nav-link'>".$_SESSION['user']."</a>";
        echo '<a role="button" class="btn btn-outline-primary my-2 my-sm-0" href="/jpeckham/HW15/logout.php">Logout</a>';
    }
    else{
        echo '<a role="button" class="btn btn-outline-primary my-2 my-sm-0" href="/jpeckham/HW15/login.php">Login</a>';
    }
    ?>
  </div>
</nav>