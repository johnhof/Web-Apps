<?php
session_start(); 

include_once './Helpers.php';
include_once './Core.php';

$req       = getValue('post', 'request');
$creating  = getValue('post', 'create_attempt');
$loggingIn = getValue('post', 'login_attempt');
$email     = getValue('post', 'email');
$password  = getValue('post', 'password');
$name      = getValue('post', 'name');

$status_in = getValue('session', 'logged_In');

error_log($creating);
error_log($email);
error_log($password);
error_log($name);

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

// attempting to create a user
else if ($creating && $email && $name && $password) {
  error_log('woot');
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