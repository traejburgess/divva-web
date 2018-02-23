<?php 
require('includes/core.engine.php');

if(Engine::is_loggedin()!= ""){
	Engine::redirect('home.php');
}
if(!empty($_POST['username'])){
	Engine::Register($_POST['username'], $_POST['password'], $_POST['email'], $_POST['dob']);

}

?>


<!DOCTYPE html>
<head>
<meta name="description" content="Divva!, Nudge, Marry, Avoid." />
<meta name="robots" content="index,follow,noarchive" />
<meta http-equiv="imagetoolbar" content="no" />
<meta name="author" content="Will" />
<meta name="copyright" content="Copyright © 2016-2017 Will R. All rights reserved." />
<meta http-equiv="Content-type" content="text/html;charset=UTF-8" />
<meta http-equiv="Content-Style-Type" content="text/css" />
<link rel="shortcut icon" href="img/favcon.png" type="image/x-icon">
<meta name="viewport" content="width=device-width, initial-scale=1" />
<link rel="stylesheet" href="./css/index.css" type="text/css" media="screen"  />
<link rel="stylesheet" href="./css/media.css" type="text/css" media="screen"  />
<title>Divva: Nudge, Marry Avoid | Meet New People!</title>
</head>


<body>

<div class="header">
	<img src="./img/logo.png" class="logo"></div></h1>
	<div class="right">
		<div class="form-h">
			<a href="#">Sign up</a>|
			<a href="login.php">Sign in</a>
		</div>
		</div>
	</div>
<div class="body-wrap">
	<div class="greeting">
		<h1 id="title">Meeting new people, made fun!</h1>
		<h1 id="greet">Sign up for your free account!</h1>
	</div>
<div class="feedw">
<div class="feed">
	<div class="reg">
	 </div>
	 	<form method="post" action="" class="form">
	 	<label>Username</label>
		<input type="text" name="username" id="username">
		<div id="reminder">Letters, numbers and underscores only. 30 characters max.</div>
		
		<label>Password</label>
		<input type="password" name="password" id="password">
		<br>
		<label>Email</label>
		<input type="text" name="email" id="email">
		<br>
		<div class="well"> 
		<div class="form-group">
		<label>Date of Birth</label><br>
		<input type="date" name="dob" class="form-control" id="exampleInputDOB1" placeholder="Date of Birth">
		</div>
		</div>

		<div class="well"> 
		</div>
		<br>
		<button type="submit" class="register"><b style="color:#FFF;">Register!</b></h1></button>

	</form>
	</div>
	<div id="feed2" class="feed2">
	<?php
	$stmt = DB::get()->prepare("SELECT * FROM interactions LIMIT 5");
	$stmt->execute();
	$row = $stmt->fetch(PDO::FETCH_ASSOC);
	$first_userid = $row['first_userid'];
	$second_userid = $row['second_userid'];

	if($stmt->rowCount() >= 10){
		foreach($row as $rows){
			$stmt = DB::get()->prepare("SELECT profile_pic, username FROM users WHERE id = :first_userid");
			$stmt->bindParam(":first_userid", $first_userid);
			$stmt->execute();
			$userone = $stmt->fetch();

			$stmt = DB::get()->prepare("SELECT profile_pic, username FROM users WHERE id = :second_userid");
			$stmt->bindParam(":second_userid", $second_userid);
			$stmt->execute();
			$usertwo = $stmt->fetch();

			echo "<div id='welcome_interaction'><img id='thumb' src='uploads/" . $userone['profile_pic'] . "'/> ". $userone['username'] ." ".$row['type']." ".$usertwo['username']." <img id='thumb' src='uploads/". $usertwo['profile_pic'] . "'/></div>";
		}
	}else{
		echo "<center><img src='img/noint.png'/></center><hr></hr>";
	}
	?>




	</div>
</div>

</div>
</div>

</body>
</html>