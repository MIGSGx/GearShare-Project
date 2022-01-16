<?php 

    session_start();

    if(isset($_SESSION['gearshare_userid']))
    {
        $_SESSION['gearshare_userid'] = NULL;
        unset($_SESSION['gearshare_userid']);

    }

    header("Location: login.php");
die;
