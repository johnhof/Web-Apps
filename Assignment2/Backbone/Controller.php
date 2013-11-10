<?php

include_once './Backbone/Utilities/Utils.php';
include_once './Backbone/Model.php';

//--------------------------------------------------------------------------------------------------------------------------------------
//--------	CONTROLLER
//--------------------------------------------------------------------------------------------------------------------------------------

Class Controller{
	private $error = false;

	function __construct() {}


//--------  LOGIN FUNCTIONS  -----------------------------------------------------------------------------------------------------------

   function handleLogin($submit, $email, $pwd, $forgot){
      $view = new View();
      $msg = false;

      if($forgot){
         $pwdEmail = $_GET['email'];

         if($pwdEmail && emailPassword($pwdEmail)) $msg = 'Email Sent to '.$email;
      }
      else if($submit){
         $fail = true;

         if($email && $pwd){
            if(validateCreds($email, $pwd)){
               $fail = false;

               $_SESSION['email'] = $email;
               $_SESSION['pwd'] = $pwd;
              
               $view->setType('home', array());
               return $view;
            }
         }
         if($fail) $msg = 'Login Failed';
      }

      $view->setType('login', array(0=>$msg));

      return $view;
   }

//--------  HOME FUNCTIONS  ------------------------------------------------------------------------------------------------------------
   function handleMaker($model){
      $view = new View();

      if($model->finalize()) {
         $result = finalize($model->finalShcedule(), $model->makerEmail());
         $view->setType('home',$result);
         return $view;
      }

      if($model->isCreate()){
         $result = createSchedule($model->createName(), $model->makerEmail(), $model->createTimes(), $model->createUsers());
         $view->setType('home',array(0=>$result));
      }
      else if($model->isFinalize()){
         $result = finalizeSchedule($model->makerEmail());
         $view->setType('finalize',$result);
      }
      else {
         $action = $model->getOption();

         if($action == 'create'){
            $view->setType('create',array());
         }
         else if($action == 'finalize'){
            $view->setType('finalize',array());
         }
         else if($action == 'logout'){
            session_unset();
            $view->setType('login',array(0=>'You are now logged out')); 
         }
         else $view->setType('home',array());
      }

      return $view;
   }


//--------  SCHEDULE FUNCTIONS  --------------------------------------------------------------------------------------------------------

   function handleView($model){
      $view = new View();

      $result = getSchedule($model->viewSchedule(),$model->viewer(),$model->viewEdit());

      $view->setType('schedule',$result);

      return $view;  
   }

   function handleSubmit($model){
      $result = submitEntries($model->viewSchedule(), $model->viewer(), $model->submitValues());
   }
}

?>