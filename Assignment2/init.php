
<html>
 <head>
  <title>PHP init</title>
 </head>
 <body>
<?php 
  //Initializes the database used by this phase of the project
  //
  //NOTE: I hate HTML
  include_once './Backbone/Utilities/DBWrapper.php'; 

  echo 'Connecting to database... ';

  $db = new DBWrapper('localhost', 'root', '', 'mysql');

  if(!$db) exit;

  echo 'Success</br>';

//-------- DROPT OLD TABLES -------------------------------------------------------------------------------------------------------------

  echo 'dropping old tables... ';

  $db->dropTable('Schedule');
  $db->dropTable('Maker_Schedule');
  $db->dropTable('Users');
  $db->dropTable('Time');

  echo 'Success</br>';

//-------- GERNERATE TABLES -------------------------------------------------------------------------------------------------------------
  
  echo 'generating tables... ';

  //USERS
  $db->createTable('Users',['email char(30) primary key not null',
      							          'name char(30) not null',
      							          'password char(50) not null',
                              'maker boolean not null default false']);
  //SCHEDULE
  $db->createTable('Schedule',['id int primary key not null',
      							            'name char(30) not null',
      						            	'string char(50) not null']);
  //MAKER_SCHECULE
  $db->createTable('Maker_Schedule',['userId int not null',
      						                   'scheduleID int not null',
      						                    'primary key (userId, scheduleId)']);
  //TIME
  $db->createTable('Time',['userId char(30) not null default "default"',
      						          'scheduleId int not null',
      						          'dateTime char(40) not null',
      						          'primary key (userId, scheduleId, dateTime)']);
  echo 'Success</br>';

//-------- ADD TUPLES -------------------------------------------------------------------------------------------------------------------

  echo 'inserting maker tuples... ';

  //MAKERS
  $values = array();

  $values['email'] = "'jmh162+maker1@pitt.edu'";
  $values['name'] = "'John'";
  $values['password'] = "'pwd'";
  $values['maker'] = "true";
  $db->insertPairs('Users', $values);

  $values['email'] = "'jmh162+maker2@pitt.edu'";
  $values['name'] = "'Dan'";
  $values['password'] = "'pwd'";
  $values['maker'] = "true";
  $db->insertPairs('Users', $values);

  $values['email'] = "'jmh162+maker3@pitt.edu'";
  $values['name'] = "'Mike'";
  $values['password'] = "'pwd'";
  $values['maker'] = "true";
  $db->insertPairs('Users', $values);

  $values['email'] = "'jmh162+maker4@pitt.edu'";
  $values['name'] = "'Sam'";
  $values['password'] = "'pwd'";
  $values['maker'] = "true";
  $db->insertPairs('Users', $values);

  echo 'Success</br>';
?>
 </body>
</html>