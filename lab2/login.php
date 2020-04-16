<?php

   session_name("login");
   session_start();
   if(!empty($_GET['user'])&&!empty($_GET['password'])){
       //header("login.php?user={$_GET['user']}&password={$_GET['password']}");
       $_SESSION['username'] =  $_GET['user'];
       $_SESSION['password'] = $_GET['password'];
       $_SESSION['loggedIn'] = true; 
        setcookie("loggedIn", date('F j, Y g:i a'),time()+(10*60),'/'); 
        header("Location:admin.php");
        exit;      
    }else if(isset($_SESSION['username'])){
        echo "<a href='admin.php'>Admin Page</a>";
    }else{
        echo "Invalid Login.";
    }   
?>