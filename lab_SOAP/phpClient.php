<?php
    ini_set("default_socket_timeout", 10);
    ini_set("soap.wsdl_cache_enabled", 0);
    ini_set("soap.wsdl_cache", 0);

    $soap_options = array("trace"=>1, "exceptions"=>1);
    $wsdl = "http://serenity.ist.rit.edu/~yj3010/lab_SOAP/phpServer.php?WSDL";
    
    try {
        $client = new SoapClient($wsdl,$soap_options);
        
        $response = $client->getMethods();
        var_dump($response);
        echo "$response<br />"; 
        echo "<hr />";
        
        $response = $client->getPrice("Apples");
        var_dump($response);
        echo "$response<br />"; 
        echo "<hr />";
        
        $response = $client->getCheapest();
        var_dump($response);
        echo "$response<br />"; 
        echo "<hr />";
        
        $response = $client->getCostliest();
        var_dump($response);
        echo "$response<br />"; 
        echo "<hr />";
        
        $response = $client->getProducts();
        var_dump($response);
        foreach($response as $res){
        echo "$res<br />"; 
        }
        echo "<hr />";
        
    } catch(SoapFault $e) {
        var_dump($e);
    }
?>