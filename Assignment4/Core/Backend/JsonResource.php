<?php

function homeJson ($array, $msg) {
  $array['heading'] = '<h1>Welcome</h1>';
  $array['content'] = '<h3>Select an Action</h3><br>'
                      .'<a href="./Game.html" class="false_button">New Game</a>'
                      .'<a href="./Logout.html"class="false_button">Logout</a>';
  $array['message'] = $msg;
  return  $json = json_encode($array);
}

function createJson ($array, $msg) {
  $array['heading'] = '<h1>Create Account</h1>';
  $array['content'] = '<h3>Enter Information</h3></br>'
                      .'<form method="POST" action="./Core/Backend/SimpleHandler.php">'
                        .'<div class ="create_fields">'
                          .'Name: <input type="text" name="name"/><br/>'
                          .'Email: <input type="text" name="email"/><br/>'
                          .'Password: <input type="password" name="password"/><br/>'
                        .'</div>'
                        .'<input type="hidden" name="create_attempt" value="true"/><br/>'
                        .'<input type="submit" value="Create" class="false_button"/>'
                      .'</form>';
  $array['message'] = $msg;
  return  $json = json_encode($array);  
}

function forgotJson ($array, $msg) {
  $array['heading'] = ' <h1>Recover Password</h1>';
  $array['content'] = '<h3>Submit account email</h3></br>'
                      .'<form method="POST" action="./Core/Backend/SimpleHandler.php">'
                        .'Email: <input type="text" name="email"/><br/>'
                        .'<input type="hidden" name="forgot" value="true"/><br/>'
                        .'<input type="submit" value="Send Reminder" class="false_button"/>'
                      .'</form>';
  $array['message'] = $msg;
  return  $json = json_encode($array);  
}

function loginJson ($array, $msg) {
  $array['heading'] = '<h1>User Login</h1>';
  $array['content'] = '<h3>Enter Credentials</h3></br>'
                      .'<form method="POST" class="main_form" action="./Core/Backend/SimpleHandler.php">'
                        .'<input class="standard_input text_field" type="text" name="email" value="email"/><br/>'
                        .'<input class="standard_input text_field" type="password" name="password" value="password"/><br/>'
                        .'<input type="hidden" name="login_attempt" value="true"/>'
                        .'<input type="submit" value="Login" class="false_button standard_input"/>'
                        .'<a href="./Forgot.html" class="false_button standard_input">forgot password?</a>'
                        .'<a href="./Create.html" class="false_button standard_input">Create New User</a>'
                      .'</form>';
  $array['message'] = $msg;
  return  $json = json_encode($array);  
}

function gameJson ($array, $msg) {
  $array['heading'] = '<h1>Hangman!</h1>';
  $array['content'] = '<input type="button" id="new" name="new" class="false_button" value="Try a new word" onclick="newGame()"/></br>'
                      .'<div id="guess_box" class="guess_box">'
                        .'Enter Guess: '
                        .'<input type="text" id="guess" name="guess"/>'
                        .'<input type="button" id="submit_guess" name="submit_guess" value="Submit" class="false_button_inactive" onclick="submitGuess()"/>'
                      .'</div></br>'
                      .'<a href="./Home.html" id="quit_button" class="false_button">Quit</a>';
  $array['message'] = $msg;
  return  $json = json_encode($array);  
}

function queueJson ($array, $msg) {
  $array['heading'] = '<h1>Hangman!</h1>';
  $array['content'] = '<input type="button" id="new" name="new" class="false_button" value="Try a new word" onclick="newGame()"/></br>'
                      .'<a href="./Home.html" id="quit_button" class="false_button">Quit</a>';
  $array['message'] = $msg;
  return  $json = json_encode($array);  
}

function accessDeniedJson () {
  $array['heading'] = '<h1>401</h1>';
  $array['content'] = '<a href="./Login.html">Login to continue</a>';
  $array['message'] = '401: Access denied';
  return  $json = json_encode($array);  
}

function notFoundJson() {
  $array['heading'] = '<h1>404</h1>';
  $array['content'] = '<a href="./Login.html">Return home?</a>';
  $array['message'] = '404: Page not found';
  return  $json = json_encode($array);  
}

function redirectJson ($url, $msg) {
  $array['redirect'] = $url;
  $array['message']  = $msg;
  return  $json = json_encode($array);  
}

function customJson ($heading, $content, $message) {
  $array['heading'] = $heading;
  $array['content'] = $content;
  $array['message'] = $message;
  return  $json = json_encode($array);  

}
?>