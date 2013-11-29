<?php
include_once './Helpers.php';
include_once './GameHelpers.php';
include_once './GameXML.php';
include_once './Core.php';
include_once './QueueStateUtils.php';
include_once './GameStateUtils.php';
include_once './DBListeners.php';

$req       = getValue('post', 'request');
$word      = getValue('post', 'word');
$letter    = getValue('post', 'letter');
$status_in = getValue('session', 'logged_In');
$userEmail = getValue('session', 'email');

//sanitize inputs
$userEmail = str_replace('"', '', $userEmail);
$word      = str_replace("'", '', $word);
$letter    = str_replace("'", '', $letter);

$word   = explode(' ', trim($word));
$word   = strtoupper(substr($word[0], 0, 14));
$letter = strtoupper($letter[0]);

groomDB();

if ($status_in && $userEmail) {  
  switch ($req) {
    case 'user_state'  : handleStateReq($userEmail);
    case 'set_word'    : handleWordReq($userEmail, $word);
    case 'guess_letter': handleGuessReq($userEmail, $letter);
    case 'quit'        : handleQuitReq($userEmail);
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
  
  validateGame($email);
  
  $word    = getWord($email);
  $guessed = getGuessed($email);
  $state   = getState($email);
  
  //if game state is 7, finalize the game, send an alert
  if (getState($email) >= 7) {
    finalizeGame($email, false);
  }
  
  if (!isGuesser($email)) {    
    if (!isWordSelected($email)) {
      respond(makerGenWordXml($state));
    }
    else {
      respond(makerGameXml($word, $guessed, $state, $msg));
    }
  }
  else {    
    if (!isWordSelected($email)) {
      respond(guesserGenWordXml($state));
    }
    else {
      respond(guesserGameXml($word, $guessed, $state, $msg));
    }    
  }  
    
  respond($res);
}

function handleWordReq ($userEmail, $word) {
  if(!inGame($userEmail) || isGuesser($userEmail) || !$word || !ctype_alpha($word)) respond();
  
  setWord($userEmail, $word);
  
  handleStateReq($email);
}

function handleGuessReq ($userEmail, $letter) {
  if(!inGame($userEmail) || !isGuesser($userEmail) || !$letter || !getWord($userEmail) || !ctype_alpha($letter)) respond();
  
  //if the guess is in the list, dont process it
  if(oldGuess($userEmail, $letter)) respond();
    
  recordGuess($userEmail, $letter);
  
  if (validGuess($userEmail, $letter)) {
    if (testForWin($userEmail)) {
      finalizeGame($userEmail, true);
    }
  }
  else {
    incrementState($userEmail);
    
    if (getState($userEmail) >= 7) {
      finalizeGame($userEmail, false);
    }
  }   
  
  handleStateReq($userEmail);
}

function handleEnQueueReq ($userEmail){
  respond(enQueue($userEmail));  
}

function handleDeQueueReq ($userEmail){
  respond(deQueue($userEmail));
}

function handleQuitReq ($userEmail, $guess) {    
  if(!inGame($userEmail)) respond(redirectXml());
  
  finalizeGame($userEmail, false);
  
  endGame($userEmail);
  
  respond(redirectXml());
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