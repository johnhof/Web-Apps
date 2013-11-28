
function home () {
  leaveQueue(function (res) {
    window.location = "./Home.html";
  })
}

function resign (callback) {  
  postResign(function (res) {
    window.location = "./Home.html";
  })
}

function setWord (callback) {  
  postSetWord($('#textField').val(), function(){})
}

function submitGuess (callback) {  
  postLetter($('#textField').val(), function(){})
}