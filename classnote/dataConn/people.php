<?php
	require_once("DB.class.php");
	
	$db = new DB();
	
	echo $db->getAllPeopleAsTable();

	//$id = $db->insert("Taylor","James","JT");

	//if ($id>0){
	//	echo "<p>You inserted 1 row whose id is $id</p>";
	//} else {
	//	echo "<p>You failed to insert row. </p>";
	//}

	//$num = $db->update(array('id'=>4,'nick'=>'Jay'));
	
	$num = $db->delete(14);

	echo "<p>You deleted $num row(s).</p>";

	//echo "<p>You update $num row(s). </p>";
	echo $db->getAllPeopleAsTable();
?>