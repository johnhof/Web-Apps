
//----  AJAX HELPERS -------------------------------------------------------------------------

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
      type: 'listen'
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
function postQueue () {
  $.post( "test.php", { func: "getNameAndTime" }, function( data ) {
  console.log( data.name ); // John
  console.log( data.time ); // 2pm
}, "json");
}