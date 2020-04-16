<?php
/*DB_02.class.php*/

class DB{
    private $connection;
    function __construct(){
        $this->connection = new mysqli($_SERVER['DB_SERVER'],
        $_SERVER['DB_USER'],$_SERVER['DB_PASSWORD'],
                                $_SERVER['DB']);

        
        if ($this->connection->connect_error){
            echo "Conenction failed ".mysqli_connect_error();
            die();
        }
    }
    
    function getAllPeople(){
        $date = array();
        if ($stmt = $this->connection->prepare("SELECT * FROM people")){
            $stmt->execute();
            $stmt->store_result();
            $stmt->bind_result($id,$last,$first,$nick);  
            
            if($stmt->num_rows > 0){
                while ($stmt->fetch()) {
                    $data[] = array('id'=>$id, 
                                    'first'=>$first, 
                                    'last'=>$last, 
                                    'nick'=>$nick);
                }           
            }//num rows >0
        }//if $stmt
        return $data;
    
    }//getAllPeople
    
    function getAllPeopleAsTable() {
        $data = $this->getAllPeople();
        if (count($data) > 0) {
            $bigString = "<table border='1'>\n
                            <tr><th>ID</th><th>First</th><th>Last</th><th>NickName</th></tr>\n";
            foreach ($data as $row) {
                $bigString .="<tr><td><a href='Lab4_2.php?id={$row['id']}'>{$row['id']}</a></td>
                                  <td>{$row['first']}</td>
                                  <td>{$row['last']}</td>
                                  <td>{$row['nick']}</td></tr>\n";
            }
            $bigString .="</table>\n";
        } else {
            $bigString = "<h2>No people exists</h2>"; 
        }
        return $bigString;
    }//getAllPeopleAsTable
    
	function getSelectedPhone($id){
        $data = array();
        if ($stmt = $this->connection->prepare("SELECT * FROM phonenumbers where PersonID = ?")){
            $stmt->bind_param('i',intval($id));
            $stmt->execute();
            $stmt->store_result();
            $stmt->bind_result($id,$pType,$pNum,$areaCode);  
            
            if($stmt->num_rows > 0){
                while ($stmt->fetch()) {
                    $data[] = array('id'=>$id, 
                                    'PhoneType'=>$pType, 
                                    'PhoneNum'=>$pNum, 
                                    'AreaCode'=>$areaCode);
                }           
            }//num rows >0
        }//if $stmt
        return $data;    
    }//getSelectedPhone
	
	function getSelectedPhoneAsTable($id) {
        $data = $this->getSelectedPhone($id);
        if (count($data) > 0) {
            $bigString = "<table border='1'>\n
                            <tr><th>Person ID</th><th>Phone Type</th><th>Phone #</th><th>Area Code</th></tr>\n";
            foreach ($data as $row) {
                $bigString .="<tr><td>{$row['id']}</td>
                                  <td>{$row['PhoneType']}</td>
                                  <td>{$row['PhoneNum']}</td>
                                  <td>{$row['AreaCode']}</td></tr>\n";

            }
            $bigString .="</table>\n";
			$bigString .="<a href='Lab4_1.php'>(Go back to Lab4_1.php)</a>";
        } else {
            $bigString = ""; 
        }
        return $bigString;
    }//getSelectedPhoneAsTable

}//class


