<?php

include 'HtmlTranslator.php';
include 'FileHandler.php';
include 'utility.php';



define('MAIN_PAGE', 'Assignment1.php', true);
date_default_timezone_set('America/New_York');

//--------------------------------------------------------------------------------------------------------------------------------------
//--------	CONTROLLER
//--------------------------------------------------------------------------------------------------------------------------------------

Class controller{
	private $state;
	private $fileHandler;
	private $msgHandler;
	private $tableHandler;
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
		$this->tableHandler = new tableHandler();

		$this->currentEdit = '';
		$this->html = '';
   	}


}

?>