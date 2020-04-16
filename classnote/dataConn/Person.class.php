<?php
    class Person {
        private $PersonID;
        private $LastName;
        private $FirstName;
        private $NickName;
        
        public function whoAmI() {
            return "I am {$this->FirstName} {$this->LastName} and my nickname is {$this->NickName}";
        }//whoAmI
        
        
        
    }//class