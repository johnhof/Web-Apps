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