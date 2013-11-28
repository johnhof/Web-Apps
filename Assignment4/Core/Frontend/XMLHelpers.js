
/*
applyXml

params
  xml     : <#Document>

function
  generically applies xml
*/
function applyXml(xml) {
  var $xml        = $(xml)
    , type        = $xml.find('type').text()
    , state       = $xml.find('state').text()
    , subheading  = $xml.find('subheading')
    , image       = $xml.find('img')    
    , script      = $xml.find('script').text()
    , message     = $xml.find('message')
    
  console.log(xml)
  
  //only load if something has changed
  if(gameState == state && userState == type) return;
  
  gameState = state;
  userState = type;
  
    
  //set html
  $('.message').empty();
  $('.content').empty();    
    
  $('#message').append(script);
  formatScore(message);
  
  // subheading
  applyElement(subheading, 'h3', 'subheading');
  $('#subheading').addClass('subheading');
  
  // body image
  applyElement(image, 'img', 'main_image');
  $('#main_image').addClass('main_image');
  
  if(userState == 'in_game') {
    $('#main_image').addClass('hangman');
  }
  
  $('#main_image').attr('src', image.attr('src'));
  
  // main image
  applyElement(image, 'img', 'main_image');
  $('#main_image').attr('class', image.attr('class'));
  $('#main_image').attr('src', image.attr('src'));
  
  // add form
  $('#content').append('<div class="main_form"></div>')
  
  // add text field
  applyTextFieldTo($xml.find('textField'), '.main_form');    
  
  // add buttons
  applyButtonTo($xml.find('button[name="submit"]'), '.main_form');    
  applyButtonTo($xml.find('button[name="home"]'), '.main_form');    
  applyButtonTo($xml.find('button[name="resign"]'), '.main_form');    
  
  onStateXmlReturn();
}

/*
applyButton

params
  button : <element>

function
  generically applies button xml
*/
function applyButton (button) {
  if(!button.length || button.attr('hidden') == 'true') return;
  
  var buttonId = button.attr('name')
  applyElement(button, 'div', buttonId);
  $('#' + buttonId).attr('class', button.attr('class'));
  $('#' + buttonId).attr('name', button.attr('name'));
  $('#' + buttonId).attr('onclick', button.attr('onclick'));
  $('#' + buttonId).append(button.attr('value'));
}
function applyButtonTo (button, selector) {
  if(!button.length || button.attr('hidden') == 'true') return;
  
  var buttonId = button.attr('name')
  applyTo(button, 'div', buttonId, selector);
  $('#' + buttonId).attr('class', button.attr('class'));
  $('#' + buttonId).attr('name', button.attr('name'));
  $('#' + buttonId).attr('onclick', button.attr('onclick'));
  $('#' + buttonId).append(button.attr('value'));
}

function applyTextFieldTo (field, selector) {
  if(!field.length) return;
  
  var fieldId = 'textField'
  applyTo(field, 'input', fieldId, selector);
  $('#' + fieldId).attr('class', field.attr('class'));
  $('#' + fieldId).attr('type', 'text');
  $('#' + fieldId).attr('name', field.attr('name'));
  $('#' + fieldId).attr('placeholder', field.attr('placeholder'));
  $('#' + fieldId).append(field.attr('value'));
}

function formatScore (message) {
  if(!message.find('wins').length) return;
  
  var wins = message.find('wins').text();
  var played = message.find('played').text();
  
  $('#message').attr('class', 'message');
  $('#message').append('Games Played: ' + played +'</br>');
  $('#message').append('Games Won: ' + wins +'</br>');
  $('#message').append('Games Lost: ' + (played-wins) +'</br>');
}

/*
applyElement

params
  element : <element>
  type    : ''
  id      : ''

function
  generically applies element xml
*/
function applyElement (element, type, id) {
  if (element) {
    var attr = element.attributes || ''
      , html = '<' + type + ' ' + attr + ' id="' + id +'">' + element.text() + '</' + type + '>';
    $('#content').append(html);
  }
}
function applyTo (element, type, id, selector) {
  if (element) {
    var attr = element.attributes || ''
      , html = '<' + type + ' ' + attr + ' id="' + id +'">' + element.text() + '</' + type + '>';
    $(selector).append(html);
  }
}


