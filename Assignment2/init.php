
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

//-------- DROPT OLD TABLES -------------------------------------------------------------------------------------------------------------

  $db->dropTable('Makers');
  $db->dropTable('Schedule');
  $db->dropTable('Maker_Schedule');
  $db->dropTable('Users');
  $db->dropTable('Time');

//-------- GERNERATE TABLES -------------------------------------------------------------------------------------------------------------
  
  //MAKERS
  $db->createTable('Makers',['email char(30) primary key not null',
      							'name char(30) not null',
      							'password char(50) not null']);
  //SCHEDULE
  $db->createTable('Schedule',['id int primary key not null',
      							'name char(30) not null',
      							'string char(50) not null']);
  //MAKER_SCHECULE
  $db->createTable('Maker_Schedule',['userId int not null',
      						'scheduleID int not null',
      						'primary key (userId, scheduleId)']);
  //USERS
  $db->createTable('Users',['id int primary key not null']);
  //TIME
  $db->createTable('Time',['userId int not null',
      						'scheduleId int not null',
      						'dateTime char(40) not null',
      						'primary key (userId, scheduleId, dateTime)']);

//-------- ADD TUPLES -------------------------------------------------------------------------------------------------------------------


?>
 </body>
</html>