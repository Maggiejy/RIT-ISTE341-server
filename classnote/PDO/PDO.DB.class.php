<?php
class DB{
    private $dbh;

    function __construct(){
        try{
            //open a connection
            $this->dbh = new PDO("mysql:host={$_SERVER['DB_SERVER']}; 
                                dbname={$_SERVER['DB']}", 
                                       $_SERVER['DB_USER'], 
                                       $_SERVER['DB_PASSWORD']);
            //change error reporting
            $this->dbh->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
        
        } catch (PDOException $e){ 
            die("There was a problem");
        }
    }//constructor

    function getPeople($id){
        try {
            $data = array();
            $stmt = $this->dbh->prepare("select * from people where PersonId = :id");
            $stmt->execute(array("id"=>$id)); //array(":id"=>$id)
            while ($row = $stmt->fetch()){
                $data[] = $row;
            }
            return $data;
        } catch (PDOException $e) {
            echo $e->getMessage();
            die();
        }
    }//getPeople

    function getPeopleAlt($id) {
        try{
            $date = array();
            $stmt = $this->dbh->prepare("select * from people where PersonID = :id");
            $stmt->bindParam(":id",$id,PDO::PARAM_INT);
            $stmt->execute();
            while ($row = $stmt->fetch()){
                $data[] = $row;
            }
            return $data;
        
        } catch (PDOException $e) {
            echo $e->getMessage();
            die();
        }
    }//getPeopleAlt

    function getPeopleAlt2($id) {
        try{
            $date = array();
            $stmt = $this->dbh->prepare("select * from people where PersonID = :id");
            $stmt->bindParam(":id",$id,PDO::PARAM_INT);
            $stmt->execute();
            $data = $stmt->fetchAll();
            return $data;
        
        } catch (PDOException $e) {
            echo $e->getMessage();
            die();
        }
    }//getPeopleAlt2

    function insert($last, $first, $nick) {
        try{
            $stmt = $this->dbh->prepare("insert into people (LastName, FirstName, NickName) 
            values (:last, :first, :nick)");
            $stmt->execute(array("last"=>$last, "first"=>$first, "nick"=>$nick));
            return $this->dbh->lastInsertId();
        } catch (PDOException $e) {
            echo $e->getMessage();
            die();
        }
    }//insert

    function getAllObjects() {
        try{
            include "Person.class.php";
            $date = array();
            $stmt = $this->dbh->prepare("select * from people");
            $stmt->execute();
            $stmt->setFetchMode(PDO::FETCH_CLASS,"Person");
            while ($person = $stmt->fetch()){
                $data[] = $person;
            }
            return $data;
        
        } catch (PDOException $e) {
            echo $e->getMessage();
            die();
        }
    }//getAllObjects

}

?>