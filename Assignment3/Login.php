<!DOCTYPE html>
<html>

<link rel="stylesheet" type="text/css" href="rsc.css" media="screen" />

<title>Login</title>
<body>


  <div class="main">

    <div class="heading">
      <h1>User Login</h1><br>
    </div>
    
    <div class="content">
      
      <h3>Enter Credentials</h3></br>
      
      <!-- LOGIN -->
      <form method="POST" action="./Home.php">
        Email: <input type="text" name="email"/><br/>
        Password: <input type="password" name="password"/><br/>
        <input type="hidden" name="login_attempt"/><br/>
        <a href="./Forgot.php" class="subtext">forgot password?</a></br>
        <input type="submit" value="Login" class="false_button"/>
      </form>
    
      </br>
    
      <!-- CREATE -->
      <a href="./Create.php" class="false_button">Create account</a>
    </div>

  </div>

</body>
</html>
