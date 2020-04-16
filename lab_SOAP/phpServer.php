<?php
    require_once("php-wsdl/class.phpwsdl.php");
    $soap = PhpWsdl::CreateInstance(
        null,
        null,
        "./php-wsdl/cache",
        Array("ProductService.php"),
        null,
        null,
        null,
        false,
        false
    );    
    ini_set("soap.wsdl_cache_enabled", 0);
    ini_set("soap.wsdl_cache",0);
    PhpWsdl::$CacheTime = 0;
    PhpWsdl::DisableCache();
    if ($soap->IsWsdlRequested()){
        $soap->Optimize = false;
    }
    
    $soap->RunServer();
?>