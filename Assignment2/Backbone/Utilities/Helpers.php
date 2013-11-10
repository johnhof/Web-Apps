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


if(preg_match('/localhost/','http'.(empty($_SERVER['HTTPS'])?'':'s').'://'.$_SERVER['SERVER_NAME'])){
	define('HOST', 'http'.(empty($_SERVER['HTTPS'])?'':'s').'://'.$_SERVER['SERVER_NAME'].'/1520/Assignment2/Assignment2.php?', true);
}
else define('HOST', 'http://cs1520.cs.pitt.edu/~jmh162/php/Assignment2/Assignment2.php?', true);
date_default_timezone_set('America/New_York');

//--------------------------------------------------------------------------------------------------------------------------------------
//--------	UNIVERSAL VARIABLES
//--------------------------------------------------------------------------------------------------------------------------------------
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'mysql';

if(!preg_match('/localhost/',HOST)) {
	$username = 'HofrichterJ';
	$password = 'sour/thank';
	$database = 'HofrichterJ';
}

$db = new DBWrapper($host, $username, $password, $database);

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
    $result = $db->simpleSelect('Users', array(0=>'password'), 'email="'.$email.'" and maker=true');

    if($result->num_rows == 0)return false;
    $pwd = mysqli_fetch_array($result);
    $pwd = $pwd[0];

    mail($email, 'Password Reminder', $pwd);

    return true;
}

function validateCreds($email, $pwd){
	global $db;
    $result = $db->simpleSelect('Users', array(0=>'*'), 'email="'.$email.'" and password="'.$pwd.'" and maker=true');
    if($result->num_rows == 0)return false;
    else return true;
}
   
function createSchedule($name, $maker, $times, $users){
	global $db;
	$msg = 'Schedule creation failed';
	if(!$name) return $msg.': invalid name';

	//create schedule
	if($db->exists('Schedules', array(0=>'*'), 'maker="'.$maker.'" and name="'.$name.'"')) return $msg.': You already have a schedule of that name';

	$db->insert('Schedules', array(stringify($maker),stringify($name)));
	$scheduleId = 'schedule='.$maker.'_'.$name;

	$msg = 'Schedule created';


	$users = explode('-',$users);
	//create users and times
	foreach($users as $user){
		$parsed = explode(':',$user);
		if(count($parsed) > 1){
			$userName = $parsed[0];
			$userEmail = $parsed[1];

			if($db->exists('Users', array(0=>'*'), 'email="'.$userEmail.'"')) continue;
			$db->insert('Users', array(stringify($userName),stringify($userEmail),'""','"false"'));
			
			//email links
			$queryStr = $scheduleId.'&viewer='.$userEmail;
			$userLink = HOST.$queryStr;

    		mail($userEmail, 'New Schedule created!', $userLink);

			//once the user exists, assign them times
			$timesArray = explode('-',$times);
			foreach($timesArray as $time){
				$time = str_replace(array(0=>':'), ' ', $time);
				$values = array(stringify($userEmail), stringify($maker), stringify($name), stringify($time), "false");
				$db->insert('Times', $values);
			}

		}
	}

	$msg = 'Table Created at url: '.HOST.$scheduleId;
	return $msg;
}

function getSchedule($schedule, $viewer, $inEdit){
	global $db;
	$msg = 'fail';

	$params = explode('_',$schedule);
	if(count($params) < 2) return false;

	
	$maker = str_replace(' ', '+', $params[0]); 
	$schedule = $params[1];

	if(!$db->exists('Schedules', array('*'), 'maker="'.$maker.'" and name="'.$schedule.'"')) return array('No such schedule');

	//build the basic schedule
	$scheduleObj = new Schedule($schedule, $maker);
	$scheduleObj->setCurrentUser($viewer);
	$scheduleObj->setInEdit($inEdit);

	//get the times for the schedule
	$reults = $db->distinctSelect('Times', array('dateTime'), 'makerEmail="'.$maker.'" and scheduleName="'.$schedule.'"');
	if(!$reults) return $msg;
	$times = array();
	while ($time = mysqli_fetch_array($reults)){
		array_push($times, $time[0]);
	}


	//get the users for the schedule
	$reults = $db->distinctSelect('Times', array('userEmail'), 'makerEmail="'.$maker.'" and scheduleName="'.$schedule.'"');
	if(!$reults) return $msg;
	$users = array();
	$user = '';
	while ($user = mysqli_fetch_array($reults)){
		array_push($users, $user[0]);
	}

	foreach($times as $time){
		//query results are nested by row
		$time = $time;

		//attempt to add time
		$scheduleObj->addTime($time);

		foreach($users as $user){
			//query results are nested by row
			$user = $user;

			//attempt to add user
			$scheduleObj->addUser($user);

			$result = $db->simpleSelect('Times', array('going'), 'makerEmail="'.$maker.'" and scheduleName="'.$schedule.'" and dateTime="'.$time.'" and userEmail="'.$user.'"');

			if($result && $result->num_rows == 1){
				$result = mysqli_fetch_array($result);
				$result = $result[0];
				$result == 0 ? true: false;
				$scheduleObj->setUserTime($user, $time, $result);
			}
		}
	}
	
	return $scheduleObj->getHtml();


}

