<?php
include_once './Helpers.php';
include_once './GameHelpers.php';
include_once './GameXML.php';
include_once './Core.php';
include_once './Queue.php';
include_once './DBListeners.php';

/*
queuedStateReq

params
  email : ''
  
returns
  sets up dame if possible. if the user was moved to a game, return the appropriate xml
*/
function queuedStateReq ($email) {
  // if this user is in a game, exit queue state
  $in_game = query('SELECT in_game FROM Users WHERE email="'.$email.'"');
  
  //if we are in a game, dequeue for good measure and move to statehandler
  if ($in_game && $in_game[0][0]) {
    deQueue($email);
    handleStateReq($email);    
  }
  
  //move to the queue
  enQueue($email);
  
  //check if a player is in the queue who is not this player
  $player = query('SELECT * FROM Queue WHERE email!="'.$email.'"');
  
  //if someone is found
  if($player) {
    deQueue($player[0][0]);
    deQueue($email);
    
    // initialize game
    query('INSERT INTO Games VALUES("'.$email.'", "'.$player.'", "'.$email.'", 0, "", null)');
    
    // set players to playing state
    query('UPDATE Users SET in_game=1 WHERE email="'.$email.'"');
    query('UPDATE Users SET in_game=1 WHERE email="'.$player.'"');
    
    handleStateReq($email);
  }
}

/*
inQueue

params
  email : ''
  
returns
  bool of whether or not the user is marked as in the queue
*/
function inQueue ($email) {
  $result = query('SELECT * FROM Queue WHERE email="'.$email.'"');
  return $result ? true : false;
}

/*
deQueue

params
  email : ''
  
returns
  removes a player from the queue
*/
function deQueue ($email) {
  if (!inQueue($email)) return 'failure';
  $result = query('DELETE FROM Queue WHERE email="'.$email.'"');
  return 'success';
}

/*
enQueue

params
  email : ''
  
returns
  adds a player to the queue
*/
function enQueue ($email) {
  if (inQueue($email)) return 'failure';
  $result = query('INSERT INTO Queue VALUES("'.$email.'")');
  return 'success';
}

function pop() {
}

?>