<?php

include_once './Backbone/Utilities/Utils.php';
include_once './Backbone/Html/ViewRsc.php';

date_default_timezone_set('America/New_York');

//--------------------------------------------------------------------------------------------------------------------------------------
//--------	MODEL
//--------------------------------------------------------------------------------------------------------------------------------------

Class View{
	private $msgHandler;
	private $translator;

	private $html;
	private $controller;

	private $type;
	private $params;

	function __construct() {
		$this->msgHandler = new MessageHandler();
		$this->controller = new Controller();
		$this->htmlBody = '';
   	}

   	function generate(){
   		if($this->type == 'login')$this->genLogin();
   		else if($this->type == 'home')$this->genHome();
   		else if($this->type == 'create')$this->genCreate();
   		else if($this->type == 'finalize')$this->genFinal();
   		else if($this->type == 'schedule')$this->genSchedule();

         if(count($this->params) > 0 && $this->params[0]){
            $buffer = markup('h4', $this->params[0],array('style'=>'color:red;text-align:center;'));
            $buffer = $buffer.markup('div', $buffer,array('style'=>'height:50px;width:300px;margin:auto;'));
            $this->append($buffer);
         }
   	}

   	function displayHtml(){
   		echo $this->html;
   	}

   	function append($string){
   		$this->html = $this->html.$string;
   	}
   	function br(){
   		$this->append('</br>');
   	}

//--------	STATE FUNCTIONS  -----------------------------------------------------------------------------------------------------------

   	function setType($type, $params){
   		$this->type = $type;
   		$this->params = $params;
   	}

//--------	LOGIN FUNCTIONS  -----------------------------------------------------------------------------------------------------------

   	function genLogin(){
		   $buffer = getHtml('login');   		
   		$this->append($buffer);
   	}

//--------	HOME FUNCTIONS  ------------------------------------------------------------------------------------------------------------

   	function genHome(){
		$buffer = getHtml('home');
		$this->append($buffer);
   	}

//--------	SCHEDULE FUNCTIONS  --------------------------------------------------------------------------------------------------------

   	function genCreate(){
		$buffer = getHtml('create');
		$this->append($buffer);
   	}


//--------	SCHEDULE FUNCTIONS  --------------------------------------------------------------------------------------------------------

   	function genFinal(){
   		$this->append(markup('title','Finalize'));
   	}


//--------	SCHEDULE FUNCTIONS  --------------------------------------------------------------------------------------------------------

   	function genSchedule(){
   		$this->append(markup('title','Schedule'));
   	}



}

?>