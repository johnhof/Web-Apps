
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

  $db->dropTable('Schedules');
  $db->dropTable('Users');
  $db->dropTable('Times');

  echo 'Success</br>';

//-------- GERNERATE TABLES -------------------------------------------------------------------------------------------------------------
  
  echo 'generating tables... ';

  //USERS
  $db->createTable('Users',['email char(30) primary key not null',
      							          'name char(30) not null',
      							          'password char(50) not null default "pwd"',
                              'maker boolean not null default false']);
  //SCHEDULE
  $db->createTable('Schedules',['maker char(30) not null',
      							            'name char(30) not null',
                                'primary key (maker, name)']);
  //TIME
  $db->createTable('Times',['userEmail char(30) not null default "default"',
      						          'makerEmail char(30) not null',
                            'scheduleName char(30) not null',
                            'dateTime char(30) not null',
                            'going boolean not null default false',
      						          'primary key (userEmail, makerEmail, scheduleName, dateTime)']);
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