<?php
session_start();
class Login
{
	private $error = "";
	 public function evaluate($data)
	 {
		$email = addslashes($data['email']);
		$password = addslashes($data['password']);

		$query = "select * from users where email = '$email' limit 1 ";

		$db = new Database();
		$result = $db->read($query);

		if($result)
		{
			$row = $result[0];
			if ($password == $row['password'])
			{
				//starts session data globally accessible
				$_SESSION['gearshare_userid'] = $row['userid'];
			}else
			{
				$this->error .= "Invalid password<br>";
			}
		}else
		{
			$this->error .= "Invalid email or password<br>";
		}

		return $this->error;
	 }	

	 private function hash_text($text){ 

		$text = hash("sha1", $text); //HASHING OF PASSWORDS
		return $text;
	}

	 public function check_login($id)
	{
		if(is_numeric($id))
		{

			$query = "select * from users where userid = '$id' limit 1 ";

			$DB = new Database();
			$result = $DB->read($query);

			if($result)
			{

				$user_data = $result[0];
				return $user_data;
			}else
			{
				header("Location: login.php");
				die;
			}
		}else
		{
			header("Location: login.php");
			die;
		}
	}
}

?>