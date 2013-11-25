
/*
listen

params
  resCallback     : function (result)

function
 executes resCallback if a value is returned
 executes timeoutcallback if the request times out
*/
function listen (resCallback) {
  var req = {  
    type: "POST",  
    url: "/Core/Backend/GameHandler.php",  
    data: {
      request: state
    },
    dataType: "xml",
    async: true,
    timeout: 300000,
    success: resCallback(xml),
    error: function (jqXHR, textStatus, errorThrown)
    {
      if(textStatus === "timeout") { 
        
        console.log('Poll Timeout, executing callback');
        listen(resCallback);
        
      } else {
        console.log('Unexpected Error:');
        console.log(errorThrown);
      }
    }
  }
  console.log(req)
  
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