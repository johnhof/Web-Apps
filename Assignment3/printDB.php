<html>
 <head>
  <title>PHP init</title>
 </head>
 <body>
  <?php

  include_once './Core/Backend/Helpers.php';
  
  echo 'Result: ';
  print_r(query('select * from Users'));
?>
 </body>
</html>