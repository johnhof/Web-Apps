<?php

function homeJson ($array, $msg) {
  $array['heading'] = '<h1>Welcome</h1><br>';
  $array['content'] = '<h3>Select an Action</h3><br>'
                      .'<a href="./Game.html" class="false_button">New Game</a>'
                      .'<a href="./Logout.html"class="false_button">Logout</a>';
  $array['message'] = $msg;
  return  $json = json_encode($array);
}

function createJson ($array, $msg) {
  $array['heading'] = '<h1>Create Account</h1><br>';
  $array['content'] = '<h3>Enter Information</h3></br>'
                      .'<form method="POST" action="./Core/Backend/SimpleHandler.php">'
                        .'Name: <input type="text" name="name"/><br/>'
                        .'Email: <input type="text" name="email"/><br/>'
                        .'Password: <input type="password" name="password"/><br/>'
                        .'<input type="hidden" name="create_attempt" value="true"/><br/>'
                        .'<input type="submit" value="Create" class="false_button"/>'
                      .'</form>';
  $array['message'] = $msg;
  return  $json = json_encode($array);  
}

function forgotJson ($array, $msg) {
  $array['heading'] = ' <h1>Recover Password</h1><br>';
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
  $array['heading'] = '<h1>User Login</h1><br>';
  $array['content'] = '<h3>Enter Credentials</h3></br>'
                      .'<form method="POST" action="./Core/Backend/SimpleHandler.php">'
                        .'Email: <input type="text" name="email"/><br/>'
                        .'Password: <input type="password" name="password"/><br/>'
                        .'<input type="hidden" name="login_attempt" value="true"/><br/>'
                        .'<a href="./Forgot.html" class="subtext">forgot password?</a></br>'
                       .' <input type="submit" value="Login" class="false_button"/>'
                      .'</form>'
                      .'<a href="./Create.html" class="false_button">Create New User</a>';
  $array['message'] = $msg;
  return  $json = json_encode($array);  
}

function gameJson ($array, $msg) {
  $array['heading'] = '<h1>Lingo!</h1><br>';
  $array['content'] = '<input type="button" id="new" name="new" class="false_button" value="Try a new word" onclick="newGame()"/></br>'
                      .'<div id="guess_box" class="guess_box">'
                        .'Enter Guess: '
                        .'<input type="text" id="guess" name="guess"/>'
                        .'<input type="button" id="submit_guess" name="submit_guess" value="Submit" class="false_button" onclick="submitGuess()"/>'
                      .'</div></br>'
                      .'<table id="game_table" class="game_table">'
                        .'<tr id="r0">'
                          .'<td class="game_cell" id="c00"></td>'
                          .'<td class="game_cell" id="c01"></td>'
                          .'<td class="game_cell" id="c02"></td>'
                          .'<td class="game_cell" id="c03"></td>'
                          .'<td class="game_cell" id="c04"></td>'
                        .'</tr>'
                        .'<tr id="r1">'
                          .'<td class="game_cell" id="c10"></td>'
                          .'<td class="game_cell" id="c11"></td>'
                          .'<td class="game_cell" id="c12"></td>'
                          .'<td class="game_cell" id="c13"></td>'
                          .'<td class="game_cell" id="c14"></td>'
                        .'</tr>'
                        .'<tr id="r2">'
                          .'<td class="game_cell" id="c20"></td>'
                          .'<td class="game_cell" id="c21"></td>'
                          .'<td class="game_cell" id="c22"></td>'
                          .'<td class="game_cell" id="c23"></td>'
                          .'<td class="game_cell" id="c24"></td>'
                        .'</tr>'
                        .'<tr id="r3">'
                          .'<td class="game_cell" id="c30"></td>'
                          .'<td class="game_cell" id="c31"></td>'
                          .'<td class="game_cell" id="c32"></td>'
                          .'<td class="game_cell" id="c33"></td>'
                          .'<td class="game_cell" id="c34"></td>'
                        .'</tr>'
                        .'<tr id="r4">'
                          .'<td class="game_cell" id="c40"></td>'
                          .'<td class="game_cell" id="c41"></td>'
                          .'<td class="game_cell" id="c42"></td>'
                          .'<td class="game_cell" id="c43"></td>'
                          .'<td class="game_cell" id="c44"></td>'
                        .'</tr>'
                      .'</table>';
  $array['message'] = $msg;
  return  $json = json_encode($array);  
}

function accessDeniedJson () {
  $array['heading'] = '<h1>401</h1><br>';
  $array['content'] = '<a href="./Login.html">Login to continue</a>';
  $array['message'] = '401: Access denied';
  return  $json = json_encode($array);  
}

function notFoundJson() {
  $array['heading'] = '<h1>404</h1><br>';
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