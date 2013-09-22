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

		$this->html = '';
   	}

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
		//TODO: cookie/token setup

	}


//--------  DATA WRAPPERS  -------------------------------------------------------------------------------------------------------------
//NOTE: NAMING IS LEFT ABSTRACT TO MINIMIZE DISRUPTION WHEN CHANGING TO A DATABASE SOURCE 
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

	function refreshDataSet(){
		$this->LoadFiles();

		$this->getUsers();
		$this->getSchedule();

		$this->fileHandler->closeAll();
	}

	function getUsers(){
		$this->users = $this->fileHandler->parseRows($this->userFile, '^', '|');
		$this->logMsg(SUCCESS, 'users generated');

	}
	function getSchedule(){
		$this->schedule = $this->fileHandler->parseRows($this->scheduleFile, '^', '|');	
		$this->logMsg(SUCCESS, 'schedule generated');	
	}

//--------  TABLE CONSTRUCTION  --------------------------------------------------------------------------------------------------------

	//builds the rable from scratch
	function buildTable($schedule,$users){
		$this->refreshDataSet();
		$this->refreshTable();
	}

	//builds the table from the internal data representation
	function refreshTable(){
		$this->table = array();

		//set the column headers
		$this->table[0] = $this->getColumnHeaders();

		//add a row for each user
		foreach ($this->users as $name => $schedule) {
			$row = $this->genUserRow($name, $schedule, $this->table[0]);
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

	//generates a row entry for a passed in user
	function genUserRow($name, $schedule, $columnHeaders){
		//offset to the second row
		$row = array();

		//set row header
		$row[0] = $name;

		//set aside the next column for edit options
		$row[1] = ' ';

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
			$row[0] = $textField;
			$row[1] = $this->translator->markupAttributes('input','',array('type'=>'submit', 'name'=>'submit', 'value'=>'Submit'));

			for($count = 2; $count < sizeof($this->table[0]); $count++) $row[$count] = 'box';
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

	function getTableHtml(){
		return $this->translator->basicTable($this->table, 'test');
	}

//--------  STATE & MESSAGE HANDLERS  --------------------------------------------------------------------------------------------------
	
	function checkInput(){
		if(isset($_POST['new'])){
			$this->state->setNewEntry();
			$this->refreshTable();
		}
		if(isset($_POST['edit'])){
			//$this->convertToForm();
		}
		if(isset($_POST['submit'])){
			//attempt to open the user file for writing
			$this->userFile = $this->fileHandler->wOpen('users.txt');
			if($this->userFile == NO_LOCK) $this->logMsg(WARNING, 'User file is locked, try editing later');

		}
	}
	function isFatal(){
		return $this->state->isFatal();
	}

	//messagehandler wrappers
	function logMsg($type, $msg){
		$this->msgHandler->logMsg($type, $msg);
	}

	function getLog(){return $this->msgHandler->getLog();}
	function lastLog(){return $this->msgHandler->last();}

	//display error and return an error code
	function markFatal($msg){
		$this->logMsg(ERROR, $msg);
		$this->state->markFatal();
	}
}

?>