<?php 

	include("classes/connect.php");
	include("classes/login.php");
	
	$email = "";
	$password = "";

	if($_SERVER['REQUEST_METHOD'] == 'POST')
	{
		$login = new Login();
		$result = $login->evaluate($_POST);
		
		if($result != "")
		{
			echo "<div style='text-align:center;font-size:12px;color:white;background-color:red;'>";
			echo "<br>The following errors occured:<br>";
			echo $result;
			echo "<br></div>";
		}else
		{
			header("Location: profile.php");
			die;
		}
		$email = $_POST['email'];
		$password = $_POST['password'];
	}
?>

<html>
	<head>
		<title>GearShare | Login</title>
		<link rel="stylesheet" href="style.css">
		<link rel="stylesheet" href="global-styles.css">
	</head>
	<style>
		body {
		background-image: url('wallpaper4.jpg');
		background-repeat: no-repeat;
		background-attachment: fixed;
  		background-size: cover;
		backdrop-filter: blur(4px);
		}
	video 
	{
		object-fit: cover;
		width: 100vw;
		height: 100vh;
		position: fixed;
		top: 0;
		left: 0;
	}
	</style>
	<body style="background-color:#505050">

		<div id="bar">
			<img id="main_bar_image" src="gearshare-short.png" style="float:left;padding-left:50px;padding-top:5px;height:70px">
			<a href="register.php"  style="color:red"><div id="signup_button">Sign Up</div></a>
		</div>
		
		<div id="signup_bar">
		<p style="font-size:20px; font-weight:bold;">Log in to<p>
		<img src="gearshare-logo.png" style="width:50%;margin-top:-10px">
		<br><br>
		
		<form method="post">
		<input name="email" value="<?php echo $email ?>" type="text" id="text" placeholder="Email Address"><br/></br>
		<input name="password" value="<?php echo $password ?>" type="password" id="text" placeholder="Password"><br/><br/>
		<a href="register.php"  style="color:#ffa31a;font-size:12px;">Don't have an account? Create one here.</a> <br><br>
		
		<input type="submit" id="button" value="Log In">
		</form>
		</div>
		
	<body>

</body>
</html>