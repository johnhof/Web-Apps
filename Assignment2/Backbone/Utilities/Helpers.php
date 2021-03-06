<?php

include('Schedule.php');

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


define('HOST', 'http'.(empty($_SERVER['HTTPS'])?'':'s').'://'.$_SERVER['SERVER_NAME'].'/1520/Assignment2/Assignment2.php?', true);
date_default_timezone_set('America/New_York');

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
    $result = $db->simpleSelect('Users', ['password'], 'email="'.$email.'" and maker=true');
    if($result->num_rows == 0)return false;
    $pwd = mysqli_fetch_array($result)[0];
    //mail($email, 'Password Reminder', $pwd);
    return true;
}

function validateCreds($email, $pwd){
	global $db;
    $result = $db->simpleSelect('Users', ['*'], 'email="'.$email.'" and password="'.$pwd.'" and maker=true');
    if($result->num_rows == 0)return false;
    else return true;
}
   
function createSchedule($name, $maker, $times, $users){
	global $db;
	$msg = 'Schedule creation failed';
	println($name);
	println($times);
	println($users);

	if(!$name) return $msg.': invalid name';

	//create schedule
	if($db->exists('Schedules', ['*'], 'maker="'.$maker.'" and name="'.$name.'"')) return $msg.': You already have a schedule of that name';

	$db->insert('Schedules', [stringify($maker),stringify($name)]);
	$scheduleId = 'schedule='.$maker.'_'.$name;

	$msg = 'Schedule created';


	$users = explode('-',$users);
	//create users and times
	foreach($users as $user){
		$parsed = explode(':',$user);
		if(count($parsed) > 1){
			$userName = $parsed[0];
			$userEmail = $parsed[1];

			if($db->exists('Users', ['*'], 'email="'.$userEmail.'"')) continue;
			$db->insert('Users', [stringify($userName),stringify($userEmail),'""','"false"']);
			
			//email links
			$queryStr = $scheduleId.'&viewer='.$userEmail;
			$userLink = HOST.$queryStr;
			println($userLink);
			//
			//
			//TODO: Email users their links
			//
			//				
			//once the user exists, assign them times
			$timesArray = explode('-',$times);
			foreach($timesArray as $time){
				$time = str_replace([':'], ' ', $time);
				$values = [stringify($userEmail), stringify($maker), stringify($name), stringify($time), "false"];
				$db->insert('Times', $values);
			}

		}
	}

	$msg = 'Table Created at url: '.HOST.$scheduleId;
	return $msg;
}

function getSchedule($schedule, $viewer, $inEdit){
	global $db;


	$params = explode('_',$schedule);

	if(count($params) < 2) return false;

	
	$maker = str_replace(' ', '+', $params[0]); 
	$schedule = $params[1];

	//build the basic schedule
	$scheduleObj = new Schedule($schedule);
	$scheduleObj->setCurrentUser($viewer);
	$scheduleObj->setInEdit($inEdit);

	//get the times for the schedule
	$times = $db->distinctSelect('Times', ['dateTime'], 'makerEmail="'.$maker.'" and scheduleName="'.$schedule.'"');
	if(!$times) return $msg;
	$times = mysqli_fetch_all($times);

	//get the users for the schedule
	$users = $db->distinctSelect('Times', ['userEmail'], 'makerEmail="'.$maker.'" and scheduleName="'.$schedule.'"');
	if(!$users) return $msg;
	$users = mysqli_fetch_all($users);

	foreach($times as $time){
		//query results are nested by row
		$time = $time[0];

		//attempt to add time
		$scheduleObj->addTime($time);

		foreach($users as $user){
			//query results are nested by row
			$user = $user[0];

			//attempt to add user
			$scheduleObj->addUser($user);

			$value = $db->distinctSelect('Times', ['going'], 'makerEmail="'.$maker.'" and scheduleName="'.$schedule.'" and userEmail="'.$user.'"');
			if(is_bool($value)){
				$scheduleObj->setUserTime($user, $time, $value);
			}
		}
	}
	
	return $scheduleObj->getHtml();


}

function finalizeSchedule($email){
	global $db;
	$msg = 'Schedule finalization failed';	
	$result = [];

	$tables = $db->simpleSelect('schedules', ['name'], 'maker="'.$email.'"');
	if(!$tables) return 'No schedules owned';
	$tables = mysqli_fetch_all($tables);

	$result[1] = array();
	$result[2] = array();
	foreach($tables as $table){
		//stash each table name and url
		array_push($result[1],$table[0]);

		$link = HOST.'schedule='.$email.'_'.$table[0];
		$result[2][$table[0]] = $link;
	}

	$result[0]='';
	return $result;
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

function stringify($string){
	return '"'.$string.'"';
}
?>