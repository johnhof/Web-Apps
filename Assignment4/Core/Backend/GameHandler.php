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
$newWord   = str_replace('"', '', $newWord);
  
  error_log('---------------------------------------------');

  error_log($req);
  error_log($status_in);
  error_log($letter);

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
  error_log('in-game');
  
  $guessed = getGuessed($email);
  $state = getState($email);
    
  if (!isGuesser($email)) {
    error_log('maker');
    
    if (!isWordselected($email)) {
      error_log('genword');
      respond(makerGenWordXml());
    }
    else {
      error_log('guessing');
      respond(makerGameXml($word, $guesses, $state, $msg));
    }
  }
  else {
    error_log('guesser');
    
    if (!isWordselected($email)) {
      error_log('genword');
      respond(guesserGenWordXml());
    }
    else {
      error_log('waiting');
      respond(guesserGameXml($word, $guesses, $state, $msg));
    }    
  }  
  error_log('unsatisfied request');
  
  respond($res);
}

function handleWordReq ($userEmail, $word){
  if(!inGame($userEmail) || isGuesser($userEmail) || !$word) respond();
  
  setWord($userEmail, $word);
  
  handleStateReq($email);
}

function handleGuessReq ($userEmail, $letter){
  if(!inGame($userEmail) || !isGuesser($userEmail) || !$letter) respond();
  
  $letter = $letter[0];
  error_log('letter guess: '.$letter);
  //submit guess
   
  respond($res);
}

function handleEnQueueReq ($userEmail){
  respond(enQueue($userEmail));  
}

function handleDeQueueReq ($userEmail){
  respond(deQueue($userEmail));
}

function handleQuitReq ($userEmail, $guess) {  
  if(!inGame($email)) respond(redirectXml());
  
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