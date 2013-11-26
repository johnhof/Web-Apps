
/*
listen

params
  resCallback     : function (result)

function
 executes resCallback if a value is returned
 executes timeoutcallback if the request times out
*/
function listen (resCallback) {
  console.log('userState: ' + userState)
  var req = {  
    type: "POST",  
    url: "./Core/Backend/GameHandler.php",  
    data: {
      request: userState
    },
    dataType: "xml",
    async: true,
    timeout: 1000,
    success: resCallback,
    error: function (jqXHR, textStatus, errorThrown)
    {
      if(textStatus === "timeout") { 
        
        console.log('Poll Timeout, executing callback');
        setTimeout(listen, 2000, resCallback);        
      } else {
        console.log('Unexpected Error:');
        console.log(errorThrown);
      }
    }
  }
  console.log('req: ' + req)
  
  $.ajax(req);
}

/*
listen

params
  resCallback     : function (result)

function
 executes resCallback if a value is returned
 executes timeoutcallback if the request times out
*/
function getStateXml (callback) {
  console.log('making request')
  
  var form = { 
    request: "user_state" 
  }
  post = $.post("./Core/Backend/GameHandler.php", form, callback, "xml");
}

/*
leaveQueue

params
  resCallback     : function (result)

function
 executes resCallback if a value is returned
 executes timeoutcallback if the request times out
*/
function leaveQueue (callback) {
  console.log('making request')
  
  var form = { 
    request: "deQueue" 
  }
  post = $.post("./Core/Backend/GameHandler.php", form, callback, "text");
}