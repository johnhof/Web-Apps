<?php 
session_start(); 
include_once './Helpers.php';
include_once './GameHelpers.php';
include_once './GameXML.php';
include_once './Core.php';
include_once './QueueStateUtils.php';

function validateGame ($email) {
  // if the player is marked in game and state is -1
  //   kill game and mark a win 
  //   place into the queue and return queue XML, with a message that the other player has quit
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

function isGuesser ($email) {
  $guesser = query('SELECT guesser FROM Games WHERE email_1="'.$email.'" OR email_2="'.$email.'"');
  
  testQuery($guesser, 'Failed to retrieve current guesser');
  
  if($guesser[0][0] == $email) return true;
  return false;
}

function isWordSelected ($email) {
  $word = query('SELECT guesser FROM Games WHERE email_1="'.$email.'" OR email_2="'.$email.'"');
  
  if($word && $word[0][0]) $word = $word[0][0];
  
  return $word;
}

function setWord ($email, $word) {
  query('UPDATE Games SET word="'.$word.'" WHERE email_1="'.$email.'" OR email_2="'.$email.'"');
}


function getGamesWon() {
  $won = query('SELECT won FROM Users WHERE email="'.getValue('session', 'email').'"');
  return $won[0][0];
}

function getGamesPlayed() {
  $played = query('SELECT played FROM Users WHERE email="'.getValue('session', 'email').'"');
  return $played[0][0];
}

function getGuessed ($email) {
  $guessed = query('SELECT guessed FROM Games WHERE email_1="'.$email.'" OR email_2="'.$email.'"');
  return $guessed[0][0];  
}

function getState ($email) {
  $state = query('SELECT state FROM Games WHERE email_1="'.$email.'" OR email_2="'.$email.'"');
  return $state[0][0];  
}

function oldGuess ($email, $letter) {
  error_log(strpos(getGuessed($email), $letter));
  return strpos(getGuessed($email), $letter) ? true : false;  
}

function processBadGuess ($email, $letter) {
  $guessed = getGuessed($email);
  
  error_log('UPDATE Games SET guessed="'.$guessed.$letter.'" WHERE email_1="'.$email.'" OR email_2="'.$email.'"');
  
  // add the quessed letter to the previous guessed list
  query('UPDATE Games SET guessed="'.$guessed.$letter.'" WHERE email_1="'.$email.'" OR email_2="'.$email.'"');

  // increment game state
  query('UPDATE Games SET state=state+1 WHERE email_1="'.$email.'" OR email_2="'.$email.'"');
}
?>