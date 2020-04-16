<?php

    $expire = time() + 10; //10 seconds from now
    $path = "/~yj3010/";
    $domain = "serenity.ist.rit.edu";
    $secure = false;

    setcookie("test_cookie","arrgh!",$expire,$path,$domain,$secure);

    $counter = $_COOKIE['counter'];
    $counter++;
    setcookie("counter",$counter,$expire,$path,$domain,$secure);

    $getCounter = $_COOKIE['counter'];
    echo "<h2>counter = $getCounter</h2>";

    foreach($_COOKIE as $k => $v){
        echo "$k=$v<br/>";

    }
?>