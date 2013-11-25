<?php
include_once './Helpers.php';
include_once './GameHelpers.php';
include_once './GameXML.php';
include_once './Core.php';
include_once './Queue.php';
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
    // long polling requests
    case 'queued'      : queuedStateReq($userEmail);
    // case 'guessing'    : guessStateReq($userEmail);
    // case 'watching'    : watchStateReq($userEmail);
  }
}

respond(redirectXml());
  
function handleStateReq ($email) {
  if (!inGame($email)) {
    enQueue($email);
    respond(queueXml(''));
  }
  respond(null);
  // validate game
  
    
  //find who the guesser is
  $guesser = query('SELECT guesser FROM Games WHERE email_1="'.$email.'" OR email_2="'.$email.'"');
    
  if (isGuesser($email)) {
    if (!isWordselected()) {
      respond(makerGenWordXml());
    }
    else {
      respond(makerGameXml());
    }
  }
  else {
    if (!isWordselected()) {
      respond(guesserGenWordXml());
    }
    else {
      respond(guesserGameXml());
    }    
  }
  // if the player is in game and the state is 0-6
  //   if the player is a maker
  //     return maker XML
  //   if the player is a guess
  //     return guesser XMl
  
  
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

?>