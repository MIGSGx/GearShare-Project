<?php
include("classes/connect.php");
include("classes/login.php");
include("classes/user.php");
include("classes/post.php");
include("classes/image.php");

$login=new Login();
$user_data=$login->check_login($_SESSION['gearshare_userid']);


//Returns to previous page, if http referrer is not present (e.g. by copying pasting links), go to profile.
if(isset($_SERVER['HTTP_REFERER'])){

	$return_to = $_SERVER['HTTP_REFERER'];
}else{
	$return_to = "profile.php";
}

	$Post=new Post();
	$ROW=$Post->get_one_post($_GET['id']); //Gets ID of post
	$Post->delete_post($ROW['postid']); //Deletes stored ID
	
	header("Location:".$return_to);
	die;

?>