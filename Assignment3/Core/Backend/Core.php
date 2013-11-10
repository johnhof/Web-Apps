<?php

include_once './Helpers.php';
include_once './JsonResource.php';



function homePage () {
  return homeJson(array(), '');
}

function createReq ($email, $name, $password) {
  echo '<div align="center">Submitting Credentials...</div></br>';
  $result = query('select * from Users where email="'.$email.'"');

  // user exists
  if ($result) {
    echo '<div align="center">...Failure.</div></br>';
    echo '<script type="text/javascript">alert("Email already in use")</script>'; 
    redirect('Login.html'); 
    exit;   
  }

  $result = query('insert into  Users (email, name, password) values ("'.$email.'", "'.$name.'", "'.$password.'")');

  echo '<div align="center">...Success.</div></br>';
  $_SESSION['logged_In']=true;
  redirect('Home.html');
}

function loginReq ($email, $password) {
  echo '<div align="center">Submitting Credentials...</div></br>';
  $result = query('select * from Users where email="'.$email.'" and password="'.$password.'"');

  if(!$result) {
    echo '<div align="center">...Failure.</div></br>';
    echo '<script type="text/javascript">alert("Login failed")</script>'; 
    redirect('Login.html');
    exit;
  }

  echo '<div align="center">...Success.</div></br>';
  $_SESSION['logged_In']=true;
  redirect('Home.html');
}

function loginPage () {
  //if logged in, redirect
  return loginJson(array(), '');
}

function createPage () {
  //if logged in, redirect
  return createJson(array(), '');
}

function forgotPage () {
  //if logged in, redirect
  return forgotJson(array(), '');
}

function gamePage () {
  return gameJson(array(), '');
}

?>