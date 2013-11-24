
<html>
 <head>
  <title>PHP init</title>
 </head>
 <body>
<?php 
  include_once './Core/Backend/Helpers.php';
  
//-------- DROPT OLD TABLES -------------------------------------------------------------------------------------------------------------

  echo 'dropping old tables... ';

  query('drop table Words');

  echo 'Success</br>';

//-------- GERNERATE TABLES -------------------------------------------------------------------------------------------------------------
  
  echo 'generating tables... ';

  //USERS
  query("create table Words (word char(40) primary key not null)");
  
  echo 'Success</br>';

//-------- ADD TUPLES -------------------------------------------------------------------------------------------------------------------

  echo 'inserting Word tuples... ';

  $words = file_get_contents('words.txt');
  $words = explode("\n", $words);

  foreach ($words as $word) {
    $word = trim($word);
    query("insert into Words (word) values ('".$word."')");
  }

  echo 'Success</br>';
?>
 </body>
</html>