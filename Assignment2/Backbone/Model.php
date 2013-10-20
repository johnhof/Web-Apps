<?php

include './Backbone/Utilities/Utils.php';

date_default_timezone_set('America/New_York');

//--------------------------------------------------------------------------------------------------------------------------------------
//--------	MODEL
//--------------------------------------------------------------------------------------------------------------------------------------

Class Model{
	private $msgHandler;
	private $error = false;

	private $translator;
	private $html;

	private $users;
	private $schedule;
	private $table;

	private $controller;
	private $view;

	private $request;

	function __construct() {
		$this->state = new State();
		$this->msgHandler = new MessageHandler();
		$this->translator = new HtmlTranslator();
		$this->controller = new Controller();
		$this->view = new View();
		$this->currentEdit = '';
		$this->html = '';

		$this->setRequest();
   	}


//--------	REQUEST UTILITIES  ---------------------------------------------------------------------------------------------------------

   	function setRequest(){$this->request = $this->controller->formatRequest();}
   	function getRequest(){return $this->request;}

   	function isHomeRequest(){return $this->request->getType() == 'get_home' ? true : false;}
   	function hasSession(){return $this->request->getSession();}

   	function getPostParam($key){return $this->request-> getPostValue($key);}

   	function getSessionEmail(){return $this->request->getSessionValue('email');}
   	function getSessionPwd(){return $this->request->getSessionValue('pwd');}

   	function getPostEmail(){return $this->request->getPostValue('email');}
   	function getPostPwd(){return $this->request->getPostValue('pwd');}

   	function forgotPwd(){return $this->request->getPostValue('forgot_password');}

   	function isNewSession(){return $this->request->getSession();}

//--------	LOGIN UTILITIES  -----------------------------------------------------------------------------------------------------------

   	function loggedIn(){
   		if(!$this->getSessionEmail() || !$this->getSessionPwd()) return false;
   		return $this->controller->validateCreds($this->getSessionEmail(), $this->getSessionPwd());
   	}


//--------	VIEW UTILITIES  ------------------------------------------------------------------------------------------------------------

   	function setLoginView(){
   		$view = $this->controller->handleLogin($this->hasSession(), $this->getPostEmail(), 
   												$this->getPostPwd(), $this->forgotPwd());
   	}

   	function setHomeView(){

   	}

   	function setScheduleView(){

   	}
}

?>