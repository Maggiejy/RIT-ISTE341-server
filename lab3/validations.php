<?php
function alphabetic($value) {
	$reg = "/^[A-Za-z]+$/";
	return preg_match($reg,$value);
}
//function alphabeticNumericPunct($value) {
//	$reg = "/^[A-Za-z0-9 _.,!?\"']+$/";
//	return( preg_match($reg,$value));
//}
function dateCheck($date, $format='m/d/Y') {
    return $date == date($format,strtotime($date));
}
function sanitize($string){
    strip_tags($string);
}
?>