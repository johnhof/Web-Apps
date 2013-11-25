<?php 
include_once './Helpers.php';
include_once './GameHelpers.php';
include_once './GameXML.php';
include_once './Core.php';
include_once './Queue.php';

function validateGame ($email) {
  // if the player is marked in game and state is -1
  //   kill game and mark a win 
  //   place into the queue and return queue XML, with a message that the other player has quit
}

/*
inGame

params
  email : ''
  
returns
  bool of whether or not the user is marked as playing a game
*/
function inGame ($email) {
  $result = query('select in_game from Users where email="'.$email.'"');
  
  testQuery($result, 'Failed to retrieve game state for '.$email);
  
  return $result[0][0];
}

function getGame($email){
  
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