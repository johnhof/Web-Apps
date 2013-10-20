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
//--------	STATE
//--------------------------------------------------------------------------------------------------------------------------------------

Class State{
	//initialize state to read
  	private $state = READ;

 	//returns true if the state is readonly
  	function isRead(){return ($this->state==READ ? true : false );}

  	//returns true if the state allows writing
  	function isEdit(){return ($this->state==EDIT ? true : false );}

  	function setNewEntry(){$this->state=NEW_LN;}

  	function isNewEntry(){return ($this->state == NEW_LN ? true : false );}


  	//returns true if the session is marked for death
  	function isFatal(){return ($this->state==FATAL ? true : false );}  	

  	//mark the session for death
  	function markFatal(){$this->state=FATAL;}

  	//return state entered
  	function enterState($reqState){

  		//TODO: enter write if the file isnt locked, and lock the file
  		
  		return false;
  	}
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