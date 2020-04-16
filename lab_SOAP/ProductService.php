<?php
class ProductService {
     /**
     * Get all the methods
     *
     * @return string[] $temp
     */
     function getMethods() {
        $temp = array("getPrice(product)",
                 "getProducts()",
                 "getCheapest()",
                 "getCostliest()");
        return $temp;
     }
     
    /**
     * get the price of one product
     *
     * @param string $product
     * @return string $price
     */
     function getPrice($product){
        $products = array("Apples"=>"3.99",
                    "Peaches"=>"4.05",
                    "Pumpkin"=>"13.99",
                    "Pie"=>"8.00");
		$price = $products[$product];
        return $price;
     }
     /**
     * get the list of products
     *
     * @return string[] $products
     */
     function getProducts(){
        $products = array("Apples", "Peaches", "Pumpkin", "Pie");
        return $products;
                              
    }
    
    /**
     * get the name of the cheapest product
     *
     * @return string $cheapest
     */
    function getCheapest() {
        $cheapest = "";
		$products = array("Apples"=>"3.99",
                    "Peaches"=>"4.05",
                    "Pumpkin"=>"13.99",
                    "Pie"=>"8.00");
		$smallest = 100; 
		foreach($products as $k=>$v){
			if(floatval($v)<=$smallest){
				$smallest = floatval($v);
				$cheapest = $k;
			}
		}
        return $cheapest;
    }
    
    /**
     * get the name of the costliest product
     *
     * @return string $costliest
     */
    function getCostliest() {
        $costliest = "";
		$products = array("Apples"=>"3.99",
                    "Peaches"=>"4.05",
                    "Pumpkin"=>"13.99",
                    "Pie"=>"8.00");
		$largest = 0; 
		foreach($products as $k=>$v){
			if(floatval($v)>=$largest){
				$largest = floatval($v);
				$costliest = $k;
			}
		}
        return $costliest;
    } 
}
?>