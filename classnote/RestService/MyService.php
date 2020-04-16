<?php
//MyService.php
require_once "RestService.class.php";
require_once "Product.class.php";

class MyService extends RestService {
    //normally use a datastore
    private $products;
    public function __construct($request, $origin){
        parent::__construct($request);

        //create the dummy data
        for ($i=0;$i<5;$i++){
            $this->products[] = new Product("Product $i",$i);
        }
    }

    // .../functionname/verb/args
    //to see what gets set, var_dump($this->method, $this->verb, $this->args) in the constructor

    protected function product($args){
        if(count($args) == 0 && $this->method == "GET"){
            // /product
            //normally do a db query
            $prods = array();
            foreach($this->products as $prod){
                $prods[] = array("name"=>$prod->getName(),"id"=>$prod->getId());
            }
            return $prods;
        } else if(count($args) == 1 && $this->method == "GET") {
            // /product/{id}
            $p = $this->getProduct(intval($args[0]));
            if ($p){
                $prod = array(
                    "name" => $p->getName(),
                    "id" => $p->getId()
                );
                return $prod;
            } else {
                return parent::_response("Requested Resource Doesn't Exist",404);
            }
        } else if(count($args) == 1 && $this->method == "PUT") {
            // /product/{id} but PUT for the verb
            //want to make exists
            $p = $this->getProduct(intval($args[0]));
            if ($p){
                //info for put comes in as a string, so parse accordingly
                //update the db, etc.
                return "Product ($args[0]) updated";
            } else {
                return parent::_response("Requested Resource Doesn't Exist",404);
            }
        } else if($this->method == "POST") {
            //info comes in an array with index for each field
            //validate and insert
            return "Product {$this->request['name']} added.";

        }
    }//product

    private function getProduct($id){
        $p = null;
        for ($i = 0; $i < count($this->products);$i++){
            if($this->products[i]->getId() == $id){
                $p = $this->products[i];
                break;
            }
        }
        return $p;
    }
}//class

//set up the service
try{
    $API;
    if(isset($_SERVER['HTTP_ORIGIN'])){
        $API = new MyService($_REQUEST['request'],$_REQUEST['HTTP_ORIGIN']);


    } else {
        $API = new MyService($_REQUEST['request'],'');
    }
    echo $API->processAPI();
} catch(Exception $e){
    echo json_encode(Array("error"=>$e->getMessage()));
}
?>