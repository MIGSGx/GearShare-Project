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
			header("Location:profile.php");
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
	margin-top:-2500px;
	border-radius:30px;
	margin-left:-530px;
	border:solid #ffa31a 3px;
	}
	#profile_menu{
		width:100px;
		display:inline-block;
		margin:2px;
		color: #ffa31a;
		font-size:12px;
		background-color:#1b1b1b;
		height:30px;
		border-radius:50px;
		/*Text aligning*/
		text-align: center;
		vertical-align: middle;
		line-height: 30px;   /*same as height*/
	}
	textarea{ 
		width:100%;
		border:none;
		font-family:Helvetica;
		font-size:14px;
		height:60px;
		outline:none;
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
	color:#e9d2b0;
	}
	a:hover{
	text-decoration:none;
	color:#ffa31a;
	}
	</style>
	
	<body>
		<?php include("header.php")?>
		<!--User Info-->
		<div style="width:800px;margin:auto;min-height:300px;margin-top:30px;">
			<div style="background-color:#353535; text-align:center; color:white;border-radius:15px;">
				
				<!--SET COVER PHOTO-->
				<?php
					$image="default-cover2.jpg";
					if(file_exists($user_data['cover_image']))
					{
						$image=$user_data['cover_image'];
					}else{
						$image="default_cover2.jpg"; //SETS DEFAULT COVER PHOTO
					}
				?>
				<img src="<?php echo $image?>" style="height:400px;width:100%;border-radius:10px;">
				
				<!--SET PROFILE PICTURE (DEFAULT OR CUSTOM)-->
				<?php
					$image="default/male.jpg";
					if($user_data['gender']=="Female")
					{
						$image="default/female.jpg";
					}
					if(file_exists($user_data['profile_image']))
					{
						$image=$user_data['profile_image'];
					}
				?>
				<img id="profile_image" src="<?php echo $image?>">

				<br/> <!--FULL NAME-->
				<div style="font-size:25px"><?php echo $user_data['first_name'] . " " . $user_data['last_name']  ?></div>
				<br/>
				<div id="profile_menu"><a href="index.php">Timeline</a></div>
				<div id="profile_menu"><a href="edit_profile_image.php?change=profile">Profile Image</a></div>
				<div id="profile_menu"><a href="edit_profile_image.php?change=cover">Cover Photo</a></div>
				<div id="profile_menu"><a href="settings.php">Settings</a></div>
				<div id="profile_menu"><a href="logout.php">Log Out</a></div>
				<div style="height:20px"></div>
			</div>
			
			<!--Timeline-->
			<div style="padding:20px; background-color:#353535;border-radius:10px;margin-top:15px;">
				<!--Submit Post TEXTAREA-->
				<div style="width:100%"></div>
					<div style="border:solid thin #AAA; border-radius:10px; padding:10px;background-color:white">
						<form method="post" enctype="multipart/form-data">
							<textarea name="post" placeholder="What's on your mind?"></textarea>
							<input type="file" name="file"></input>
							<input id="post_button" type="submit" value="Post"></input>
							<br>
						</form>
					</div>
				<!--Posts - Uses Loop to generate posts from a table through post.php--> 
					<div id="post_area">
					<?php
					if($posts)
					{
						foreach($posts as $ROW) 
						{
							$user = new User();
							$ROW_USER=$user->get_user($ROW['userid']);
							include("post.php");
						}
					}
					?>
					</div>
			</div>
		</div>
		
		
	</body>
</html>