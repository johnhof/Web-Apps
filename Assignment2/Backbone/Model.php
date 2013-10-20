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
		$this->request = formatRequest();
   }


//--------	REQUEST UTILITIES  ---------------------------------------------------------------------------------------------------------

   	function setRequest($request){$this->request = $request;}
   	function getRequest(){return $this->request;}

   	function isMakerRequest(){return $this->request->getType() == 'maker' ? true : false;}
   	function hasSession(){return $this->request->getSession();}

   	function getPostParam($key){return $this->request-> getPostValue($key);}

      //SESSION wrappers
   	function getSessionEmail(){return $this->request->getSessionValue('email');}
   	function getSessionPwd(){return $this->request->getSessionValue('pwd');}

      //GET wrappers
   	function getEmail(){return $this->request->getQueryParam('email');}
      function getPwd(){return $this->request->getQueryParam('pwd');}
      function getOption(){return $this->request->getQueryParam('option');}
   	function forgotPwd(){return $this->request->getQueryParam('forgot_password');}
   	function isSubmission(){return $this->request->isSubmission();}

//--------	LOGIN UTILITIES  -----------------------------------------------------------------------------------------------------------

   	function loggedIn(){
   		if(!$this->getSessionEmail() || !$this->getSessionPwd()) return false;
   		return validateCreds($this->getSessionEmail(), $this->getSessionPwd());
   	}


//--------	VIEW UTILITIES  ------------------------------------------------------------------------------------------------------------

   	function setLoginView(){
   		$this->view = $this->controller->handleLogin($this->isSubmission(), $this->getEmail(), $this->getPwd(), $this->forgotPwd());
         return $this->view;
   	}

   	function setHomeView(){
         $this->view =  $this->controller->handleHome($this->getOption());
         return $this->view;
   	}

   	function setScheduleView(){

   	}

      function getView(){
         return $this->view;
      }
}

?>