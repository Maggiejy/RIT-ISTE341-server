<html>

<body>
    <?php
    $title = "First PHP Program";
    //single line comment php7, older version using #
    /* 
        multiple line comment
    */
    //style needs to have consistency

    ?>
    <h1><?php echo "<p>Hi World! - $title</p>";
            echo "<br/> Name is ".$_GET['name']."<br/>";
        //echo is faster could use print
        //echo could take multi varaibles
        //for .$_GET: http://serenity.ist.rit.edu/~yj3010/ISTE-341/php1_1.php?name=maggie
        ?>
    </h1>

    <?php
        $version = phpversion();
        echo "<h2>The version of php is $version</h2>";
        phpinfo();

        var_dump($_SERVER);
        
    ?>
</body>
</html>