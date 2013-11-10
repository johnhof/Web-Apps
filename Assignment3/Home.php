<?php
  session_start();
?>
<!DOCTYPE html>
<html>

<link rel="stylesheet" type="text/css" href="rsc.css" media="screen" />

<?php
  include_once './Core/Backend/Helpers.php';
  printAll();
  loggedIn();
?>

<title>Home</title>
<body>
  
<div class="main">

  <div class="heading">
    <h1>Welcome</h1><br>
  </div>
  
  <div class="content">
    <h3>Select an Action</h3><br>
    <a href="./Game.php" class="false_button">New Game</a>
    <a href="./Logout.php"class="false_button">Logout</a>
  </div>

</div>

</body>
</html>
