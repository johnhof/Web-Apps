<?php

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
  
  $content = $queue->addChild('type', 'queued');
  $content = $queue->addChild('content');
  
  $content->addChild('subheading', "Waiting for an Opponent");
  
  $image = $content->addChild('img', '');
  $image->addAttribute('src', './Core/Images/loading.gif');
  
  $queue = addButton($queue, 'home', 'blue', 'Return Home', 'home()');
  
  addMessage($content, $msg);
  
  Header('Content-type: text/xml');
  return $queue->asXml();
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
  return coreGameXmlRaw($state, $msg)->asXml();
}
function coreGameXmlRaw ($state, $msg) {
  $xml = new SimpleXMLElement('<xml></xml>');
  
  $image = $xml->addChild('img', '');
  $image->addAttribute('src', './Core/Images/'.$state.'_wrong.png');
  
  $xml->addChild('state', $state);
  $xml->addChild('type', 'in_game');
  
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
function gameOverXml ($word, $guessed, $wins, $total, $msg) {
  $xml = new SimpleXMLElement('<xml></xml>');
 
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
  return $xml->asXml();
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
  $xml = coreGameXmlRaw(0, $msg);
  $xml->addChild('subheading', 'Select a word');
  
  $xml = addButton($xml, 'setWord', 'green', 'Submit Word', 'setWord()');
  
  $xml = addNavButtons($xml);
  $xml = addButton($xml, 'submit', 'green', 'Submit Guess', 'submitGuess()');
  
  Header('Content-type: text/xml');
  return $xml->asXml();
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
  $xml = guessXml(coreGameXmlRaw($state, $msg), $word, $guessed, true);
  
  $xml->addChild('subheading', 'The guesser is playing');
  
  $xml = addNavButtons($xml);
  
  Header('Content-type: text/xml');
  return $xml->asXml();
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
  $xml = coreGameXmlRaw(0, $msg);
  
  $xml->addChild('subheading', 'Waiting for a word');
  
  $xml = addNavButtons($xml);
  
  Header('Content-type: text/xml');
  return $xml->asXml();
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
  $xml = coreGameXmlRaw(0, $msg);
  
  $xml->addChild('subheading', (7-$state).' changes remaining');
  
  $xml = guessXml(coreGameXmlRaw($state, $msg), $word, $guessed, false);
  $xml = addButton($xml, 'submit', 'green', 'Submit Guess', 'submitGuess()');
  
  $xml = addNavButtons($xml);
  
  Header('Content-type: text/xml');
  return $xml->asXml();
}

//----  REDIRECT XML -------------------------------------------------------------------------

/*
redirect

params
  
returns
  xml fto redirect
*/
function redirectXml () {
  $xml = new SimpleXMLElement('<xml></xml>');
  $content = $xml->addChild('type', 'redirect');
  $content = $xml->addChild('content');
  $content->addChild('script', '<script type="text/javascript">window.location = "./Home.html";</script>');
  
  Header('Content-type: text/xml');
  return $xml->asXml();
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
  $button = $xml->addChild('button', $letter);  
  
  $button->addAttribute('class', 'standard_input false_button '.$color);
  $button->addAttribute('name', $name);
  $button->addAttribute('value', $value);
  $button->addAttribute('onclick', $function);
  $button->addAttribute('type', $type);
  
  Header('Content-type: text/xml');
  return $xml;
}

function addNavButtons ($xml) {
  $xml = addButton($xml, 'home', 'blue', 'Return Home', 'home()');
  $xml = addButton($xml, 'queue', 'red', 'Return to Queue', 'queue()');
  
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