<?php

queueXml('');

//----  QUEUEING XML -------------------------------------------------------------------------
/*
queueXml

params
  word  : []
  
returns
  xml for queue state
*/
function queueXml ($msg) {
  $queue = new SimpleXMLElement('<xml></xml>');
    
  $content = $queue->addChild('content');
  $content->addChild('subTitle', "Looking for an Opponent");
  $content->addChild('img', "./Core/Images/loading.gif");
  
  addMessage($content, $msg);
  
  //Header('Content-type: text/xml');
  
  
  echo $queue->asXml();
  exit;
  return $queue;
}

//----  CORE GAME XML ------------------------------------------------------------------------

/*
coreGameXml

params
  word  : []
  state : 0-6
  
returns
  xml for base game play
*/
function coreGameXml ($state, $msg) {
  $xml = new SimpleXMLElement('<xml/>');
  
  $xml->addChild('game-img', "./Core/Images/.".$state."_wrong.png");
  
  addMessage($xml, $msg);
  
  Header('Content-type: text/xml');
  return $xml;
}

/*
guessXml

params
  xml     : SimpleXMLElement
  word    : []
  guessed : []
  maker   : bool

returns
  xml appended with letter cells
*/
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
  
  Header('Content-type: text/xml');
  return $xml;
}
/*
gamOverXml
params
  word    : ''
  guessed : bool
  wins    : #
  total   : #
  msg     : ''
  
returns
  xml for universal game over
*/
function gamOverXml ($word, $guessed, $wins, $total, $msg) {
  $xml = new SimpleXMLElement('<xml/>');
 
  $xml->addChild('final-result', $guessed);
  $xml->addChild('word', $word);
  $xml->addChild('wins', $wins);
  $xml->addChild('losses', $total - $wins);
  $xml->addChild('total', $total);
  
  $xml = addButton($xml, 'rematch', 'green', 'Play Again', 'rematch()');
  
  $buttonSeparator = $xml->addChild('buttonSeparator');
  $buttonSeparator = addButton($buttonSeparator, 'queue', 'grey', 'Return to Queue', 'queue()');
  $buttonSeparator = addButton($buttonSeparator, 'home', 'grey', 'Return Home', 'home()');
  
  addMessage($xml, $msg);
  
  Header('Content-type: text/xml');
  return $xml;
}

//----  MAKER XML ----------------------------------------------------------------------------

/*
makerGenWordXml

params
  msg     : []

returns
  xml for maker word selection
*/
function makerGenWordXml ($msg){
  $xml = coreGameXml(0, $msg);
  $xml->addChild('subheader', 'Select a word');
  
  $xml = addButton($xml, 'setWord', 'green', 'Submit Word', 'setWord()');
  
  $buttonSeparator = $xml->addChild('buttonSeparator');
  $buttonSeparator = addButton($buttonSeparator, 'queue', 'red', 'Return to Queue', 'queue()');
  $buttonSeparator = addButton($buttonSeparator, 'home', 'red', 'Quit', 'home()');
  
  Header('Content-type: text/xml');
  return $xml;
}

/*
makerGameXml

params
  word    : []
  guessed : []
  state   : 0-6
  msg     : []
  
returns
  xml for maker game play
*/
function makerGameXml ($word, $guessed, $state, $msg) {
  $xml = guessXml(coreGameXml($state, $msg), $word, $guessed, true);
  
  $xml->addChild('subheader', 'The guesser is playing');
  
  Header('Content-type: text/xml');
  return $xml;
}

//----  GUESSER XML --------------------------------------------------------------------------

/*
guesserGenWordXml

params
  msg     : []
  
returns
  xml for guesser wating for word selection
*/
function guesserGenWordXml ($msg){
  $xml = coreGameXml(0, $msg);
  
  $xml->addChild('subheader', 'Waiting for a word');
  
  $xml = addButton($xml, 'setWord', 'inactive', 'Submit Word', 'setWord()');
  
  $buttonSeparator = $xml->addChild('buttonSeparator');
  $buttonSeparator = addButton($buttonSeparator, 'queue', 'red', 'Return to Queue', 'queue()');
  $buttonSeparator = addButton($buttonSeparator, 'home', 'red', 'Quit', 'home()');
  
  Header('Content-type: text/xml');
  return $xml;
}

/*
guesserGameXml

params
  word    : []
  guessed : []
  state   : 0-6
  msg     : []
  
returns
  xml for guesser game play
*/
function guesserGameXml ($word, $guessed, $state, $msg) {
  $xml->addChild('subheader', (7-$state).' changes remaining');
  
  $xml = guessXml(coreGameXml($state, $msg), $word, $guessed, false);
  $xml = addButton($xml, 'submitGuess', 'green', 'Submit Guess', 'submitGuess()');
  
  $buttonSeparator = $xml->addChild('buttonSeparator');
  $buttonSeparator = addButton($buttonSeparator, 'queue', 'red', 'Return to Queue', 'queue()');
  $buttonSeparator = addButton($buttonSeparator, 'home', 'red', 'Quit', 'home()');
  
  Header('Content-type: text/xml');
  return $xml;
}

//----  BUTTON FORMATTING --------------------------------------------------------------------

/*
addButton

params
  xml      : SimpleXMLElement
  name     : ''
  color    : ''
  value    : ''
  funciton : 'foo()'
  
returns
  xml with button of the requested properties added
*/
function addButton ($xml, $name, $color, $value, $function) {
  $button = $xml->addChild($name, $letter);  
  
  $button->addAttribute('class', 'standard_input false_button '.$color);
  $button->addAttribute('name', $name);
  $button->addAttribute('value', $value);
  $button->addAttribute('onclick', $function);
  
  Header('Content-type: text/xml');
  return $xml;
}

//----  TEXTFIELD FORMATTING -----------------------------------------------------------------

/*
addTextField

params
  xml      : SimpleXMLElement
  name     : ''
  value    : ''
  
returns
  xml with textField of the requested properties added
*/
function addTextField ($xml, $name, $value) {
  $textField = $xml->addChild($name, $letter);  
  
  $textField->addAttribute('class', 'standard_input text_field');
  $textField->addAttribute('name', $name);
  $textField->addAttribute('placeholder', $value);
  
  Header('Content-type: text/xml');
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
?>