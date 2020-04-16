<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<title>Array Tester</title>
</head>
<body>
<?php

echo "<h1>#1 - Standard indexed array</h1>";
$array1 = array(55,66,77,88); //$array1 = [55,66,77,88]
$array1[] = 99; //$array1 = [55,66,77,88,99]
$array1[0] = 11;

for ($i=0; $i<count($array1); $i++) {
	echo $array1[$i]."<br/>";
}

echo "<h1>#2 - Associative array value</h1>";
$array2 = array('RIT'=>"http://www.rit.edu",
				//10, //index of 0
				'Google'=>"http://www.google/com"); //single or double quote is fine

//$array2[] = "Hello"; //index of 1
$array2['CNN'] = "http://www.cnn.com";

foreach($array2 as $val){
	echo "$val <br/>";
}

echo "<h1>#3- Associative array value and key</h1>";
foreach($array2 as $k=>$val){
	echo "$k = $val <br/>";
}

echo "<h1>#4 - Associative array build some links</h1>";
foreach($array2 as $k=>$val){
	echo "<a href= '$val'>$k</a> <br/>";
}

echo "<h1>#5 - Nested Asoociative Array - one at a time</h1>";
$array3 = [
	'colors' => ['red','green','white'],
	'shapes' => ['circle','rectangle','square','triangle']
]; //the dimention does not to be equal

foreach($array3['colors'] as $v){
	echo "$v <br/>";
}

foreach($array3['shapes'] as $v){
	echo "$v <br/>";
}

echo "<h1>#6 - Nested Asoociative Array - nested for loops</h1>";
foreach($array3 as $key => $val){
	echo "<h2>$key</h2>";
	foreach($val as $v){
		echo "$v<br/>";
	}
	echo "<br/>";
}

?>
</body>
</html>
