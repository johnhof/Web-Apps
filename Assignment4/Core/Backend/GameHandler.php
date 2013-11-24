<?php
session_start(); 


include_once './Helpers.php';
include_once './Core.php';

$req       = getValue('post', 'request');
$guess     = getValue('post', 'guess');
$newWord   = getValue('post', 'word');
$status_in = getValue('session', 'logged_In');
$userEmail = getValue('session', 'email');

//sanitize inputs
$userEmail = str_replace('"', '', $userEmail);
$guess     = str_replace("'", '', $guess);
$newWord   = str_replace('"', '', $newWord);

if ($status_in && $userEmail) {
  $response = null;
  
  if($request === 'user_state')
  
  
  respond($response);
}

function testQuery ($result, $string) {

  if($result) return;
  
  respond(coreGameXml(0, $string));
}

function respond ($response) {
  
  if($response) echo ($response); 
  
  else coreGameXml(0, '500: Inernal Server Error');
  
  exit;
}

?>