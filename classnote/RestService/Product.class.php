<?php
//Product.class.php
    class Product {
        private $name;
        private $id;

        public function __construct($name="TBD", $id="TBD"){
            $this->name = $name;
            $this->id = $id;
        }

        public function getName() {return $this->name;}
        public function setName($name){$this->name = $name;}

        public function getId() {return $this->id;}
        public function setId($id){$this->id = $id;}

        

    }

?>