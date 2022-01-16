<?php 

	$corner_image = "default/male.jpg";
	if(isset($user_data)){
		
		if(file_exists($user_data['profile_image']))
		{
			$corner_image = $user_data['profile_image'];
		}else{

			if($user_data['gender'] == "Female"){

				$corner_image = "default/female.jpg";
			}
		}
	}
?>

<div id="main_bar">
			<div style="width:800px;margin:auto;font-size:30px;">
			<a href="index.php">
				<img id="main_bar_image" src="gearshare-short.png" style="float:left;">
			</a>

			<input type="text" id="search_box" placeholder="  Search...">

			<a href="profile.php">
			<img id="main_bar_image" src="<?php echo $corner_image?>" style="border: 1.5px solid white;float:right;">
			</a>
		</div>
		</div>