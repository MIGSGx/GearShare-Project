<?php 

include("classes/connect.php");
include("classes/login.php");
include("classes/user.php");
include("classes/post.php");
include("classes/image.php");
include("classes/register.php");
$login=new Login();
$user_data=$login->check_login($_SESSION['gearshare_userid']);
$userid =$user_data['userid'];

    //GETS CURRENT USER INFO
	$first_name = $user_data['first_name'];
	$last_name = $user_data['last_name'];
	$gender = $user_data['gender'];
	$email =$user_data['email'];
    $password=$user_data['password'];
    $camera=$user_data['camera'];
    $lens=$user_data['lens'];
    $category=$user_data['category'];

	if($_SERVER['REQUEST_METHOD'] == 'POST')
	{
        //ADDS UPDATED INFORMATION
        $email = $_POST['email'];
		$first_name = $_POST['first_name'];
		$last_name = $_POST['last_name'];
		$gender = $_POST['gender'];
        $password = $_POST['password'];
        $url_address="$first_name"."."."$last_name";
        $camera=$_POST['camera'];
        $lens=$_POST['lens'];
        $category=$_POST['category'];

        if(!$password){//PREVENTS SAVING IF NO PASSWORD
            echo "<div style='text-align:center;font-size:12px;color:white;background-color:red;'>";
			echo "<br>Please enter your old password or a new one.<br>";
			echo "<br></div>";

        }else{
        $userid =$user_data['userid'];
        $query="UPDATE users SET first_name = '$first_name', last_name ='$last_name',
            gender = '$gender', email = '$email', password = '$password', url_address='$url_address',
            camera ='$camera', lens='$lens', category='$category'
            WHERE
            userid='$userid'";
				
			$db=new Database();
			$db->save($query);
            header("location:profile.php");
        	}
        }
?>


<html>
	<head>
		<title>Edit Settings | GearShare</title>
		<link rel="stylesheet" href="style.css">
		<link rel="stylesheet" href="global-styles.css">
	</head>
	<style>
        body{
            background-color:#ebebeb;
        }
	</style>
	<body>
		<div id="bar">
			<img id="main_bar_image" src="gearshare-short.png" style="float:left;padding-left:40px;padding-top:5px;height:70px">
            <p style="font-size:30px;color:white;float:left;padding-left:30px;"> Welcome, <?php echo $user_data['first_name']." ".$user_data['last_name']?>.</p>
		</div>
		
		<div id="signup_bar">
		<p style="font-size:20px; font-weight:bold;">Edit User Settings<p>
            <p style="color:red">Please fill up all data before saving.</p>
		<br>
		
		<form method="post" action="">
			<input value="<?php echo $email ?>" name="email" type="text" id="text" placeholder="Email Address"><br/>
			<input value="<?php echo $first_name ?>" name="first_name" type="text" id="text" placeholder="First Name"><br/>
			<input value="<?php echo $last_name ?>" name="last_name" type="text" id="text" placeholder="Last Name"><br/>
			<select id="text" name="gender">
					
					<option selected disabled>Gender: (<?php echo $gender ?> or Female)</option>
					<option selected="selected">Male</option>
					<option>Female</option>

				</select>
			<!--GEAR SETTINGS for TIMELINE-->
			<input name="password" type="password" id="text" placeholder="Password"><br/><br/>
            
            <p style="font-size:20px; font-weight:bold;">My Gear<p>
            <input value="<?php echo $camera ?>" name="camera" type="text" id="text" placeholder="Main Camera"><br/>
            <input value="<?php echo $lens ?>" name="lens" type="text" id="text" placeholder="Main Lens"><br/>
            <input value="<?php echo $category ?>" name="category" type="text" id="text" placeholder="Photography Category"><br/><br/>
            
			<input type="submit" id="button" value="Save Settings">
            <br/></br><a href="profile.php">Cancel</a>
			</div>
		</form>
		
	<body>

</body>
</html>