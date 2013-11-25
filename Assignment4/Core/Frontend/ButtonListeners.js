
function home () {
  leaveQueue(function (res) {
    if (/success/i.test(res)) {
      window.location = "./Home.html";
    }
    else {
      updateMessage('ERROR: could not leave queue');
    }
  })
}