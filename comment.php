<!--HANDLES INDIVIDUAL POSTS-->
<div id="post"> 
    <div> 
        <?php
        //PROFILE IMAGE PER POST
            $image="default/male.jpg"; //CHECKS if users are male or female and sets default profile picture
            if($ROW_USER['gender']=="Female")
            {
                $image="default/female.jpg";
            }
            if(file_exists($ROW_USER['profile_image']))
            {
                $image_class=new Image();
                $image=$image_class->get_thumb_profile($ROW_USER['profile_image']);
            }

        ?>
        <img src="<?php echo $image?>" style="width:75px;margin-right:6px;border-radius:6px">
    </div>

    <div>
        <div style="font-weight:bold">
        <?php echo $ROW_USER['first_name']." ".$ROW_USER['last_name']?> 
        </div>
        <?php echo $COMMENT['post']?> 
        
        <br><br>

        <?php
        if(file_exists($COMMENT['image']))
        {
            $post_image=$image_class->get_thumb_post($COMMENT['image']); //CROPPING IS ENABLED
            echo "<img src='$post_image' style='width:100%;border-radius:15px;'/>";
            
        }?>                         
        <br><br><!--SHOW LIKES-->
        <a href="like.php?type=post&id=<?php echo $COMMENT['postid']?>"> Like(<?php echo $COMMENT['likes']?>) </a>   
        
        <a href="single_post.php?id=<?php echo $COMMENT['postid']?>"> Comment </a>  
        <?php 
				$post = new Post(); //HIDES EDIT & DELETE IF NOT CURRENT USER
				if($post->i_own_post($COMMENT['postid'],$_SESSION['gearshare_userid'])){
			    	echo "
					 <a href='delete.php?id=$COMMENT[postid]' >
		 				Delete
					</a>";
				}
 
			 ?>
        <span style="color:grey;"> 
        <?php echo $COMMENT['date']?>
        </span>
    </div>

</div>