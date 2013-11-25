<?php
include_once './Helpers.php';
include_once './GameHelpers.php';
include_once './GameXML.php';
include_once './Core.php';
include_once './Queue.php';
include_once './DBListeners.php';


function queuedStateReq ($email) {
  // if this user is in a game, exit queue state
  $in_game = query('SELECT in_game FROM Users WHERE email="'.$email.'"');
  
  //if we are in a game, dequeue for good measure and move to statehandler
  if ($in_game) {
    error_log('in game');
    deQueue($email);
    handleStateReq($email);    
  }
  
  //move tot the queue
  enQueue($email);
  
  $iter = 0;
  
  //poll for someone to enter the queue
  while(true){
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
    else {
      error_log('waiting: '.$iter++);
     sleep(2); 
    }
    
  }
  
  
  //otherwise, poll for participants
}

?>