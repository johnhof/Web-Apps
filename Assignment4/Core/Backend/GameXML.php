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
  
  if ($state != -1) {
    $image = $xml->addChild('img', '');
    $image->addAttribute('src', './Core/Images/'.$state.'_wrong.png');
  }
  
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
  $word = str_split($word);
    
  for ($i = 0; $i < sizeof($word); $i++) {
    
    // mark if this letter has been guesed
    $found = (strpos($guessed, $letter) !== false);
    
    // found letters display the same for maker and guesser
    if($found) {      
      $letterBox = $xml->addChild('letter', $letter);  
      $letterBox->addAttribute('class', 'letter guessed');
    }
    // let maker see non-guessed letters
    else if ($maker) {
      $letterBox = $xml->addChild('letter', $letter);  
      $letterBox->addAttribute('class', 'letter not_guessed');      
    }
    // if its not found and a guesser, return an empty cell
    else {
      $letterBox = $xml->addChild('letter', ' ');
      $letterBox->addAttribute('class', 'letter');      
    }
  }  
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
function makerGenWordXml ($state, $msg){
  $xml = coreGameXmlRaw($state, $msg);
  $xml->addChild('subheading', 'Select a word');
  
  $xml = addButton($xml, 'submit', 'green', 'Submit Word', 'setWord()');
  $xml = addTextField($xml, 'textField', 'Select a word');
  
  $xml = addNavButtons($xml);
  
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
  
  $xml->addChild('guessed', $guessed);
  
  $xml->addChild('subheading', $state.' incorrect guesses');
  
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
function guesserGenWordXml ($state, $msg){
  $xml = coreGameXmlRaw($state, $msg);
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
  $xml = guessXml(coreGameXmlRaw($state, $msg), $word, $guessed, false);
  $xml->addChild('subheading', $state.' incorrect guesses');
  
  $xml->addChild('guessed', $guessed);
  
  $xml = addButton($xml, 'submit', 'green', 'Submit Letter', 'submitGuess()');
  $xml = addTextField($xml, 'textField', 'Enter a Letter');
  
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
  $xml = addButton($xml, 'home', 'grey', 'Return Home', 'home()');
  $xml = addButton($xml, 'resign', 'red', 'Resign', 'resign()');
  
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
  else {
    $message = $xml->addChild('message', $msg);
    
    $message->addChild('wins', getGamesWon());
    $message->addChild('played', getGamesPlayed());
  }
}

?>