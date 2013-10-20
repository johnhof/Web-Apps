<?php
function getHtml($request){
	if($request == 'login'){
		return'<title>Login</title>
		<div style="width:500px; margin:auto;">
			<h1>Enter email and password to log in</h1>
			<form name="login" style="text-align:center; width:500px; margin:auto;">
				<div style="height:30px;width:400px;margin:auto;">
					<h3 style="float:left; margin:0px;width:150px;">Email: </h3>
					<input type="text" name="email"></input>
				</div>
				<div style="height:30px;width:400px;margin:auto;">
					<h3 style="float:left; margin:0px;width:150px">Password:</h3>
					<input type="text" name="pwd">
				</div>
				<div style="height:50px;width:300px;margin:auto;">
					<input type="submit" name="submit"></input>
				</div>
			</form>
				
			<form name="forgot_password" style="width:400px;margin:auto;">
				<div style="height:25px;width:300px;margin:auto;">
				<input type="submit" name="forgot_password" value="Email password to:" style="float:left;">
					<input type="text" name="email"></input>
				</div>
				</input type="submit" name="forgot_password" value="Email password">
			</form>
		</div>';
	}

	if($request == 'home'){
		return '<title>Login</title>
		<div style="width:300px; margin:auto;"><br>
			<h1>Choose an Action</h1>
			<form><h3>
				<div style="width:200px; margin:auto;">
					<input type="radio" name="option" value="create"> Create a Schedule<br>
					<input type="radio" name="option" value="finalize"> Finalize a Schedule<br>
					<input type="radio" name="option" value="logout" checked> Logout<br>
				</div></h3>
				<input type="submit" value="Submit" style="margin-top:25px;;width:100px; margin-left:75;">
			<form>
		</div>';
	}
}
?>