function finalizeSchedule($email){
	global $db;
	$msg = 'Schedule finalization failed';	
	$result = array();

	$results = $db->simpleSelect('Schedules', array('name'), 'maker="'.$email.'"');
	if(!$results) return 'No schedules owned';
	$tables = array();
	while ($table = mysqli_fetch_array($results)){
		array_push($tables, $table[0]);
	}

	$result[1] = array();
	$result[2] = array();
	foreach($tables as $table){
		//stash each table name and url
		array_push($result[1],$table);

		$link = HOST.'schedule='.$email.'_'.$table;
		$result[2][$table] = $link;
	}

	$result[0]='';
	return $result;
}

function submitEntries($scheduleId, $viewer, $values){
	global $db;

	$params = explode('_',$scheduleId);
	if(count($params) < 2) return false;

	$maker = str_replace(' ', '+', $params[0]); 
	$schedule = $params[1];


	//get all times for this  table
	$reults = $db->simpleSelect('Times', array('dateTime'), 'makerEmail="'.$maker.'" and scheduleName="'.$schedule.'" and userEmail="'.$viewer.'"');

	if($reults && $reults->num_rows != 0){
		$times = array();
		$time = '';
		while ($time = mysqli_fetch_array($reults)){
			array_push($times, $time[0]);
		}

		//for all times
		foreach($times as $time){
			if(in_array($time, $values)){
				$db->exec('Update Times Set going=true Where makerEmail="'.$maker.'" and scheduleName="'.$schedule.'" and dateTime="'.$time.'" and userEmail="'.$viewer.'"');
			}
			else{
				$db->exec('Update Times Set going=false Where makerEmail="'.$maker.'" and scheduleName="'.$schedule.'" and dateTime="'.$time.'" and userEmail="'.$viewer.'"');	
			}
		}
	}
}

function finalize($schedule, $maker){
	global $db;
	$msg = 'failed to create schedule';
	$return = array(0=>$msg);

	//get all times for this table
	$reults = $db->distinctSelect('Times', array('dateTime'), 'makerEmail="'.$maker.'" and scheduleName="'.$schedule.'"');

	if($reults && $reults->num_rows != 0){
		$times = array();
		while ($time = mysqli_fetch_array($reults)){
			array_push($times, $time[0]);
		}

		$timeChosen = '';
		$max = -1;
		foreach ($times as $time){
			//get the list of users going at the current time
			$result = $db->simpleSelect('Times', array('*'), 'makerEmail="'.$maker.'" and scheduleName="'.$schedule.'" and datetime="'.$time.'" and going='.true);

			//if its the new max count, store it
			if($result->num_rows > $max){
				$max = $result->num_rows;
				$timeChosen = $time;
			}
		}

		//get all times for this table
		$reults = $db->distinctSelect('Times', array('userEmail'), 'makerEmail="'.$maker.'" and scheduleName="'.$schedule.'"');
		if($reults && $reults->num_rows != 0){
			$users = array();
			while ($user = mysqli_fetch_array($reults)){
				array_push($users, $user[0]);
			}
			$msg = 'Time selected: '.$timeChosen;
			$msg = $msg.'<br>Emailing users: '; 
			foreach($users as $user){
				$msg = $msg.$user.'; ';
				$body = 'Schedule: '.$schedule.' - finalized to time: '.$timeChosen;
    			mail($user, 'New Schedule created!', $body);
			}
		}
	}

	$return[0]=$msg;
	return $return;

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