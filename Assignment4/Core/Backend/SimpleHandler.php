<?php
session_start(); 

include_once './Helpers.php';
include_once './Core.php';
include_once './QueueStateUtils.php';

$req       = getValue('post', 'request');
$forgot    = getValue('post', 'forgot');
$creating  = getValue('post', 'create_attempt');
$loggingIn = getValue('post', 'login_attempt');
$email     = getValue('post', 'email');
$password  = getValue('post', 'password');
$name      = getValue('post', 'name');

$userEmail = getValue('session', 'email');
$status_in = getValue('session', 'logged_In');


//sanitize inputs
$email = str_replace('"', '', $email);
$email = str_replace("'", '', $email);

//sanitize inputs
$password = str_replace('"', '', $password);
$password = str_replace("'", '', $password);

//sanitize inputs
$name = str_replace('"', '', $name);
$name = str_replace("'", '', $name);

//remote the player from the queue in case back was hit
deQueue($userEmail);

// determine the requested page
if ($req){

  // if logged in
  if($status_in) {

    // go to the home page
    if ($req === 'homePage') {
      echo homePage();
    }

    // go to the game page
    else if ($req === 'gamePage') {
      echo gamePage();
    }

    else if ($req === 'destroy') {
      session_destroy();
    }

    //all other pages redirect home
    else {
      echo redirectJson('./Home.html', '');
    }
  }

  // if not logged in
  else {

    // go to the login page
    if ($req === 'loginPage') {
      echo loginPage();
    }

    // go to the forgot password page
    else if ($req === 'forgotPage') {
      echo forgotPage();
    }

    // go to the create user page
    else if ($req === 'createPage') {
      echo createPage();
    }
    // if neither, deny access
    else echo accessDeniedJson();
  }
}

// forgot password
else if ($forgot && $email) {
  echo forgotReq($email);
}

// attempting to create a user
else if ($creating && $email && $name && $password) {
  echo createReq($email, $name, $password);
}

//attempting to log in
else if ($loggingIn && $email && $password) {
  echo loginReq($email, $password);
}

//could not determine
else {
  echo '<script type="text/javascript">window.location = "./../../Login.html"</script>';
}

?>