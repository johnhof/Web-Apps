<?php 
session_start(); 
include_once './Helpers.php';
include_once './GameHelpers.php';
include_once './GameXML.php';
include_once './Core.php';
include_once './QueueStateUtils.php';

function groomDB () {  
  query('DELETE FROM Queue WHERE email=""');  
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
  
  if($guesser[0][0] == $email) return true;
  return false;
}

function isWordSelected ($email) {
  $word = query('SELECT word FROM Games WHERE email_1="'.$email.'" OR email_2="'.$email.'"');
  return $word[0][0];
}

function setWord ($email, $word) {
  query('UPDATE Games SET word="'.$word.'" WHERE email_1="'.$email.'" OR email_2="'.$email.'"');
  query('UPDATE Games SET state=0 WHERE email_1="'.$email.'" OR email_2="'.$email.'"');
}

function getWord ($email) {
  $word = query('SELECT word FROM Games WHERE email_1="'.$email.'" OR email_2="'.$email.'"');
  return $word[0][0]; 
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
  return strpos(getGuessed($email), $letter) !== false ? true : false;  
}

function recordGuess ($email, $letter) {
  $guessed = getGuessed($email);
  query('UPDATE Games SET guessed="'.$guessed.$letter.'" WHERE email_1="'.$email.'" OR email_2="'.$email.'"');
}

function validGuess ($email, $letter) {
  $word = getWord($email);
  return strpos($word, $letter) !== false ? true : false;
}
  
//return true if the word was guessed
function testForWin ($email) {
  $guessed = getGuessed($email);
  $word    = getWord($email);
  
  
  $word = str_split($word);    
  for ($i = 0; $i < sizeof($word); $i++) {
    $letter = $word[$i];
    if(strpos($guessed, $letter) === false) return false;
  }
  return true;
}
?>