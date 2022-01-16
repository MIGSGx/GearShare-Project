<?php
include("classes/connect.php");
include("classes/login.php");
include("classes/user.php");
include("classes/post.php");
include("classes/image.php");

//Returns to previous page, if http referrer is not present (e.g. by copying pasting links), go to profile.
 if(isset($_SERVER['HTTP_REFERER'])){

        $return_to = $_SERVER['HTTP_REFERER'];
    }else{
        $return_to = "profile.php";
    }

	if(isset($_GET['type']) && isset($_GET['id'])){
		
		if(is_numeric($_GET['id'])){

			$allowed[] = 'post';
			$allowed[] = 'user';
			$allowed[] = 'comment';

			if(in_array($_GET['type'], $allowed)){

				$post = new Post();
				$user_class = new User();
				$post->like_post($_GET['id'],$_GET['type'],$_SESSION['gearshare_userid']);

				if($_GET['type'] == "user"){
					$user_class->follow_user($_GET['id'],$_GET['type'],$_SESSION['gearshare_userid']);
				}
			}

		}
		
	}

    
//GET LIKES FROM DATABASE
$post = new Post();
$likes = $post->get_likes($_GET['id'],$_GET['type']);

if(is_array($likes)){
	echo count($likes);
}else{
	echo 0;
}
header("Location:".$return_to);
die;
