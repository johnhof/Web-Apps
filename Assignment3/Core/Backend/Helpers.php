<?php

if(preg_match('/localhost/','http'.(empty($_SERVER['HTTPS'])?'':'s').'://'.$_SERVER['SERVER_NAME'])){
  define('HOST', 'http'.(empty($_SERVER['HTTPS'])?'':'s').'://'.$_SERVER['SERVER_NAME'].'/1520/Assignment3/', true);
}
else define('HOST', 'http://cs1520.cs.pitt.edu/~jmh162/php/Assignment3/', true);

date_default_timezone_set('America/New_York');

/*---------------------------------------------------------------------------------*/
/*-- DB utilities
/*---------------------------------------------------------------------------------*/

//Initializes the database used by this phase of the project

echo 'Connecting to database... ';

$host = 'localhost';
$username = 'root';
$password = '';
$database = 'mysql';

if(!preg_match('/localhost/',HOST)) {
  $username = 'HofrichterJ';
  $password = 'sour/thank';
  $database = 'HofrichterJ';
}
$db = new mysqli($host, $username, $password, $database);
if(!$db) exit;

echo 'Success</br>';

function query($query){
  global $db;
  print_r($query);
  $results = $db->query($query);
  if($db->error){
    print_r($db->error);
  }
  
  if(!$results) return false;
  
  $array = array();
  while ($element = mysqli_fetch_array($results)){
    array_push($array, $element);
  }
  return $array;
}

/*---------------------------------------------------------------------------------*/
/*-- 
/*---------------------------------------------------------------------------------*/

function getHtml($filename){
  
}


function loggedIn() {
  
}


?>