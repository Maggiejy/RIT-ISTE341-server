<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<title>Untitled</title>
 	<style type="text/css">
 		form div
 		{
 			margin: 1em;
 		}
 		form div label
 		{
 			float: left;
 			width: 20%;
 		}
 		form div.radio {
 			float: left;
 		}
 		.clearfix {
 			clear: both;
 		}
 	</style>
</head>
<body>
	<form action = "/~yj3010/ISTE-341/lab3/feelings.php" method="POST">
		<div>
			<label for="fname">First Name:</label>
			<input type="text" name="fname" size="30" />
		</div>
		<div>
			<label for="lname">Last Name:</label>
			<input type="text" name="lname" size="30" />
		</div>
		<div>
			<label for="date">Date:</label>
			<input type="text" name="date" size="30" />
		</div>
		<div>
			<label for="comments">Comments:</label>
			<textarea name="comments" rows="3" cols="30"></textarea>
		</div>
		<div>
			<label for="mood">Mood:</label>
			<div class="radio">
				<input type="radio" name="mood" value="happy" />Happy<br />
				<input type="radio" name="mood" value="mad" />Mad<br />
				<input type="radio" name="mood" value="indifferent" />Indifferent<br />
			</div>
		</div>
		<div class="clearfix">
			<input type="reset" value="Reset Form" />
			<input type="submit" name="submit" value="Submit Form" />
		</div>	
	</form>
<?php
include ("validations.php");

if(isset($_POST['submit'])){
    $errorMsg = false;
	$errorText = "<ul><strong>Please insert correct form of:</strong><br />";
 
	$fname = isset($_POST['fname']) ? trim($_POST['fname']) : '';
	$lname = isset($_POST['lname']) ? trim($_POST['lname']) : '';
	$date = isset($_POST['date']) ? trim($_POST['date']) : '';
	$comments = isset($_POST['comments']) ? trim($_POST['comments']) : '';
    if ($date =="" || alphabetic($date) || !dateCheck($date)){
        $errorText = $errorText.'<li>Date (MM/DD/YYYY)</li>';
        $errorMsg = true;
    }else{
        echo "Today is $date.<br/>";
    }
    
    if($fname == "" || !alphabetic($fname) || strlen($fname) > 30 && $lname == "" || !alphabetic($lname) || strlen($lname) > 30 ) {
    	$errorText = $errorText.'<li>Full name (alphabetic)</li>';
    	$errorMsg = true;
  	}else{
        echo "Hello $fname $lname.<br/>";
    }
    if(!empty($_POST['mood'])) {
        if($_POST['mood'] ==='happy'){
            echo "I'm glad you're happy today.<br/>";
        }elseif($_POST['mood'] === 'mad'){
            echo "I'm sorry you're mad today.<br/>";
        }elseif($_POST['mood'] === 'indifferent'){
            echo "Hope you could be happy tomorrow.<br/>";
        }
    }else{
        $errorText = $errorText.'<li>Mood</li>';
        $errorMsg = true; 
    }
    
    
    if ($comments =="" || strlen($comments)>500) {
        sanitize($comments);
        $errorText = $errorText.'<li>Comments</li>';
        $errorMsg = true;  		 
  	}else{
        echo "Your comments: $comments<br/>";
    }   
    $errorText .="</ul>";        
        
    echo $errorText;    
    
}
?>
</body>
</html>
