<?php


//--------------------------------------------------------------------------------------------------------------------------------------
//--------	UNIVERSAL DEFINES
//--------------------------------------------------------------------------------------------------------------------------------------

define('READ', 0, true);
define('EDIT', 1, true);
define('NEW_LN', 2, true);
define('FATAL', -1, true);

define('NO_LOCK', -1, true);

define('ERROR','ERROR', true);
define('WARNING','WARNING', true);
define('SUCCESS','SUCCESS', true);

//--------------------------------------------------------------------------------------------------------------------------------------
//--------	UNIVERSAL VARIABLES
//--------------------------------------------------------------------------------------------------------------------------------------

$db = new DBWrapper('localhost', 'root', '', 'mysql');

//--------------------------------------------------------------------------------------------------------------------------------------
//--------	MESSAGE HANDLER
//--------------------------------------------------------------------------------------------------------------------------------------

Class MessageHandler{
	private $msgLog;

	function __construct(){
		$this->msgLog = array();
	}

	function logMsg($type, $msg){
		$fullMsg = $type.': '.$msg;
		array_push($this->msgLog, $fullMsg);
	}

	function last(){
		$last = end($this->msgLog);
		reset($this->msgLog);
		return $last;
	}

	function getLog(){
		$fullLog = '';
		foreach ($this->msgLog as $index => $msg) $fullLog=$fullLog.$msg.'</br>';
		return $fullLog;
	}
}



//--------------------------------------------------------------------------------------------------------------------------------------
//--------	CONTROLLER HELPERS
//--------------------------------------------------------------------------------------------------------------------------------------

function emailPassword($email){
	global $db;
    $msg = 'your password is';
    $result = $db->simpleSelect('Makers', ['password'], 'email="'.$email.'"');
    if($result->num_rows == 0)return false;
    $pwd = mysqli_fetch_array($result)[0];
    //mail($email, 'Password Reminder', $pwd);
    return true;
}

function validateCreds($email, $pwd){
	global $db;
    $result = $db->simpleSelect('Makers', ['*'], 'email="'.$email.'" and password="'.$pwd.'"');
    if($result->num_rows == 0)return false;
    else return true;
}

//--------------------------------------------------------------------------------------------------------------------------------------
//--------	MODEL HELPERS
//--------------------------------------------------------------------------------------------------------------------------------------

	
function formatRequest(){
	$type = isset($_GET['view']) ? 'viewer' : 'maker'; 
	$query = $_GET;
   	$post = isset($_POST) ? $_POST : false;
   	$session = isset($_SESSION) ? $_SESSION : false;
    $submit = (isset($_GET['submit']));

    $request = new Request($type, $query, $post, $session, $submit);

    return $request;
}

//--------------------------------------------------------------------------------------------------------------------------------------
//--------	UTILITY FUNCITONS
//--------------------------------------------------------------------------------------------------------------------------------------


//remove a key value pair at an index
function deleteIndex($array, $index){
	$i = 0;
	foreach ($array as $key => $value) {
		if($i == $index){
			unset($array[$key]);
			return $array;
		}
		$i++;
	}
}

function println($string){print('</br>'.$string);}

function printlnOffset($string, $offset){println($offset.$string);}

function printArray($array){printArrayOff($array,'&nbsp&nbsp&nbsp&nbsp');}
function printArrayOff($array,$offset){

	print('[');

	foreach ($array as $key => $value) {

		if(is_array($value)){
			printlnOffset($key.'=>',$offset);
			printArrayOff($value,$offset.$offset);
		} 
		else printlnOffset($key.'=>'.$value,$offset);
	}
	printlnOffset(']',$offset);
}

?>