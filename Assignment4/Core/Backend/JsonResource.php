<?php

function homeJson ($array, $msg) {
  $array['heading'] = '<h1>Home</h1>';
  $array['content'] = '<h3>Select an Action</h3><br>'
                      .'<div class="main_form">'
                        .'<a href="./Game.html"   class="standard_input false_button green">Play Now</a>'
                        .'<a href="./Logout.html" class="standard_input false_button grey">Logout</a>'
                      .'</div>';
  $array['message'] = $msg;
  return  $json = json_encode($array);
}

function createJson ($array, $msg) {
  $array['heading'] = '<h1>Create Account</h1>';
  $array['content'] = '<form method="POST" class="main_form" action="./Core/Backend/SimpleHandler.php">'
                        .'<input class="standard_input text_field"type="text" name="name" placeholder="Name"/><br/>'
                        .'<input class="standard_input text_field" type="text" name="email" placeholder="Email"/><br/>'
                        .'<input class="standard_input text_field" type="password" name="password" placeholder="Password"/><br/>'
                        .'<input class="standard_input false_button green" type="submit" value="Create" class="false_button"/>'
                        .'<a     class="standard_input false_button blue" href="./Login.html">Return to Login</a>'
                        .'<input type="hidden" name="create_attempt" value="true"/><br/>'
                      .'</form>';
  $array['message'] = $msg;
  return  $json = json_encode($array);  
}

function forgotJson ($array, $msg) {
  $array['heading'] = ' <h2>Recover Password</h2>';
  $array['content'] = '<form method="POST"  class="main_form" action="./Core/Backend/SimpleHandler.php">'
                        .'<input class="standard_input text_field"         type="text" name="email" placeholder="Account Email"/><br/>'
                        .'<input class="standard_input false_button green" type="submit"            value="Send Reminder" />'
                        .'<a     class="standard_input false_button blue" href="./Login.html">Return to Login</a>'
                        .'<input type="hidden" name="forgot" value="true"/><br/>'
                      .'</form>';
  $array['message'] = $msg;
  return  $json = json_encode($array);  
}

function loginJson ($array, $msg) {
  $array['heading'] = '<h1>Hang Man!</h1>';
  $array['content'] = '<form method="POST" class="main_form" action="./Core/Backend/SimpleHandler.php">'
                          .'<input class="standard_input text_field"         type="text" name="email" placeholder="Email"/><br/>'
                          .'<input class="standard_input text_field"         type="password" name="password" placeholder="Password"/><br/>'
                          .'<input class="standard_input false_button green" type="submit" value="Login"/>'
                          .'<div   class="split left">'
                          .'<a     class="standard_input false_button grey space_right" href="./Forgot.html">Forgot Password</a>'
                          .'</div>'
                          .'<div   class="split right">'
                          .'<a     class="standard_input false_button grey space_left" href="./Create.html">Create New User</a>'
                          .'</div>'
                        .'<input type="hidden" name="login_attempt" value="true"/>'
                      .'</form>';
  $array['message'] = $msg;
  return  $json = json_encode($array);  
}

function gameJson ($array, $msg) {
  $array['heading'] = '<h1>Hangman!</h1>';
  $array['content'] = ' ';/*'<input type="button" id="new" name="new" class="false_button" value="Try a new word" onclick="newGame()"/></br>'
                      .'<div id="guess_box" class="guess_box">'
                        .'Enter Guess: '
                        .'<input type="text" id="guess" name="guess"/>'
                        .'<input type="button" id="submit_guess" name="submit_guess" value="Submit" class="false_button_inactive" onclick="submitGuess()"/>'
                      .'</div></br>'
                      .'<a href="./Home.html" id="quit_button" class="false_button">Quit</a>';*/
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