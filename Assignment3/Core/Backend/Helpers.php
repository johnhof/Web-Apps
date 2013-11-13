<?php

if(preg_match('/localhost/','http'.(empty($_SERVER['HTTPS'])?'':'s').'://'.$_SERVER['SERVER_NAME'])){
  define('HOST', 'http'.(empty($_SERVER['HTTPS'])?'':'s').'://'.$_SERVER['SERVER_NAME'].'/1520/Assignment3/', true);
}
else define('HOST', 'http://cs1520.cs.pitt.edu/~jmh162/php/Assignment3/', true);

date_default_timezone_set('America/New_York');

ini_set("log_errors", 1);
ini_set("error_log", "./log.txt");
error_reporting(E_ERROR | E_PARSE);


/*---------------------------------------------------------------------------------*/
/*-- DB utilities
/*---------------------------------------------------------------------------------*/

//Initializes the database used by this phase of the project

// echo 'Connecting to database... ';

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

// echo 'Success</br>';

function query($query){
  global $db;
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
  echo 'create: '.isset($_POST['create_attempt']).'<br>';
  echo 'login: '.isset($_POST['login_attempt']).'<br>';
  
  if (isset($_SESSION['loggedin'])) {
    echo 'already logged in';
    return true;
  } 
  else if (isset($_POST['login_attempt'])){
    echo 'attempted login';
  } 
  else if (isset($_POST['create_attempt'])){
    echo 'attempted login';
  }  else {
    
  }
}

function getValue($rsc, $var){
  $result = false;
  if ($rsc == 'session') { 
    if(isset($_SESSION)){
      if(isset($_SESSION[$var]))  $result = $_SESSION[$var];
    }
  }
  if ($rsc == 'post') { 
    if(isset($_POST)){
      if(isset($_POST[$var])) $result = $_POST[$var];
    }
  }
  if ($rsc == 'get') { 
    if(isset($_GET)){
      if(isset($_GET[$var])) $result = $_GET[$var];
    }
  }
  //error_log('getValue: '.$rsc.'('.$var.')='.$result);
  return  $result;
}

function redirect ($url) {
   echo '<script type="text/javascript">window.location = "./../../'.$url.'"</script>';
}

function printAll(){
  printPost();
  echo '<br>';
  printSession();
}

function printPost(){  
    foreach ($_POST as $key => $value) {
        echo "<tr>";
        echo "<td>";
        echo $key;
        echo "</td>";
        echo "<td>";
        echo $value;
        echo "</td>";
        echo "</tr>";
    }
}
function printSession(){  
    if(!isset($_SESSION)) {return;}
    foreach ($_SESSION as $key => $value) {
        echo "<tr>";
        echo "<td>";
        echo $key;
        echo "</td>";
        echo "<td>";
        echo $value;
        echo "</td>";
        echo "</tr>";
    }
}
function println($string){
  echo $string.'<br>';
}
?>