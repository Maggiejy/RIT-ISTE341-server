<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<title>Lab1_3</title>
</head>
<body>
<?php
$sum = 0;
$count = 0;
$oneArray = array(87,75,93,95);
unset($oneArray[1]);
foreach($oneArray as $val){
	$sum += $val;
	$count++;
}
$average = $sum /$count;
echo "<h2>Average test score is ".$average."</h2>";

?>
</body>