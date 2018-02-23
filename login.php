<?php
require('includes/core.engine.php');

if(Engine::is_loggedin()!= ""){
	Engine::redirect('home.php');
}
if(!empty($_POST['username'])){
	Engine::Login($_POST['username'], $_POST['password']);
}

?>

<!DOCTYPE html>
<head>
<meta name="description" content="Divva!, Marry avoid." />
<meta name="robots" content="index,follow,noarchive" />
<meta http-equiv="imagetoolbar" content="no" />
<meta name="author" content="Will" />
<meta name="copyright" content="Copyright © 2016-2017 Will R. All rights reserved." />
<meta http-equiv="Content-type" content="text/html;charset=UTF-8" />
<meta http-equiv="Content-Style-Type" content="text/css" />
<link rel="shortcut icon" href="img/favcon.png" type="image/x-icon">
<meta name="viewport" content="width=device-width, initial-scale=1" />
<link rel="stylesheet" href="./css/login.css" type="text/css" media="screen"  />
<title>Divva: Nudge, Marry Avoid | Meet New People!</title>
</head>


<body>

<div class="header">
	<img src="./img/logo.png" class="logo"></div></h1>
	<div class="right">
		<div class="form-h">
			<a href="index.php">Sign up</a>|
			<a href="#">Sign in</a>
		</div>
		</div>
	</div>
<div class="body-wrap">

<div class="feedw">
<div class="feed">
	<div class="reg">
	 </div>
	 	<form id="login" method="POST" action="" class="form">
			
		<a href="index.php"/><button type="button" class="register">Signup with Divva</h1></button></a><br>
		<div id="or">Or</div>
		<p class="l-title">Sign in to Divva!</p>
		<div class="login">
		<input type="text" name="username" id="username" placeholder="Username">
		<br>
		<br>
		<input type="password" name="password" id="password" placeholder="Password">
		
		<a href="#" class="forgot">Forgot password?</a>
		<br>
		<div class="remember-w">
<input id="mrs" type="radio" name="title[mr]" value="Mrs">
        <label for="mrs"><span></span>Remember me?</label>
		</div>
		
		</div>
		<button type="submit" class="loginbutton">Login</h1></button><br>
	</form>
	</div>

</div>

</div>
</div>

</body>
</html>