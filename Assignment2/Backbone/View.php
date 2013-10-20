<?php

include './Backbone/Utilities/Utils.php';

date_default_timezone_set('America/New_York');

//--------------------------------------------------------------------------------------------------------------------------------------
//--------	MODEL
//--------------------------------------------------------------------------------------------------------------------------------------

Class View{
	private $msgHandler;
	private $translator;

	private $htmlBody;
	private $controller;

	function __construct() {
		$this->msgHandler = new MessageHandler();
		$this->translator = new HtmlTranslator();
		$this->controller = new Controller();
		$this->htmlBody = '';
   	}

//--------	LOGIN FUNCTIONS  -----------------------------------------------------------------------------------------------------------

   	function initLogin(){
   	}

   	function showSchedule(){

   		$this->translator->link(HOST, 'Are you trying to edit the schedule? CLick here to log in');
   	}

}

?>