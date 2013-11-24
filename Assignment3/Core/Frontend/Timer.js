timer = {
  id: null,
  target: null,
  time: null,
  iterator: function(){},
  fin: function(){},
  start: function(){},
  count: function(){}
}

timer.init = function (time, iterCallback, finCallback) {
  timer.target = time;
  timer.time = time;
  timer.iterator = iterCallback;
  timer.fin = finCallback;
  if (timer.id) window.clearTimeout(timer.id);
}

timer.count = function () {
  if (timer.id) window.clearTimeout(timer.id);
  
  // if the timer ran out, call the finish callback
  if (timer.time == 0) {
    return timer.fin();
  }
  
  // otherwise, call the iterator callback, log the time, and start the timer
  timer.iterator(timer.time);
  timer.id = window.setTimeout(timer.count, 1000);
  timer.time--;
}

timer.start = function () {
  timer.iterator(timer.time);
  timer.time--;
  timer.id = window.setTimeout(timer.count, 1000);
  
}

timer.reset = function () {
  timer.time = timer.target;
  timer.count();
}

timer.stop = function () {
  timer.time = 0;
  if (timer.id) window.clearTimeout(timer.id);
}