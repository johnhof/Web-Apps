<?php

//--------------------------------------------------------------------------------------------------------------------------------------
//--------	REQUEST ENCAPSULATION
//--------------------------------------------------------------------------------------------------------------------------------------

Class Request{
	private $type;
	private $query;
	private $post;
	private $session;

	function __construct() {}

   function getType(){
  		return $this->type;
  	}

  	function getQueryParams(){
      if(!$this->post) return false;
  		return $this->query;
  	}

  	function getQueryParam($key){
      if(!$this->query || !isset($this->query[$key])) return false;
  		return $this->query[$key];
  	}

  	function getPost(){
      if(!$this->post) return false;
  		return $this->post;
  	}

  	function getPostValue($key){
      if(!$this->post || !isset($this->post[$key])) return false;
  		return $this->post[$key];
  	}

  	function getSession(){
      if(!$this->session) return false;
  		return $this->session;
  	}

  	function getSessionValue($key){
      if(!$this->session || !isset($this->session[$key])) return false;
  		return $this->session[$key];
  	}

    function isSubmission(){
      return $this->submit;
    }


  
  function format(){
    $this->type = isset($_GET['view']) ? 'viewer' : 'maker'; 
    $this->query = $_GET;
    $this->post = isset($_POST) ? $_POST : false;
    $this->session = isset($_SESSION) ? $_SESSION : false;
    $this->submit = (isset($_GET['submit']));
  }

}
?>