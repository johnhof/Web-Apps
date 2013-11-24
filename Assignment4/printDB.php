<html>
 <head>
  <title>PHP init</title>
 </head>
 <body>
  <?php

  include_once './Core/Backend/Helpers.php';
  
  echo 'Users:</br>';
  print_r(query('select * from Users'));
  
  echo '</br></br>Games:</br>';
  print_r(query('select * from Games'));
  
  echo '</br></br>Queue:</br>';
  print_r(query('select * from Queue'));
?>
 </body>
</html>