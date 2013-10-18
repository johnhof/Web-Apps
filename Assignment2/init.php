
<html>
 <head>
  <title>PHP init</title>
 </head>
 <body>
<?php 
  //Initializes the database used by this phase of the project
  //
  //NOTE: I hate HTML
  include 'DBWrapper.php'; 

  $db = new DBWrapper('localhost', 'root', '', 'mysql');

  if(!$db) exit;

  $db->dropTable('test');

  $db->createTable('test',['id int primary key not null',
      						'name char(30) not null',
      						'string char(50) not null']);

  $db->tableToString('test'); 



?>
 </body>
</html>