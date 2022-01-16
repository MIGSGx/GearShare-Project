<?php 

	include("classes/connect.php");
	include("classes/register.php");

	$first_name = "";
	$last_name = "";
	$gender = "";
	$email = "";

	if($_SERVER['REQUEST_METHOD'] == 'POST')
	{
		$signup = new Signup();
		$result = $signup->evaluate($_POST);
		
		if($result != "")
		{
			echo "<div style='text-align:center;font-size:12px;color:white;background-color:red;'>";
			echo "<br>Error with registration:<br>";
			echo $result;
			echo "<br></div>";
		}else
		{
			header("Location: login.php");
			die;
		}
		$email = $_POST['email'];
		$first_name = $_POST['first_name'];
		$last_name = $_POST['last_name'];
		$gender = $_POST['gender'];
	}
?>


<html>
	<head>
		<title>GearShare | Register</title>
		<link rel="stylesheet" href="style.css">
		<link rel="stylesheet" href="global-styles.css">
	</head>
	<style>
	body {
		background-image: url('wallpaper2.jpg');
		background-repeat: no-repeat;
		background-attachment: fixed;
  		background-size: cover;
		backdrop-filter: blur(4px);
		}
	</style>
	<body style="background-color:#505050">
		<div id="bar">
			<img id="main_bar_image" src="gearshare-short.png" style="float:left;padding-left:50px;padding-top:5px;height:70px">
			<a href="login.php"  style="color:red"><div id="signup_button">Log In</div></a>
		</div>
		
		<div id="signup_bar">
		<p style="font-size:20px; font-weight:bold;">Sign Up to<p>
		<img src="gearshare-logo.png" style="width:50%;margin-top:-10px"> 
		<p>Create your GearShare Account today.</p>
		<a href="login.php"  style="color:#ffa31a;font-size:12px;">Click here to Login instead.</a>
		<br><br>
		
		<form method="post" action="">
			<input value="<?php echo $email ?>" name="email" type="text" id="text" placeholder="Email Address"><br/></br>
			<input value="<?php echo $first_name ?>" name="first_name" type="text" id="text" placeholder="First Name"><br/></br>
			<input value="<?php echo $last_name ?>" name="last_name" type="text" id="text" placeholder="Last Name"><br/><br/>
			<select id="text" name="gender">
					
					<option selected disabled>Gender: (<?php echo $gender ?> or Female)</option>
					<option selected="selected">Male</option>
					<option>Female</option>

				</select>
			</br>
			</br>
			<input name="password" type="password" id="text" placeholder="Password"><br/><br/>
			<input name="password2" type="password" id="text" placeholder="Re-enter Password"><br/><br/>
			<input type="submit" id="button" value="Sign Up">
			</div>
		</form>
		
	<body>

</body>
</html>