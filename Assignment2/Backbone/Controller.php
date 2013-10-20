<?php

include './Backbone/Utilities/Utils.php';
include_once './Backbone/Model.php';

define('HOST', 'http'.(empty($_SERVER['HTTPS'])?'':'s').'://'.$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'], true);
date_default_timezone_set('America/New_York');

//--------------------------------------------------------------------------------------------------------------------------------------
//--------	CONTROLLER
//--------------------------------------------------------------------------------------------------------------------------------------

Class Controller{
	private $error = false;
   private $db;

	function __construct() {
      $this->db = new DBWrapper('localhost', 'root', '', 'mysql');
   }


   	function formatRequest(){

   		$type = isset($_REQUEST['schedule']) ? 'get_schedule' : 'get_home'; 
   		$query = $_REQUEST;
   		$post = isset($_POST) ? $_POST : false;
   		$session = isset($_SESSION) ? $_SESSION : false;


		$request = new Request($type, $query, $post, $session);

   		return $request;
   	}

//--------  LOGIN FUNCTIONS  -----------------------------------------------------------------------------------------------------------
//
   	function handleLogin($session, $email, $pwd, $forgot){
         $view = new View();

         if(!$session){
          session_start();

            $loginSuccess = false;

            if($email && $pwd){
               /*QUERY DB*/
               if(false){
                  //if successful, set the email and password in the session
                  $loginSuccess = true;
               }
            }
            else{
               
            }

            if($forgot){
               $this->forgotPassword();
               return;
            }
            else{
               //login failed
            }
         }

         echo 'testing';
   	}

      function validateCreds($email, $pwd){
         $result = $this->db->simpleSelect('Makers', ['*'], 'email="'.$email.'" and password="'.$pwd.'"');
         print_r($result);
         //query the DB
      }

//--------  SCHEDULE FUNCTIONS  ----------------------------------------------------------------------------------------------------------
   	function viewSchedule(){
   		echo 'viewing schedule';
   	}

      function forgotPassword(){
         //TODO: email
      }
}

?>