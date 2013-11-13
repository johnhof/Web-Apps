
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
  query("create table Users (email char(30) primary key not null, name char(30) not null, password char(50) not null default 'pwd', played int not null default 0, won int not null default 0)");
  
  echo 'Success</br>';

//-------- ADD TUPLES -------------------------------------------------------------------------------------------------------------------

  echo 'inserting User tuples... ';

  query("insert into Users (email, name, password) values ('jmh162+maker1@pitt.edu', 'John1', 'pwd')");
  query("insert into Users (email, name, password) values ('jmh162+maker2@pitt.edu', 'John2', 'pwd')");
  query("insert into Users (email, name, password) values ('jmh162+maker3@pitt.edu', 'John3', 'pwd')");

  echo 'Success</br>';
?>
 </body>
</html>