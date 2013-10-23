<?php

include_once './Backbone/Utilities/Utils.php';

date_default_timezone_set('America/New_York');

//--------------------------------------------------------------------------------------------------------------------------------------
//--------	MODEL
//--------------------------------------------------------------------------------------------------------------------------------------

Class Model{
	private $msgHandler;
	private $error = false;

	private $users;
	private $schedule;
	private $table;

	private $controller;
	private $view;

	private $request;

	function __construct() {
		$this->controller = new Controller();
		$this->view = new View();
		$this->currentEdit = '';
		$this->html = '';
		$this->request = new Request();
      $this->request->format();
   }


//--------	REQUEST UTILITIES  ---------------------------------------------------------------------------------------------------------

   	function setRequest($request){$this->request = $request;}
   	function getRequest(){return $this->request;}

   	function isMakerRequest(){return $this->request->getType() == 'maker' ? true : false;}
   	function hasSession(){return $this->request->getSession();}

   	function getPostParam($key){return $this->request-> getPostValue($key);}

      //SESSION wrappers
   	function makerEmail(){return $this->request->getSessionValue('email');}
   	function makerPwd(){return $this->request->getSessionValue('pwd');}

      //GET wrappers
   	function getEmail(){return $this->request->getQueryParam('email');}
      function getPwd(){return $this->request->getQueryParam('pwd');}
      function getOption(){return $this->request->getQueryParam('option');}
   	function forgotPwd(){return $this->request->getQueryParam('forgot_password');}
   	function isSubmission(){return $this->request->isSubmission();}
      //create parameters
      function isCreate(){return $this->request->getQueryParam('create');}
      function createName(){return $this->request->getQueryParam('name');}
      function createTimes(){return $this->request->getQueryParam('times');}
      function createUsers(){return $this->request->getQueryParam('users');}
      //view parameters
      function viewSchedule(){return $this->request->getQueryParam('schedule');}
      function viewer(){return $this->request->getQueryParam('viewer');}
      function viewEdit(){return $this->request->getQueryParam('edit');}
      function viewSubmit(){return $this->request->getQueryParam('submit');}
      function submitValues(){return $this->request->getPostValues();}
      //view finalize
      function isFinalize(){return $this->request->getQueryParam('option') == 'finalize';}
      function finalShcedule(){return $this->request->getQueryParam('makeFinal');}
      function finalize(){return $this->request->getQueryParam('finalize') == 'Finalize';}

//--------	LOGIN UTILITIES  -----------------------------------------------------------------------------------------------------------

   	function loggedIn(){
   		if(!$this->makerEmail() || !$this->makerPwd()) return false;
   		return validateCreds($this->makerEmail(), $this->makerPwd());
   	}


//--------	VIEW UTILITIES  ------------------------------------------------------------------------------------------------------------

   	function setLoginView(){
   		$this->view = $this->controller->handleLogin($this->isSubmission(), $this->getEmail(), $this->getPwd(), $this->forgotPwd());
         return $this->view;
   	}

   	function setMakerView(){
         $this->view =  $this->controller->handleMaker($this);
         return $this->view;
   	}

   	function setScheduleView(){
         if($this->viewSubmit() && $this->submitValues()) $this->controller->handleSubmit($this);
         $this->view =  $this->controller->handleView($this);
         return $this->view;
   	}

      function getView(){
         return $this->view;
      }
}

?>