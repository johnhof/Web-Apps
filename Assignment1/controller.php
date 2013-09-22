<?php 

include 'HtmlTranslator.php';
include 'FileHandler.php';
include 'utility.php';


define('MAIN_PAGE', 'Assignment1.php', true);

//--------------------------------------------------------------------------------------------------------------------------------------
//--------	CONTROLLER
//--------------------------------------------------------------------------------------------------------------------------------------

Class Controller{
	private $state;
	private $fileHandler;
	private $msgHandler;
	private $error = false;

	private $translator;
	private $html;

	private $userFile;
	private $users;
	private $scheduleFile;
	private $schedule;
	private $table;
	private $currentEdit;

	function __construct() {
		$this->state = new State();
		$this->fileHandler = new FileHandler();
		$this->msgHandler = new MessageHandler();
		$this->translator = new HtmlTranslator();

		$this->userFile = null;
		$this->users = array();
		$this->scheduleFile = null;
		$this->schedule = array();
		$this->table = array();

		$this->currentEdit = '';
		$this->html = '';
   	}

   	//executes all functions to output the HTML
	function initSession(){

		//load resource files
		$this->refreshDataSet();

		if($this->isFatal())return;

		//check the state
		$this->checkInput();

		
		if($this->isFatal())return;

		//construct the table from the data
		$this->buildTable($this->schedule, $this->users);

		if($this->isFatal())return;	

	}


//--------  DATA WRAPPERS  -------------------------------------------------------------------------------------------------------------
//NOTE: NAMING IS LEFT ABSTRACT TO MINIMIZE DISRUPTION WHEN CHANGING TO A DATABASE SOURCE 
//
	function LoadFiles(){

		//open or create the user file
		$this->userFile = $this->fileHandler->rOpen('users.txt');
		if($this->userFile == NO_LOCK) $this->markFatal('User file is currently being edited, try again later');
		else if(!$this->userFile){
			$this->logMsg(WARNING, 'Failed to open user file, attempting to create one');

			$this->userFile = $this->fileHandler->rCreate('users.txt');
			if(!$this->userFile) $this->markFatal('Failed to create user file');
		}

		//open the schedule file
		$this->scheduleFile = $this->fileHandler->rOpen('schedule.txt');
		if($this->userFile == NO_LOCK) $this->markFatal('Schedule file is locked, try again later');
		else if(!$this->scheduleFile) $this->markFatal('Failed to open schedule file');
	}

	//wrapper to encapsulate calls
	function refreshDataSet(){
		$this->LoadFiles();

		$this->getUsers();
		$this->getSchedule();

		//close and free the file locks
		$this->fileHandler->close($this->userFile);
		$this->fileHandler->close($this->scheduleFile);
	}

	//refreshes from the downstream data source
	function getUsers(){
		$this->users = $this->fileHandler->parseRows($this->userFile, '^', '|');
		$this->logMsg(SUCCESS, 'users generated');
	}

	function getSchedule(){
		$this->schedule = $this->fileHandler->parseRows($this->scheduleFile, '^', '|');	
		$this->logMsg(SUCCESS, 'schedule generated');	
	}

	//updates the downstream data source
	function updateUsers(){
		$text='';
//TODO: clean this up
		foreach ($this->users as $name => $schedule) {
			$text = $text."\n".$name.'^';
			foreach ($schedule as $cell) {
				$text = $text.$cell.'|';
			}
		}

		$returnCode = $this->fileHandler->writeToFile('users.txt',$text);
		if($returnCode == NO_LOCK) $this->logMsg(WARNING,'User file is locked, try again later');
		else $this->logMsg(SUCCESS, 'schedule updated, and uploaded');

	}

	function setSchedule(){

	}


//--------  TABLE CONSTRUCTION  --------------------------------------------------------------------------------------------------------

	//builds the rable from scratch
	function buildTable($schedule,$users){
		$this->refreshTable();
	}

	//builds the table from the internal data representation
	function refreshTable(){
		$this->table = array();

		//set the column headers
		$this->table[0] = $this->getColumnHeaders();

		//add a row for each user
		foreach ($this->users as $name => $schedule) {
			$row = array();

			//if this user is being edited
			if($this->currentEdit == $name) $row = $this->userEditRow($name, $schedule, $this->table[0]);
			else $row = $this->userDisplayRow($name, $schedule, $this->table[0]);
			
			array_push($this->table, $row);
		}

		array_push($this->table, $this->genSubmitRow());

		array_push($this->table, $this->genTotalRow());

		$this->logMsg(SUCCESS, 'internal table constructed');
	}

	//gets the column headers from the internal data set
	function getColumnHeaders(){
		$columnHeaders = array();

		//fixed headers
		$columnHeaders[0] = 'User';
		$columnHeaders[1] = 'Action';

		//two headers were added, offsett to make up for it
		$count = 2;
		foreach ($this->schedule as $date => $times) {

			//convert the full date to a more readable version
			$converter = strtotime($date); 
			$formattedDate =date('l', $converter);
			$formattedDate = $formattedDate.'</br>'.date('m-d-y', $converter);

			foreach($times as $time){// #2dimensionlswag

				//convert the international time to AM/PM
				$converter = strtotime($time); 
				$formattedTime = date("g:i A", $converter);

				$columnHeaders[$count] = $formattedDate.'</br>'.$formattedTime;
				$count++;
			}
		}
		return $columnHeaders;
	}

	//formats the user wor for editing (dynamic) display
	function userEditRow($name, $schedule, $columnHeaders){
		//offset to the second row
		$row = array();

		//add the first two inputs
		$row[0] = $this->translator->markupAttributes('input', '',array('type'=>'text', 'name'=>'user', 'value'=>$name));
		$row[1] = $this->translator->markupAttributes('input','',array('type'=>'submit', 'name'=>'submit', 'value'=>'Submit'));
		
		//add a check box input for each entry, checking those that are stored that way
		for($count = 2; $count < sizeof($this->table[0]); $count++){
			if(!in_array($count, $schedule)){
				$row[$count] = $this->translator->markupAttributes('input',  '',array('type'=>'checkbox', 'name'=>$count));
			}
			else {
				$row[$count] = $this->translator->markupAttributes('input',  '',array('type'=>'checkbox', 'name'=>$count, 'checked'=>'checked'));
			}
		}

		return $row;
	}

	//formats teh user row for standard (static) display
	function userDisplayRow($name, $schedule, $columnHeaders){
		//offset to the second row
		$row = array();

		//set row header
		$row[0] = $name;
		//set aside the next column for edit options
		if($this->canEdit($name)){
			$row[1] = $this->translator->markupAttributes('input','',array('type'=>'submit', 'name'=>'edit', 'value'=>'Edit'));
		}
		else $row[1] = ' ';

		for($column = 2; $column < sizeof($columnHeaders); $column++){// #2dimensionlswag
			if(in_array($column, $schedule)){
				$row[$column] = '&#x2713';
			}
			else $row[$column] = '';
		}
		return $row;

	}

	//construct and return the submit row
	function genSubmitRow(){
		$row = array();
		array_pad($row, sizeof($this->table[0]), '');

		if($this->state->isNewEntry()){
			$textField = 'text';
			$row[0] = $this->translator->markupAttributes('input', '',array('type'=>'text', 'name'=>'user'));
			$row[1] = $this->translator->markupAttributes('input','',array('type'=>'submit', 'name'=>'submit', 'value'=>'Submit'));


			for($count = 2; $count < sizeof($this->table[0]); $count++) $row[$count] = $this->translator->markupAttributes('input',  '',array('type'=>'checkbox', 'name'=>$count));
		}
		else{
			$row[0] = '';
			$row[1] = $this->translator->markupAttributes('input','',array('type'=>'submit', 'name'=>'new', 'value'=>'New'));

			for($count = 2; $count < sizeof($this->table[0]); $count++) $row[$count] = '';
		}

		return $row;
	}

	//count, format, and return the 'total' row
	function genTotalRow(){
		//set our row to be the size of the first row, each cell set to 0
		$row = array();
		$row = array_pad($row, sizeof($this->table[0]), 0);

		//sum up the number of users per column
		foreach ($this->table as $rows) {
			foreach ($rows as $index => $cell) {
				if($cell == '&#x2713') 	{
					$row[$index]=$row[$index]+1;
				}
			}
		}
		$row[0] = 'Total';
		return $row;
	}


//--------  HTML FORMATTING  -----------------------------------------------------------------------------------------------------------

	//treturns a fomrmatted dable output for the internal data
	function getTableHtml(){
		return $this->translator->basicTable($this->table, 'test');
	}

//--------  SPECIAL IMPUT HANDLERS  ----------------------------------------------------------------------------------------------------
	
	//checks post values tand handles them accordingly (generally by manipulating state)
	function checkInput(){		

		//posting new sets the state to 'newentry' for the table sonstruction to handle later
		if(isset($_POST['new'])){
			$this->state->setNewEntry();
			$this->refreshTable();
		}
		//similar to posting new, posting edit marks the row being edited for the table construction to handle later
		if(isset($_POST['edit'])){
			$this->currentEdit = $_POST['id'];
		}
		//submit constructs the form submitted and adds it to the table. it will be written to file at a later date
		//NOTE: cookies are also set here
		if(isset($_POST['submit'])){
			$user = $_POST['user'];

			//make sure the user doesnt already exist
			if(array_key_exists ($user, $this->users) && !$this->canEdit($user)){
				$this->logMsg(WARNING, 'User already exists, and you arent the creator. operation aborted');
				return;
			}
			//add a cookie to store the people we edited
			else{
				if(array_key_exists ($user, $this->users)) $this->logMsg(SUCCESS, 'The user was overwritten');
				setcookie($user, "created", time()+3600);
				$_COOKIE[$user] = "created";//add a fake cookie to allow editing of the new element
			}
		    
		    if($user==''){
				$this->logMsg(WARNING, 'No user name included in the submission, operation aborted');
				return;
			}

			//build the array
			$schedule = array();	
			foreach ($_POST as $key => $value) {
				if($key != 'submit' && $key != 'user')array_push($schedule, $key);
			}

			//add/update the edited row
			if($_POST['row'] < sizeof($this->users)) $this->users = deleteIndex($this->users, $_POST['row']-1); //remove the row if its in teh user set
			$this->users[$user] = $schedule; //add the row

			$this->updateUsers();
		}
	}

	//checks if the current poster has a cookie for the user identified
	function canEdit($user){
		if($_COOKIE && array_key_exists($user, $_COOKIE))return true;
		else return false;
	}

//--------  STATE & MESSAGE HANDLERS  --------------------------------------------------------------------------------------------------

	//checks if the session is marked for death
	function isFatal(){
		return $this->state->isFatal();
	}

	//messagehandler wrappers
	function logMsg($type, $msg){
		$this->msgHandler->logMsg($type, $msg);
	}

	//returns the formatted log
	function getLog(){
		$log = $this->msgHandler->getLog();
		return $this->translator->markupAttributes('div', $log, array('style'=>'overflow: scroll; height: 25%; width: 50%; margin: auto;'));
	}
	function lastLog(){return $this->msgHandler->last();}

	//display error and return an error code
	function markFatal($msg){
		$this->logMsg(ERROR, $msg);
		$this->state->markFatal();
	}
}

?>