<?php
/*
inQueue

params
  email : ''
  
returns
  bool of whether or not the user is marked as in the queue
*/
function inQueue ($email) {
  $result = query('SELECT * FROM Queue WHERE email="'.$email.'"');
  return $result ? true : false;
}

/*
deQueue

params
  email : ''
  
returns
  removes a player from the queue
*/
function deQueue ($email) {
  if (!inQueue($email)) return 'failure';
  $result = query('DELETE FROM Queue WHERE email="'.$email.'"');
  return 'success';
}

/*
enQueue

params
  email : ''
  
returns
  adds a player to the queue
*/
function enQueue ($email) {
  if (inQueue($email)) return 'failure';
  $result = query('INSERT INTO Queue VALUES("'.$email.'")');
  return 'success';
}

function pop() {
}

?>