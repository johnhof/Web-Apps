
<html>
 <head>
  <title>PHP init</title>
 </head>
 <body>
<?php 
  include_once './Core/Backend/Helpers.php';
  
//-------- DROPT OLD TABLES -------------------------------------------------------------------------------------------------------------

  echo 'dropping old tables... ';

  query('drop table Users');

  echo 'Success</br>';

//-------- GERNERATE TABLES -------------------------------------------------------------------------------------------------------------
  
  echo 'generating tables... ';

  //USERS
  query("create table email (char(30) primary key not null, name char(30) not null, password char(50) not null default 'pwd')");
  
  echo 'Success</br>';

//-------- ADD TUPLES -------------------------------------------------------------------------------------------------------------------

  echo 'inserting User tuples... ';

  query("insert into Users (email, name, password) values ('jmh162+maker1@pitt.edu', 'John', 'pwd')");

  echo 'Success</br>';
?>
 </body>
</html>