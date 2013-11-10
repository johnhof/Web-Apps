<!DOCTYPE html>
<html>

<link rel="stylesheet" type="text/css" href="rsc.css" media="screen" />

<title>Create Account</title>
<body>


  <div class="main">

    <div class="heading">
      <h1>Create Account</h1><br>
    </div>
    
    <div class="content">
      
      <h3>Enter Information</h3></br>
      
      <!-- CREATE -->
      <form method="POST" action="./Home.php">
        Name: <input type="text" name="username"/><br/>
        Email: <input type="text" name="email"/><br/>
        Password: <input type="password" name="password"/><br/>
        <input type="hidden" name="create_attempt"/><br/>
        <input type="submit" value="Create" class="false_button"/>
      </form>
    
      </br>
    </div>

  </div>
