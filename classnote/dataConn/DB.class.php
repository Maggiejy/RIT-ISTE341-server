<?php
class DB{
    private $dbh;

    function __construct(){
        $this->dbh = new mysqli($_SERVER['DB_SERVER'],
        $_SERVER['DB_USER'],$_SERVER['DB_PASSWORD'],
                                $_SERVER['DB']);
        
        if($this->dbh->connect_error){
            //don't do on production code
            echo "cnnection failed".mysqli_connect_error();
            die();
        }
    }

    function getAllPeople(){
        $data = array();
        if ($stmt = $this->dbh->prepare ("select * from people")){
            $stmt->execute();
            $stmt->store_result();
            $stmt->bind_result($id,$last,$first,$nick);
            if ($stmt->num_rows > 0){
                while($stmt->fetch()){
                    $data[] = array("id"=>$id, "first"=>$first, "last"=>$last, "nick"=>$nick);
                }
            }
        }
        return $data;
    }//getAllPeople
    
    function getAllPeopleAsTable(){
        $data = $this->getAllPeople();
        if(count($data > 0)) {
            $bigString = "<table border ='1'>\n<tr><th>ID</th><th>First</th><th>Last</th><th>Nick</th></tr>\n";
                    
                foreach($data as $row){
                $bigString .= "<tr><td><a href = 'phones.php?id={$row['id']}'>{$row['id']}</a></td><td>{$row['first']}</td><td>{$row['last']}</td><td>{$row['nick']}</td></tr>\n";
                }
                    
            $bigString .= "</table>\n";
        }else{
            $bigString = "<h2>Not peopel exist</h2>";
        }
        return $bigString;
    }//getAllPeopleAsTable
    
    function insert($last,$first,$nick){
        $queryString = "insert into people(LastName,FirstName,NickName) 
        values (?,?,?)";
        $insertID = -1;
        
        if($stmt = $this->dbh->prepare($queryString)){
            $stmt->bind_param("sss",$last,$first,$nick);
            $stmt->execute();
            $stmt->store_result();
            $insertID = $stmt->insert_id;
        }
        
        return $insertID;
    }//insert
    
    function update($fields){
        $queryString = "update people set ";
        $updateID = 0;
        $numRows = 0;
        $items = array();
        $type = "";
        
        foreach($fields as $k=>$v){
            switch($k){
                case 'nick':
                    $queryString .= "NickName = ?,";
                    $items[] = &$v; // may have to change to &$fields[$k]
                    $type .= "s";
                    break;
                case 'first':
                    $queryString .= "FirstName = ?,";
                    $items[] = &$v; // may have to change to &$fields[$k]
                    $type .= "s";
                    break;
                case 'last':
                    $queryString .= "LastName = ?,";
                    $items[] = &$v; // may have to change to &$fields[$k]
                    $type .= "s";
                    break;
                case 'id':
                    $updateID = intval($v);
                    break;
                
                
            }//switch
        }//foreach
        $queryString = trim($queryString,",");
        $queryString .= " where PersonID = ?";
        $type .= "i";
        $items[] = &$updateID;
        
        if($stmt = $this->dbh->prepare($queryString)){
            $refArr = array_merge(array($type),$items);
            $reg = new ReflectionClass('mysqli_stmt');
            $method = $reg -> getMethod("bind_param");
            $method->invokeArgs($stmt,$refArr);
            
            $stmt->execute();
            $stmt->store_result();
            $numRows = $stmt->affected_rows;
            
        }		
        return $numRows;
    }//update
    
    function delete($id){
        $queryString = "delete from people where PersonID = ?";
        $numRows = 0;
        
        if($stmt = $this->dbh->prepare($queryString)){
            $stmt->bind_param("i",intval($id));
            $stmt->execute();
            $stmt->store_result();
            $numRows = $stmt->affected_rows;
        }
        return $numRows;
    }//delete

    
}//class

