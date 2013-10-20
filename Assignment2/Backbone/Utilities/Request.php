<?php

//--------------------------------------------------------------------------------------------------------------------------------------
//--------	REQUEST ENCAPSULATION
//--------------------------------------------------------------------------------------------------------------------------------------

Class Request{
	private $type;
	private $query;
	private $post;
	private $session;

	function __construct($type, $query, $post, $session, $submit) {
		$this->type = $type;
		$this->query = $query;
		$this->post = $post;
		$this->session = $session;
    $this->submit = $submit;
   }

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
}
?>