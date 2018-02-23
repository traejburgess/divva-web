<?php
require 'includes/config.php';

$errorMessage = "";

if(isset($_POST["username"]) && !empty($_POST["username"]))
{
  $username = $_POST["username"];
  $password = $_POST["password"];

  Loginuser($username, $password);
}
function Loginuser($username, $password)
{
  $password = md5($password);
  $sql = "SELECT id, username, password FROM users WHERE username = :username";
  $stmt = DB::get()->prepare($sql);
  $stmt->bindValue(':username', $username);

  $stmt->execute();

  $user = $stmt->fetch(PDO::FETCH_ASSOC);

  if($user == false){
    die("Incorrect username / password combination!");
  }
}





$sth = DB::get()->prepare("SELECT * FROM site_settings");
$sth->execute();
while($result = $sth->fetch(PDO::FETCH_ASSOC)){
?>
<html>
<head>
<title><?php echo $result['title'];?></title>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link href="assets/index.css" rel="stylesheet">
<script>
  $('.message a').click(function(){
   $('form').animate({height: "toggle", opacity: "toggle"}, "slow");
});
</script>
</head>
  <div class="recent-divva">
  
  </div>
<div class="login-page">
  <div class="form" action="registeri.php">
    <form class="login-form">
    <center><img src="assets/logo.png"></img></center>
    <hr></hr>
      <input type="text" placeholder="username"/>
      <input type="password" placeholder="password"/>
      <input type="password" placeholder="confirm password"/>
      <input type="email" placeholder="email"/>
    <hr></hr>
      <input type="submit" value="Register" id="submit">
    </form>
  </div>
</div>
</html>
<?php }?>