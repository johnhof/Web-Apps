
function requestContent (page, callback) {
  post('./Core/Backend/SimpleHandler.php', 'request='+page, callback)
}

function destroySession (callback) {
  post('./Core/Backend/SimpleHandler.php', 'request=destroy', callback)
}

function post (url, params, callback) {
  if (window.XMLHttpRequest) { 
    var httpRequest = new XMLHttpRequest();

    if (httpRequest.overrideMimeType) {
      httpRequest.overrideMimeType('text/xml');
    }
  }
  else if (window.ActiveXObject) { 
    try {
      httpRequest = new ActiveXObject("Msxml2.XMLHTTP");
    }
    catch (e) {
      try {
          httpRequest = new ActiveXObject("Microsoft.XMLHTTP");
      }
      catch (e) {}
    }
  }

  if(!httpRequest){
    alert("Could not create HTTP request");
    return false;
  }

  httpRequest.open('POST', url, true);
  httpRequest.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
  // httpRequest.setRequestHeader("Content-length", params.length);
  // httpRequest.setRequestHeader("Connection", "close");

  httpRequest.onreadystatechange = callback;

  httpRequest.send(params);

}

function formatResponse (response, callback) {
  if(!this.response) {
    if(typeof(callback) == "function")  return callback(null);
    else return null;
  }
  
  if (this.response.match(/xdebug-error xe-notice/)) {
   document.write(this.response);
 }

  var response = eval('('+this.response+')');

  if(response.redirect){
    document.write('<script type="text/javascript">window.location = "'+response.redirect+'"</script>');
  }

  document.getElementById('heading').innerHTML = response.heading;
  document.getElementById('content').innerHTML = response.content;

  if(response.message) {
    var msg = document.getElementById('message');
    msg.innerHTML = response.message;
    msg.className  = 'message';
  }

  if(callback) {
    if(typeof(callback) == "function")  return callback(null);
    else return null;
  }
}


var genericBody = '<body>  \
                <div class="main">\
                  <div class="heading" id="heading" id="heading"></div>    \
                  <div class="content" id="content">Loading...</div>\
                </div><br>\
                <div id="message"></div>\
              </body>';

