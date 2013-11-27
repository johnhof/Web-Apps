
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
    , message     = $xml.find('script').text()
    
  console.log(xml)
  
  //only load if something has changed
  if(gameState == state && userState == type) return;
  
  gameState = state;
  userState = type;
  
    
  //set html
  $('.message').empty();
  $('.content').empty();    
    
  $('#message').append(script);
  $('#message').append(message);
  
  // subheading
  applyElement(subheading, 'h2', 'subheading');
  $('#subheading').addClass('subheading');
  
  // body image
  applyElement(image, 'img', 'main_image');
  $('#main_image').addClass('main_image');
  
  if(userState == 'in_game') {
    $('#main_image').addClass('hangman');
  }
  
  $('#main_image').attr('src', image.attr('src'));
  
  // guesses box
  applyElement(image, 'img', 'main_image');
  $('#main_image').attr('class', image.attr('class'));
  $('#main_image').attr('src', image.attr('src'));
  
  // add form
  $('#content').append('<div class="main_form"></div>')
  
  // add buttons
  applyButtonTo($xml.find('button[name="submit"]'), '.main_form');    
  applyButtonTo($xml.find('button[name="home"]'), '.main_form');    
  applyButtonTo($xml.find('button[name="queue"]'), '.main_form');    
  
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
  applyElement(button, 'div', buttonId, selector);
  $('#' + buttonId).attr('class', button.attr('class'));
  $('#' + buttonId).attr('name', button.attr('name'));
  $('#' + buttonId).attr('onclick', button.attr('onclick'));
  $('#' + buttonId).append(button.attr('value'));
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
