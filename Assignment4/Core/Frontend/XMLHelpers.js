
/*
applyXml

params
  xml     : <#Document>

function
  generically applies xml
*/
function applyXml(xml) {
  $('.heading').append('Hang Man');
  $('.content').empty();
  
  var $xml       = $(xml)
    , type       = $xml.find('type').text()
    , subHeader  = $xml.find('subheading')
    , image      = $xml.find('img')    
    , mainButton = $xml.find('button').first()
  
  // subheading
  applyElement(subHeader, 'h3', 'subheading');
  $('#subheading').addClass('subheading');
  
  // body image
  applyElement(image, 'img', 'main_image');
  $('#main_image').addClass('main_image');
  $('#main_image').attr('src', image.attr('src'));
  
  // guesses box
  applyElement(image, 'img', 'main_image');
  $('#main_image').attr('class', image.attr('class'));
  $('#main_image').attr('src', image.attr('src'));

  // main form
  applyButton(mainButton);
}

/*
applyButton

params
  button : <element>

function
  generically applies button xml
*/
function applyButton (button) {
  var buttonId = button.attr('name')
  applyElement(button, 'div', buttonId);
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
    console.log(element)
    console.log(element.attributes)
    var attr = element.attributes || ''
      , html = '<' + type + ' ' + attr + ' id="' + id +'">' + element.text() + '</' + type + '>';
    $('#content').append(html);
  }
}
