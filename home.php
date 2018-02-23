<?php
require('includes/core.engine.php');
if(Engine::is_loggedin()!= ""){
  $stmt = DB::get()->prepare("SELECT * FROM `users` WHERE id = :id");
  $userid = $_SESSION['user_session'];
  $stmt->bindParam(":id", $userid);
  $stmt->execute();
  $row = $stmt->fetch(PDO::FETCH_ASSOC);
  $date = $row['dob'];



}else{
  Engine::redirect("login.php");
}
if(!empty($_FILES['picture'])){
  $dir = "uploads/";
  $tar_file = $dir . basename($_FILES["picture"]["name"]);
  $uploadGood = 1;
  $imageFileType = pathinfo($tar_file, PATHINFO_EXTENSION);

  $check = getimagesize($_FILES["picture"]["tmp_name"]);

  if($check != false){
    $uploadGood = 1;

  }else{
    $uploadGood = 0;

  }

  if(file_exists($tar_file)){
    echo "<div class='alert-divva'>Photo already exists</div>";
  }
  if($_FILES["picture"]["size"] > 200000){
    echo "<div class='alert-divva'>Photo was too large</div>";
  }
  if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif"){
    echo "<div class='alert-divva'>Image type not supported</div>";
  }
  if($uploadGood == 0){
    echo "<div class='alert-divva'>Photo was not uploaded succesfully</div>";
  }else{
    if (move_uploaded_file($_FILES["picture"]["tmp_name"], $tar_file)){
      Engine::PostImage($tar_file);
    }else{
      echo "<div class='alert-divva'>Photo was not uploaded succesfully</div>";
    }
  }
}

if(!empty($_POST['setdp'])){
  Engine::SetDP($_POST['dpvalue'], $row['id']);
}

if(!empty($_GET['usersearch'])){
  Engine::SearchUser($_GET['usersearch']);
}
if(!empty($_POST['statusquo'])){
  if(Engine::PostStatus($_POST['statusquo'], $row['id']) == true){
    echo "Status Posted";
  }else{
    echo "Status Failed To Post";
  }
}


//Get Profile Pictures
$userid = $_SESSION['user_session'];
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
   <link rel="stylesheet" href="./css/homepage.css" type="text/css" media="screen"  />
   <link rel="stylesheet" href="./css/media.css" type="text/css" media="screen"  />

   <title>Divva: <?php echo $row['username'];?> Home</title>
</head>
<body>
      <div class="navigation">
       <h1 class="logo">Divva</h1></div>
      <div class="right">
      <a href="#">Home</a> &nbsp;
      <a href="feed.php">feed</a> &nbsp;
      <a href="settings.php">account</a> &nbsp;
      <a href="members.php">members</a> &nbsp;
      <a href="logout.php">logout</a> &nbsp;
      </div>
      </div>

      <div class="content">
      <div class="body-content">
         <img src="<?php echo Engine::GetProfilePicture($userid);?>" width="215px" height="215px" class="profilei">
         <div class="upwrap"><form enctype="multipart/form-data" action="home.php" method="POST"><input name="picture" type="file" class="uploadfile" onchange="javascript:this.form.submit();" value="Change"></form></div>
         <div class="second-content">
            <h1 class="information">@<?php echo $row['username'];?></h1>
            <h1 class="tagline"><?php if($row['rank'] >= 9) {echo "Site Administrator";} else {$row['small_bio'];}?></h1>
            <h1 class="moreinfo"><?php echo Engine::GetAge($date); ?> · <?php echo $row['gender'];?> · <?php echo $row['relationship_status'];?> · <?php echo $row['location'];?></h1>
			<div class="statuswrap"><div class="status-update"><div class="username-wrap"><h1 id="statust">Update status..</h1><form action="home.php" method="POST"><input type="text" name="statusquo" onchange="javascript:this.form.submit();" id="username" placeholder="Whats on your mind?"></form></div></div></div>
    	    </div>			
            <div class="upload">
            <?php Engine::GetPicture($row['id']); ?>
            <br>
            <div class="social-links">Social</div>
            <div class="social"><a href="<?php echo $row['Facebook'];?>">Facebook</a><br><a href="<?php echo $row['Snapchat'];?>"/>Snapchat</a><br><a href="<?php echo $row['Snapchat'];?>"/>Twitter</a></div>
            <div class="referer"><p>Search User</p><br><form action="home.php" method="GET"/><input type="text" name="usersearch" class="ref" onchange="javascript:this.form.submit();"></form></div>
            </div>
         </div>
      <div class="second-wrapper">
         <div class="feed-wrap">
            <div class="feed">
               <h1 class="feedinfo">Latest Nudges, Marries and Avoids</h1>
            </div>
             <div class="details">
                <div class="d-content">
                <?php Engine::GetNudgeCount($row['id']);?>
                <hr></hr>
                <?php Engine::GetLatestNudge($row['id']);?>

                    </div>
                    </div>
                 </div>
               </div>
             </div>
         </div>
      </div>
   </div>
   </div>
</body>
</html>