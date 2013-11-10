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
      <form method="POST" action="./Home.do">
        Username: <input type="text" name="username"/><br/>
        Password: <input type="password" name="passwort"/><br/>
        <a href="./Forgot.do" class="subtext">forgot password?</a></br>
        <input type="submit" value="Login" class="false_button"/>
      </form>
    
      </br>
    
      <!-- CREATE -->
      <a href="./Create.do" class="false_button">Create account</a>
    </div>

  </div>

</body>
</html>
