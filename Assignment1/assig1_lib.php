<?php 

define('READ', 0, true);
define('WRITE', 1, true);
define('FATAL', -1, true);

define('ERROR','ERROR', true);
define('WARNING','WARNING', true);
define('SUCCESS','SUCCESS', true);


//--------------------------------------------------------------------------------------------------------------------------------------
//--------	CONTROLLER
//--------------------------------------------------------------------------------------------------------------------------------------

Class Controller{
	private $state = null;
	private $fileHandler = null;
	private $msgHandler = null;
	private $error = false;

	private $users;
	private $schedule;

	function initSession(){

    echo "Hello world!";
		$state = new State();
		$fileHandler = new fileHandler();
		$msgHandler = new messageHandler();

		//load resource files
		$users = $fileHandler->loadUsers();
		if(!$users) $this->terminate('Failed to open user file');

		$schedule = $fileHandler->loadSchedule();
		if(!$schedule) terminate('Failed to open schedule file');

		if($state->fatal()) return false;
		
		log(SUCCESS, 'schedule and user files opened');

		//parse resource files



		//TODO: cookie/token setup
	}

	//messagehandler wrappers
	function log($type, $msg){$msgHandler->log($type, $msg);}

	//display error and return an error code
	function terminate($msg){
		$msgHandler->log(ERROR, $msg);
		$state->terminate();
	}
	function getLog(){return $msgHandler->getLog();}
	function lastLog(){return $msgHandler->last();}
}

//--------------------------------------------------------------------------------------------------------------------------------------
//--------	STATE
//--------------------------------------------------------------------------------------------------------------------------------------

Class State{
	//initialize state to read
  	private $state = READ;

 	//returns true if the state is readonly
  	function read(){return ($state==READ ? true : false );}

  	//returns true if the state allows writing
  	function write(){return ($state==WRITE ? true : false );}

  	//returns true if the session is marked for death
  	function fatal(){return ($state==FATAL ? true : fase );}

  	//mark the session for death
  	function terminate(){$state=FATAL;}

  	//return state entered
  	function enterState($reqState){

  		//TODO: enter write if the file isnt locked, and lock the file
  		
  		return false;
  	}

}

//--------------------------------------------------------------------------------------------------------------------------------------
//--------	FILE HANDLER
//--------------------------------------------------------------------------------------------------------------------------------------

Class fileHandler{
    private $userFile = null; 
    private $scheduleFile = null; 

    //load the user file and return a 2D array
    function loadUsers() { 
        $userFile = fopen ('users.txt', 'r');
        if(!$userFile) return null;
        return null;
    } 

    //load the schedule adn return an array
    function loadSchedule(){
      	$scheduleFile = fopen('shedule.txt', 'r');
      	if(!$scheduleFile) return null;
      	return null;
    }

    function writeSchedule(){
      	$scheduleFile = fopen('shedule.txt', 'w');
      	if(!$scheduleFile) return null;
      	return null;
    }
} 

//--------------------------------------------------------------------------------------------------------------------------------------
//--------	MESSAGE HANDLER
//--------------------------------------------------------------------------------------------------------------------------------------

Class messageHandler{
	private $msgLog = null;

	function log($type, $msg){
		$fullMsg = $type+': '+$msg;
		array_push($msgLog, $fullMsg);
	}

	function last(){
		$last = end($msgLog);
		reset($msgLog);
		return $last;
	}

	function getLog(){
		$fullLog = '';
		foreach ($msgLog as $index => $msg) {
			$fullLog+=$msg+'\n';
		}
		return $fullLog;
	}
}
?>