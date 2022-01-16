<?php
include("classes/connect.php");
include("classes/login.php");
include("classes/user.php");
include("classes/post.php");
include("classes/image.php");
/*print_r($_SESSION);*///FOR DEBUGGING

$login=new Login();
$user_data=$login->check_login($_SESSION['gearshare_userid']);

//WRITING POST
if($_SERVER['REQUEST_METHOD'] == "POST")
	{
		$post = new Post();
		$id = $_SESSION['gearshare_userid'];
		$result = $post->create_post($id, $_POST,$_FILES);

		if($result=="")
		{
			header("Location:index.php");
			die;
		}else
		{
			echo "<div style='text-align:center;font-size:12px;color:white;background-color:red;'>";
			echo "<br>The following errors occured:<br>";
			echo $result;
			echo "<br></div>";
		}
	}

//READING EXISTING POSTS
	$post = new Post();
	$id = $_SESSION['gearshare_userid'];
	$posts = $post->get_posts($id);
?>


<!DOCTYPE html>
<html>
	<head>
		<title> My Profile | GearShare</title>
		<link rel="stylesheet" href="global-styles.css">
	</head>
	
	<style type="text/css">

	#profile_image{
	width:150px; 
	border:solid #ffa31a 3px;
	width:75px;
	float:left;
	margin:8px;
	border-radius:8px;
	}
	#profile_menu{
		width:100px;
		display:inline-block;
		margin:2px;
	}
	textarea{
		width:100%;
		border:none;
		font-family:Helvetica;
		font-size:14px;
		height:60px;
	}
	#post_button{
		float:right;
		background-color:#de8500;
		border:none;
		color:white;
		padding:4px;
		font-size:14px;
		border-radius:2px;
		width:50px;
	}
	#post_area{
		margin-top:20px; 
		background-color:white;
		border-radius:10px;
		padding:10px;
	}
	#post{
		padding:4px;
		font-size:13px;
		display:flex;
		margin-bottom:20px
		margin-top:20px;
	}
	a{
	text-decoration:none;
	}

	#sidebar{
		background-color:#1b1b1b;
		padding:8px;
		min-height:150px;
		border-radius:10px;
		margin-right:20px;
		color:white;
		text-align:center;
		font-size:18px;
	}
	#sidebar_entry{
	height:100px;
	margin-bottom:8px;
	
	}
	
	</style>
	
	<body>
	<?php include("header.php")?>
		
		<!--User Info-->
		<div style="width:800px;margin:auto;min-height:300px;margin-top:30px;">
			
			<!--Timeline-->
			<div style="display:flex;padding:20px; background-color:#353535;border-radius:10px;margin-top:15px;">
				<!--Sidebar-->
				<div style="flex:1;">
					<div id="sidebar" >

					<?php 
							$image_class=new Image();
							$image = "default/male.jpg";
							if($user_data['gender'] == "Female")
							{
								$image = "default/female.jpg";
							}
							if(file_exists($user_data['profile_image']))
							{
								$image = $image_class->get_thumb_profile($user_data['profile_image']);
							}
						?>

						<div id="sidebar_entry">
						<img id="profile_image" src="<?php echo $image ?>">
						<br> 
						<a href="profile.php" style="color:#ffa31a">
							<?php echo $user_data['first_name']." ".$user_data['last_name']?>
						</a>
						</div>
						
						<div id="sidebar_entry" style="padding:5px;font-size:10px;text-align:left;">
						<p id="center"><b>My Gear</b></p>
						<ul>
							<li>Camera: <?php echo $user_data['camera']?></li>
							<li>Lens: <?php echo $user_data['lens']?></li>
							<li>Category: <?php echo $user_data['category']?></li>
						</ul>
						</div>
					</div>
					

				</div>
				
				<!--Submit Post-->
				<div style="flex:2.5">
				<div style="width:100%"></div>
					<div style="border:solid thin #AAA; border-radius:10px; padding:10px;background-color:white">
						<form method="post" enctype="multipart/form-data">
							<textarea style="outline:none;"name="post" placeholder="What's on your mind?"></textarea>
							<input type="file" name="file"></input>
							<input id="post_button" type="submit" value="Post"></input>
							<br>
						</form>
					</div>
				<!--Posts-->
					<div id="post_area">
						<?php
						$DB = new Database();
						$user_class=new User();
						$image_class = new Image();
						$myuserid = $_SESSION['gearshare_userid'];
						$sql = "select * from posts where parent=0 order by id desc limit 30";//parent=0 means full post
						$posts = $DB->read($sql);
						if($posts)
 	 					 	{
 	 					 		foreach ($posts as $ROW) {
 	 					 			# code...

 	 					 			$user = new User();
 	 					 			$ROW_USER = $user->get_user($ROW['userid']);

 	 					 			include("post.php");
 	 					 		}
 	 					 	}
						?>
						
					</div>
			</div>
		</div>
		
		
	</body>
</html>