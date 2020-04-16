<?php
    //var_dump($_POST);
    $decoded = json_decode($_POST['json']);
    //var_dump($decoded);

    //do something with the data
    $hobbies = "";
    foreach($decoded->hobby as $v){
        if($v->isHobby) {
            $hobbies.= $v->hobbyName.",";
        }
    }
    $hobbies = trim($hobbies,",");

    //create our response
    $json = array();
    $json['sent'] = array("name"=>$decoded->firstname,
                          "email"=>$decoded->email,
                          "hobbies"=>$hobbies);

    $json['numErrors'] = 1;
    $json['error'] = array();
    $json['error'][] = "Wrong email!";
    $json['error'][] = "Wrong hobby!";


    //encode the array
    $encoded = json_encode($json);

    //send response back
    die($encoded);

?>