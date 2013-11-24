<?php

//----  QUEUEING XML -------------------------------------------------------------------------

/* ```
queueXml

params
  word  : []
  
returns
  xml for queue state
``` */
function queueXml ($msg) {
  $queue = new SimpleXMLElement('<xml/>');
  $content = $queue->addChild('content');
  $content->addChild('subTitle', "Looking for an Opponent");
  $content->addChild('img', "./Core/Images/loading.gif");
  
  addMessage($content, $msg);
  
  //Header('Content-type: text/xml');
  return $queue;
}

//----  CORE GAME XML ------------------------------------------------------------------------

/* ```
coreGameXml

params
  word  : []
  state : 0-6
  
returns
  xml for base game play
``` */
function coreGameXml ($state, $msg) {
  $xml = new SimpleXMLElement('<xml/>');
  $xml->addChild('game-img', "./Core/Images/.".$state."_wrong.png");
  addMessage($xml, $msg);
  return $xml;
}

/* ```
guessXml

params
  xml     : SimpleXMLElement
  word    : []
  guessed : []
  maker   : boolean

returns
  xml appended with letter cells
``` */
function guessXml ($xml, $word, $guessed, $maker){
  foreach ($word as $index => $letter) {
    
    // mark if this letter has been guesed
    $found = in_array($letter, $guessed);
    
    // found letters display the same for maker and guesser
    if($found) {
      $letterBox = $xml->addChild('letter_'.$index, $letter);  
      $letterBox->addAttribute('class', 'letter.guessed');
    }
    // let maker see non-guessed letters
    else if ($maker) {
      $letterBox = $xml->addChild('letter_'.$index, $letter);  
      $letterBox->addAttribute('class', 'letter.not_guessed');      
    }
    // if its not found and a guesser, return an empty cell
    else {
      $letterBox = $xml->addChild('letter_'.$index, ' ');
      $letterBox->addAttribute('class', 'letter');      
    }
  }
  return $xml;
}

//----  MAKER XML ----------------------------------------------------------------------------

/* ```
makerGenWordXml

params
  msg     : []

returns
  xml for maker word selection
``` */
function makerGenWordXml ($msg){
  $xml = coreGameXml(0, $msg);
  $xml->addChild('subheader', 'Select a word');
  
  return $xml;
}

/* ```
makerGameXml

params
  word    : []
  guessed : []
  state   : 0-6
  msg     : []
  
returns
  xml for maker game play
``` */
function makerGameXml ($word, $guessed, $state, $msg) {
  $xml = guessXml(coreGameXml($state, $msg), $word, $guessed, true);
  $xml->addChild('subheader', 'The guesser is playing');
  
  return $xml;
}

//----  GUESSER XML --------------------------------------------------------------------------

/* ```
guesserGenWordXml

params
  msg     : []
  
returns
  xml for guesser wating for word selection
``` */
function guesserGenWordXml ($msg){
  $xml = coreGameXml(0, $msg);
  $xml->addChild('subheader', 'Waiting for a word');
  return $xml;
}

/* ```
guesserGameXml

params
  word    : []
  guessed : []
  state   : 0-6
  msg     : []
  
returns
  xml for guesser game play
``` */
function guesserGameXml ($word, $guessed, $state, $msg) {
  $xml->addChild('subheader', (7-$state).' guesses remaining');
  $xml = guessXml(coreGameXml($state, $msg), $word, $guessed, false);
  return $queue;
}

//----  BUTTON FORMATTING --------------------------------------------------------------------

/* ```
addButton

params
  xml      : SimpleXMLElement
  name     : ''
  color    : ''
  value    : ''
  funciton : 'foo()'
  
returns
  xml with button of the requested properties added
``` */
function addButton ($xml, $name, $color, $value, $function) {
  $letterBox = $xml->addChild($name, $letter);  
  $letterBox->addAttribute('class', 'standard_input false_button '.$color);
  $letterBox->addAttribute('name', $name);
  $letterBox->addAttribute('value', $value);
  $letterBox->addAttribute('onclick', $function);
  return $xml;
}

//----  MESSAGE ADDING -----------------------------------------------------------------------

function addMessage ($xml, $msg) {
  if ($msg) {
    $message = $xml->addChild('message', $msg);
    foreach ($message as $key => $text) { 
      $message->addChild($key, $text);
    }
  }
}

?>

'<input type="button" id="new" name="new" class="false_button" value="Try a new word" onclick="newGame()"/></br>'
                      .'<div id="guess_box" class="guess_box">'
                        .'Enter Guess: '
                        .'<input type="text" id="guess" name="guess"/>'
                        .'<input type="button" id="submit_guess" name="submit_guess" value="Submit" class="false_button_inactive" onclick="submitGuess()"/>'
                      .'</div></br>'
                      .'<a href="./Home.html" id="quit_button" class="false_button">Quit</a>';