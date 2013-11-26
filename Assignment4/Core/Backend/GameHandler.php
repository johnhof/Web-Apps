<?php
include_once './Helpers.php';
include_once './GameHelpers.php';
include_once './GameXML.php';
include_once './Core.php';
include_once './QueueStateUtils.php';
include_once './GameStateUtils.php';
include_once './DBListeners.php';

$req       = getValue('post', 'request');
$guess     = getValue('post', 'guess');
$newWord   = getValue('post', 'word');
$status_in = getValue('session', 'logged_In');
$userEmail = getValue('session', 'email');

//sanitize inputs
$userEmail = str_replace('"', '', $userEmail);
$guess     = str_replace("'", '', $guess);
$newWord   = str_replace('"', '', $newWord);
  
  error_log('---------------------------------------------');

  error_log($req);
  error_log($status_in);

if ($status_in && $userEmail) {  
  switch ($req) {
    case 'user_state'  : handleStateReq($userEmail);
    case 'set_word'    : handleWordReq($userEmail, $newWord);
    case 'guess_letter': handleGuessReq($userEmail, $guess);
    case 'quit'        : handleQuitReq($userEmail, $guess);
    case 'rematch'     : handleRematchReq($userEmail, $guess);
    case 'enQueue'     : handleEnQueueReq($userEmail);
    case 'deQueue'     : handleDeQueueReq($userEmail);
    // polling requests
    case 'queued'      : queuedStateReq($userEmail);
    case 'in_game'     : handleStateReq($userEmail);
  }
}

respond(redirectXml());
  
function handleStateReq ($email) {
  if (!inGame($email)) {
    enQueue($email);
    respond(queueXml(''));
  } 
  error_log('in-game');
    
  if (isGuesser($email)) {
    error_log('guesser');
    
    if (!isWordselected()) {
      error_log('genword');
      respond(makerGenWordXml());
    }
    else {
      error_log('guessing');
      respond(makerGameXml());
    }
  }
  else {
    error_log('maker');
    
    if (!isWordselected()) {
      error_log('genword');
      respond(guesserGenWordXml());
    }
    else {
      error_log('waiting');
      respond(guesserGameXml());
    }    
  }  
  error_log('unsatisfied request');
  
  respond($res);
}

function handleWordReq($userEmail, $newWord){
  //if the word is not null, and state != -1
  
  respond($res);
}

function handleGuessReq($userEmail, $guess){
  //if the word is not null, and state != -1
  
  respond($res);
}

function handleEnQueueReq($userEmail){
  respond(enQueue($userEmail));  
}

function handleDeQueueReq($userEmail){
  respond(deQueue($userEmail));
}


function queuedStateReq ($email) { 
  //if we are in a game, dequeue for good measure and move to statehandler
  if (inGame($email)) {
    deQueue($email);
    return handleStateReq($email);   
  }
  
  //move to the queue
  enQueue($email);
  
  //check if a player is in the queue who is not this player
  $player = popOther($email);
  
  //if someone is found
  if($player) {
    newGame($email, $player);
    return handleStateReq($email);
  }
  
  header("HTTP/1.0 408 Request Timeout");
  exit;
}

?>