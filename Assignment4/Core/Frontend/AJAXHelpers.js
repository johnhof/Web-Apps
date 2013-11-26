
/*
listen

params
  resCallback     : function (result)

function
 executes resCallback if a value is returned
 executes timeoutcallback if the request times out
*/
function listen (resCallback) {
  setTimeout(function () {
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
          listen(resCallback);
      }
    }
    
    $.ajax(req);
  }, listenerWait);
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
  var form = { 
    request: "deQueue" 
  }
  post = $.post("./Core/Backend/GameHandler.php", form, callback, "text");
}