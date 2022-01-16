<?php
include("classes/connect.php");
include("classes/login.php");
include("classes/user.php");
include("classes/post.php");
include("classes/image.php");

$login=new Login();
$user_data=$login->check_login($_SESSION['gearshare_userid']); //Selects the row of the current logged in user via userid matching

//DEBUGGING
//echo"<pre>";
//print_r($_GET);
//echo "</pre>";

if($_SERVER['REQUEST_METHOD'] == "POST")
{
	/*echo"<pre>";
	print_r($_POST);
	print_r($_FILES);
	echo"</pre>";*/
	
	if(isset($_FILES['file']['name']) && $_FILES['file']['name']!="") //checks if file is uploaded
	{
		if($_FILES['file']['type']=="image/jpeg") //checks if file is JPG
		{
			$allowed_size=(1024*1024)*7; //checks if file size is under the limit
				if($_FILES['file']['size']<$allowed_size)
			{
				//IMAGE UPLOADED IS VALID
				$folder="uploads/".$user_data['userid']."/";

				//CREATE FOLDER
				if(!file_exists($folder))
				{
					mkdir($folder,0777,true);
				}
				
				$image=new Image();
				//$filename=$folder.$_FILES['file']['name'];
				$filename=$folder.$image->generate_filename(15).".jpg";
				move_uploaded_file($_FILES['file']['tmp_name'],$filename); //copies uploaded file to internal folder for website
				
				//URL QUERY (checks GET variable in link and stores it)
				$change="profile";
				if(isset($_GET['change']))
				{
					$change=$_GET['change'];
				}

				//CROP & UPLOAD IMAGE using class from image.php according to GET variable (cover/profile photo)
				
				if($change=="cover")
				{
					if(file_exists($user_data['cover_image']))//DELETES EXISTING COVER
					{
						unlink($user_data['cover_image']);
					}
					$image->crop_image($filename,$filename,1920,1080); //select original image, destination, and size
				}
				else
				{
					if(file_exists($user_data['profile_image']))//DELETES EXISTING PROFILE IMAGE
					{
						unlink($user_data['profile_image']);
					}
					$image->crop_image($filename,$filename,640,640); //select original image, destination, and size
				}

				if(file_exists($filename))
				{

				$userid =$user_data['userid'];
				//Uses stored GET variable to determine what upload will be done
				if($change=="cover")
				{
					$query="update users set cover_image='$filename' where userid='$userid' limit 1"; //Specifies profile image of userid to database table
				}
				else
				{
					$query="update users set profile_image='$filename' where userid='$userid' limit 1"; //Specifies profile image of userid to database table
				}
				
				$db=new Database();
				$db->save($query);

				header("Location: profile.php");
				die;
				}
			}else{
				echo "<div style='text-align:center;font-size:12px;color:white;background-color:red;'>";
				echo "<br>The following errors occured:<br>";
				echo "Uploaded image is too big. Upload 3MB or lower.";
				echo "<br><br></div>";
			}

		}else{
			echo "<div style='text-align:center;font-size:12px;color:white;background-color:red;'>";
			echo "<br>The following errors occured:<br>";
			echo "Only JPEG and PNG images are allowed.";
			echo "<br><br></div>";
		}
	}else
	{
		echo "<div style='text-align:center;font-size:12px;color:white;background-color:red;'>";
		echo "<br>The following errors occured:<br>";
		echo "Please upload an image.";
		echo "<br><br></div>";
	}
}
?>

<!DOCTYPE html>
<html>
	<head>
		<title> Edit Image | GearShare</title>
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
	
	</style>
	
	<body>
	<?php include("header.php")?>
		
		<!--User Info-->
		<div style="width:800px;margin:auto;min-height:300px;margin-top:30px;">
			
			<!--Timeline-->
			<div style="display:flex;padding:20px; background-color:#353535;border-radius:10px;margin-top:15px;">
				
				<!--Uploading Photo-->
				<div style="flex:2.5">
				<p style="color:white;font-weight:bold;padding-bottom:5px;font-size:28px;text-align:center">
				<?php 
				$change="profile";
				if(isset($_GET['change']))
				{
					$change=$_GET['change'];
				}
				if($change=="cover")
				{
				echo "Upload new cover photo.";
				}
				else
				{
				echo "Upload new profile image.";
				}
				?>
				</p>
					<form method="post" enctype="multipart/form-data">
					<div style="color:white;border:none; border-radius:10px; padding:10px;">
						<input type="file" name="file"></input>
						<input id="post_button" type="submit" value="Upload"></input>
						<br>
					</div>
					</form>
				
			</div>
		</div>
		
		
	</body>
</html>