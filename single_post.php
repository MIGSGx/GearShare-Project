<?php 

include("classes/connect.php");
include("classes/login.php");
include("classes/user.php");
include("classes/post.php");
include("classes/image.php");
include("classes/profile.php");

/*print_r($_SESSION);*///FOR DEBUGGING

$login=new Login();
$user_data=$login->check_login($_SESSION['gearshare_userid']);
 
$USER = $user_data;
 	
if(isset($_GET['id']) && is_numeric($_GET['id'])){

    $profile = new Profile();
    $profile_data = $profile->get_profile($_GET['id']);

    if(is_array($profile_data)){
        $user_data = $profile_data[0];
    }

}

//POST PARAMETERS AFTER SUBMIT
if($_SERVER['REQUEST_METHOD'] == "POST")
{

       $post = new Post();
       $id = $_SESSION['gearshare_userid'];
       $result = $post->create_post($id, $_POST,$_FILES);
       
       if($result == "")
       {
           header("Location: single_post.php?id=$_GET[id]");
           die;
       }else
       {

           echo "<div style='text-align:center;font-size:12px;color:white;background-color:grey;'>";
           echo "<br>The following errors occured:<br><br>";
           echo $result;
           echo "</div>";
       }
        
}

$Post = new Post();
$ROW = false;

$ERROR = "";
if(isset($_GET['id'])){

   $ROW = $Post->get_one_post($_GET['id']);
   //ISSUE: only gets current id of poster which changes image and name of comments the same as the poster
}else{

   $ERROR = "No post was found!";
}
?>

<!DOCTYPE html>
	<html>
	<head>
        <title> Post | GearShare</title>
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
		<?php include("header.php"); ?>

		<!--cover area-->
		<div style="width: 800px;margin:auto;min-height: 400px;">
		 
			<!--below cover area-->
			<div style="display: flex;">	

				<!--posts area-->
 				<div style="min-height: 400px;flex:2.5;padding: 20px;padding-right: 0px;">
 					
 					<div style="border-radius:10px;border:solid thin #aaa; padding: 10px;background-color: white;">

  					 <?php 

  					 		$user = new User();
  					 		$image_class = new Image();

  					 		if(is_array($ROW)){

 	 					 		$ROW_USER = $user->get_user($ROW['userid']);
  					 			 include("post.php");
  					 		}

  					 ?>

  					 <br style="clear: both;">

  					 <div style="border-radius:10px;border:solid thin #aaa; padding: 10px;background-color: white;">

 						<form method="post" enctype="multipart/form-data">

	 						<textarea name="post" placeholder="Post a comment"></textarea>
	 						<input type="hidden" name="parent" value="<?php echo $ROW['postid'] ?>">
	 						<input type="file" name="file">
	 						<input id="post_button" type="submit" value="Post">
	 						<br>
 						</form>
 					</div>
 
 						<?php 
 
 							$comments = $Post->get_comments($ROW['postid']);

 							if(is_array($comments)){

 								foreach ($comments as $COMMENT) {
 									

 									include("comment.php");
 								}
 							}
 						?>
 					</div>
  

 				</div>
			</div>

		</div>

	</body>
</html>