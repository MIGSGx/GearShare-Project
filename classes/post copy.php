<?php

class Post{
    private $error="";
    public function create_post($userid, $data, $files)
    {
        //IMAGE
        if(!empty($data['post'])||!empty($files['file']['name'])) //CHECKS FOR TEXT AREA CONTENT
        {
            $myimage=""; //Image post default parameters for table
            $has_image=0;

            //IMAGE POSTING
            if(!empty($files['file']['name']))
            {

                //IMAGE UPLOADED IS VALID
				$folder="uploads/".$userid."/";

				//CREATE FOLDER
				if(!file_exists($folder))
				{
					mkdir($folder,0777,true);
				}
				
				$image_class = new Image();
				//$filename=$folder.$_FILES['file']['name'];
				$myimage=$folder.$image_class->generate_filename(15).".jpg";
				move_uploaded_file($_FILES['file']['tmp_name'],$myimage); //copies uploaded file to internal folder for website
				$image_class->resize_image($myimage,$myimage,1000,1000);//IMAGE RESIZE

                $has_image=1;
            }


            $post=addslashes($data['post']);
            $postid = $this->create_postid();
			$parent = 0;
			$DB = new Database();

			if(isset($data['parent']) && is_numeric($data['parent'])){

				$parent = $data['parent'];

				$sql = "update posts set comments = comments + 1 where postid = '$parent' limit 1";
				$DB->save($sql);
			}

			$query = "insert into posts (userid,postid,post,image,has_image,parent) values ('$userid','$postid','$post','$myimage','$has_image','$parent')";
			$DB->save($query);

		}else
		{
			$this->error .= "The text field is empty.<br>";
		}

		return $this->error;
	}

    public function get_posts($id)
    {
        $query="select * from posts where userid='$id' order by id desc";
        $db = new Database();
        $result=$db->read($query);

        if($result)
        {
            return $result;
        }else
        {
            return false;
        }
    }
    

    public function get_one_post($postid) //USED TO DELETE
    {
        $query="select * from posts where postid='$postid' limit 1";
        $db = new Database();
        $result=$db->read($query);

        if($result)
        {
            return $result[0];
        }else
        {
            return false;
        }
    }

    public function delete_post($postid) //USED TO DELETE
    {
        $query="delete from posts where postid='$postid' limit 1";
        $db = new Database();
        $db->read($query);
    }

    public function i_own_post($postid,$gearshare_userid) //CHECKS IF LOGGED IN USER OWNS POST (hides delete)
	{

		if(!is_numeric($postid)){
			
			return false;
		}

		$query = "select * from posts where postid = '$postid' limit 1";

		$DB = new Database();
		$result = $DB->read($query);
  		
  		if(is_array($result)){

  			if($result[0]['userid'] == $gearshare_userid){

  				return true;
  			}
  		}

  		return false;
	}
    public function get_comments($id)
	{

		$query = "select * from posts where parent = '$id' order by id asc limit 10";

		$DB = new Database();
		$result = $DB->read($query);

		if($result)
		{
			return $result;
		}else
		{
			return false;
		}
	}

    public function like_post($id,$type,$gearshare_userid){

        $DB = new Database();
        
       //save likes details
       $sql = "select likes from likes where type='$type' && contentid = '$id' limit 1";
       $result = $DB->read($sql);
       if(is_array($result)){

           $likes = json_decode($result[0]['likes'],true);

           $user_ids = array_column($likes, "userid");

           if(!in_array($gearshare_userid, $user_ids)){

               $arr["userid"] = $gearshare_userid;
               $arr["date"] = date("Y-m-d H:i:s");

               $likes[] = $arr;

               $likes_string = json_encode($likes);
               $sql = "update likes set likes = '$likes_string' where type='$type' && contentid = '$id' limit 1";
               $DB->save($sql);

               //increment the right table
               $sql = "update {$type}s set likes = likes + 1 where {$type}id = '$id' limit 1";
               $DB->save($sql);

           }else{

               $key = array_search($gearshare_userid, $user_ids);
               unset($likes[$key]);

               $likes_string = json_encode($likes);
               $sql = "update likes set likes = '$likes_string' where type='$type' && contentid = '$id' limit 1";
               $DB->save($sql);

               //increment the right table
               $sql = "update {$type}s set likes = likes - 1 where {$type}id = '$id' limit 1";
               $DB->save($sql);

           }
           

       }else{

           $arr["userid"] = $gearshare_userid;
           $arr["date"] = date("Y-m-d H:i:s");

           $arr2[] = $arr;

           $likes = json_encode($arr2);
           $sql = "insert into likes (type,contentid,likes) values ('$type','$id','$likes')";
           $DB->save($sql);

           //increment the right table
           $sql = "update {$type}s set likes = likes + 1 where {$type}id = '$id' limit 1";
           $DB->save($sql);
       }
    }

    public function get_likes($id,$type){

		$DB = new Database();
		$type = addslashes($type);

		if(is_numeric($id)){
 
			//get like details
			$sql = "select likes from likes where type='$type' && contentid = '$id' limit 1";
			$result = $DB->read($sql);
			if(is_array($result)){

				$likes = json_decode($result[0]['likes'],true);
				return $likes;
			}
		}


		return false;
	}

    private function create_postid()
	{
		$length = rand(4,19);
		$number = "";
		for ($i=0; $i < $length; $i++) { 
			# code...
			$new_rand = rand(0,9);
			$number = $number . $new_rand;
		}
		return $number;
	}

}

?>