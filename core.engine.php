<?php
require('config.php');

Class Engine{
	public static $GoodUser = false;
	public static $GoodEmail = false;

	public function Register($username, $password, $email, $dob){
		$Clear = false;
		$stmt = DB::get()->prepare("INSERT INTO `users` (username, password, email, dob, recover_pin, account_created, registered_ip, last_ip) VALUES (:username, :password, :email, :dob, :recover_pin, :account_created, :registered_ip, :last_ip)");
		if(Engine::CheckUser($username) == true){
			if(Engine::CheckEmail($email) == true){
				$startpin = rand();
				$stmt->bindParam(":username", $username);
				$hash = sha1($password);
				$stmt->bindParam(":password", $hash);
				$stmt->bindParam(":email", $email);
				$stmt->bindParam(":dob", $dob);
				$stmt->bindParam(":recover_pin", $startpin);
				$date = date("Y/m/d");
				$stmt->bindParam(":account_created", $date);
				if(!empty($_SERVER['HTTP_CLIENT_IP'])){
					$regip = $_SERVER['HTTP_CLIENT_IP'];
				}elseif(!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){
					$regip = $_SERVER['HTTP_X_FORWARDED_FOR'];
				}else{
					$regip = $_SERVER['REMOTE_ADDR'];
				}
				$stmt->bindParam(":registered_ip", $regip);
				$stmt->bindParam(":last_ip", $regip);


				//Execution
				$stmt->execute();
				header("location: home.php");
			}else{
				return false;
			}

		}else{
			return false;
		}
	}
	public function login($username, $password){
		$stmt = DB::get()->prepare("SELECT * FROM users WHERE username = :username LIMIT 1");
		$stmt->bindParam(":username", $username);
		$stmt->execute();
		$row = $stmt->fetch(PDO::FETCH_ASSOC);

		if($stmt->rowCount() > 0){
			$postedpass = sha1($password);
			if($postedpass == $row['password']){
				$_SESSION['user_session'] = $row['id'];
				header("location: home.php");
				return true;
			}else{
				echo "HI";
				return false;
			}
		}
	}
	public function CheckUser($username){
		$stmt = DB::get()->prepare("SELECT * FROM `users` WHERE username = :username");
		$stmt->bindParam(":username", $username);
		$stmt->execute();

		if($stmt->rowCount() > 0){
			return false;

		}else{
			$GoodUser = true;
			return true;
		}
	}
	public function CheckEmail($email){
		$stmt = DB::get()->prepare("SELECT * FROM `users` WHERE email = :email");
		$stmt->bindParam(":email", $email);
		$stmt->execute();

		if($stmt->rowCount() > 0){
			return false;
		}else{
			$GoodEmail = true;
			return true;
		}
	}

	public function is_loggedin(){
		if(isset($_SESSION['user_session'])){
			return true;
		}
	}
	public function redirect($url){
		header("Location: $url");
	}
	public function logout(){
		session_destroy();
		unset($_SESSION['user_session']);
		header("location: index.php");
		return true;
	}
	public function GetAge($date){
		$stmt = DB::get()->prepare("SELECT * FROM `users` WHERE id = :id AND dob = :dob");
		$userid = $_SESSION['user_session'];
		$stmt->bindParam(":id", $userid);
		$stmt->bindParam(":dob", $date);
		$stmt->execute();

		$date = new DateTime($date);
		$today = new DateTime('today');
		$age = $date->diff($today)->y;
		return $age;
	}
	public function PostImage($file){
		$stmt = DB::get()->prepare("INSERT INTO `uploads` (user_id, image_tag) VALUES (:user_id, :image_tag)");
		$userid = $_SESSION['user_session'];
		$stmt->bindParam(":user_id", $userid);
		$stmt->bindParam(":image_tag", $file);
		if($stmt->execute()){
			echo "<div class='alert-divva'>Photo was uploaded succesfully</div>";
		}else{
			echo "<div class='alert-divva'>Photo was not uploaded succesfully</div>";
		}
	}
	public function GetProfilePicture($id){
		$stmt = DB::get()->prepare("SELECT * FROM users WHERE id = :id");
		$stmt->bindParam(":id", $id);
		if($stmt->execute()){
			$row = $stmt->fetch(PDO::FETCH_ASSOC);
			$pictu = $row['profile_pic'];
			if($pictu != ""){
				echo $pictu;
			}else{
				"img/black.jpg";
			}
			
		}else{
			"img/black.jpg";
		}
	}

	public function GetPicture($userid){
		$stmt = DB::get()->prepare("SELECT * FROM uploads WHERE user_id = :user_id LIMIT 4");
		$stmt->bindParam(":user_id", $userid);
		$stmt->execute();
		$rows = $stmt->fetchAll();

		foreach ($rows as $row){
			echo "<div class='tooltip'><a href='http://localhost/" .$row['image_tag']. "'><img src='" .$row['image_tag']. "' width='50px' height='50px' class='uploaded'><span class='tooltiptext'><form action='home.php' method='POST'><input type='submit' class='button5' value='Set DP' name='setdp'/><input type='hidden' name='dpvalue' value='" .$row['image_tag']. "'/></form></span></div></a> ";
		}
	}
	public function GetLatestNudge($userid){
		$stmt = DB::get()->prepare("SELECT * FROM interactions WHERE second_userid = :id LIMIT 10");
		$stmt->bindParam(":id", $userid);
		$stmt->execute();
		$rows = $stmt->fetchAll();

		foreach($rows as $row){
			$second_userid = $row['first_userid'];
			$stmt = DB::get()->prepare("SELECT * FROM users WHERE id = :id");
			$stmt->bindParam(":id", $userid);
			$stmt->execute();
			$userone = $stmt->fetch();
			//Between Users
			$stmt = DB::get()->prepare("SELECT * FROM users WHERE id = :id");
			$stmt->bindParam(":id", $second_userid);
			$stmt->execute();
			$second_user = $stmt->fetch();

			echo "<center><a href='localhost/home.php'><i>".$second_user['username']."</i></a> ".$row['type']." <a href='localhost/home.php'><i>".$userone['username']."</i></a></center><hr></hr>";
		}
	}
	public function SetDP($file, $user_id){
		$stmt = DB::get()->prepare("UPDATE users SET profile_pic = :profile_tag WHERE id = :id");
		$stmt->bindParam(":profile_tag", $file);
		$stmt->bindParam(":id", $user_id);
		if($stmt->execute()){
			return true;
		}else{
			return false;
		}
	}
	public function GetNudgeCount($userid){
		$stmt = DB::get()->prepare("SELECT * FROM users WHERE id = :id");
		$stmt->bindParam(":id", $userid);
		if($stmt->execute()){
			$user = $stmt->fetch();
			echo "<center><i>Nudges ".$user['nudges']."</i> | <i>Marries ".$user['marry']."</i> | <i>Avoids ".$user['avoids']."</i></center>";
		}else{
			echo "<center><i>Nudges..</i> | <i>Marries...</i> | <i>Avoids...</i></center>";
		}
	}
	public function PostStatus($Message, $UserId){
		$stmt = DB::get()->prepare("INSERT INTO `profile_posts` (message, userid) VALUES (:message, :userid)");
		$Message = htmlspecialchars($Message);
		$stmt->bindParam(":message", $Message);
		$stmt->bindParam(":userid", $UserId);
		if($stmt->execute()){
			return true;
		}else{
			return false;
		}

	}

	public function SearchUser($username){
		$username = htmlspecialchars($username);
		$stmt = DB::get()->prepare("SELECT * FROM users WHERE username = :user");
		$stmt->bindParam(":user", $username);
		if ($stmt->execute()){
			if($stmt->rowCount() > 0){
				$row = $stmt->fetch(PDO::FETCH_ASSOC);
				//Echo out designed search

			}else{
				echo "User not found";
			}
		}else{
			echo "User not found";

		}
	}


}
	


?>