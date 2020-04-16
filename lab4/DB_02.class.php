 <?php
class DB{
    private $dbh;
    function __construct(){
        try{
            $this->dbh = new PDO("mysql:host={$_SERVER['DB_SERVER']}; 
                                    dbname={$_SERVER['DB']}", 
                                           $_SERVER['DB_USER'], 
                                           $_SERVER['DB_PASSWORD']);
            //change error reporting
            $this->dbh->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
            
           } catch (PDOException $e){ 
           
           }

    }//constructor

    function getPeopleParameterizedAlt($id) {
        try{
            $data = array();
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
    }//getPeopleParameterizedAlt
    
    function getAllPeople() {
        try{
            //include "Person.class.php";
            $data = array();
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

    function getAllPeopleAsTable() {
        $data = $this->getAllPeople();
        if (count($data) > 0) {
            $bigString = "<table border='1'>\n
                            <tr><th>ID</th><th>First</th><th>Last</th><th>NickName</th></tr>\n";
            foreach ($data as $row) {
                $bigString .="<tr><td><a href='Lab4_4.php?id={$row['PersonID']}'>{$row['PersonID']}</a></td>
                                  <td>{$row['FirstName']}</td>
                                  <td>{$row['LastName']}</td>
                                  <td>{$row['NickName']}</td></tr>\n";
            }
            $bigString .="</table>\n";
        } else {
            $bigString = "<h2>No people exists</h2>"; 
        }
        return $bigString;
    }//getAllPeopleAsTable

    function getPhoneParameterizedAlt($id) {
        try{
            $data = array();
            $stmt = $this->dbh->prepare("select * from phonenumbers where PersonID = :id");
            $stmt->bindParam(":id",$id,PDO::PARAM_INT);
            $stmt->execute();
            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $data;
        
        } catch (PDOException $e) {
            echo $e->getMessage();
            die();
        }
    }//getPhoneParameterizedAlt

    function getSelectedPhone($id) {
        try{
            include_once "phonenumbers.class.php";
            $date = array();
            $stmt = $this->dbh->prepare("select * from phonenumbers where PersonID = :id");
            $stmt->bindParam(":id",$id,PDO::PARAM_INT);
            $stmt->execute();
            $stmt->setFetchMode(PDO::FETCH_CLASS,"Phonenumbers");
            while ($phone = $stmt->fetch()){
                $data[] = $phone;
            }
            return $data;
        
        } catch (PDOException $e) {
            echo $e->getMessage();
            die();
        }
    }//getSelectedPhone

    
    function getSelectedPhoneAsTable($id) {
        $data = $this->getPhoneParameterizedAlt($id);
        if (count($data) > 0) {
            $bigString = "<table border='1'>\n
                            <tr><th>Person ID</th><th>Phone Type</th><th>Phone #</th><th>Area Code</th></tr>\n";
            foreach ($data as $row) {
                $bigString .="<tr><td>{$row['PersonID']}</td>
                                  <td>{$row['PhoneType']}</td>
                                  <td>{$row['PhoneNum']}</td>
                                  <td>{$row['AreaCode']}</td></tr>\n";

            }
            $bigString .="</table>\n";
			$bigString .="<a href='Lab4_3.php'>(Go back to Lab4_3.php)</a>";
        } else {
            $bigString = "Error"; 
        }
        return $bigString;
    }//getSelectedPhoneAsTable
    
    function getSelectedPhoneAsTable2($id) {
        $data = $this->getSelectedPhone($id);
        if (count($data) > 0) {
            $bigString = "<table border='1'>\n
                            <tr><th>Person ID</th><th>Phone Type</th><th>Phone #</th><th>Area Code</th></tr>\n";
            foreach ($data as $row) {
                $bigString .="<tr><td>{$row['PersonID']}</td>
                                  <td>{$row['PhoneType']}</td>
                                  <td>{$row['PhoneNum']}</td>
                                  <td>{$row['AreaCode']}</td></tr>\n";

            }
            $bigString .="</table>\n";
			$bigString .="<a href='Lab4_3.php'>(Go back to Lab4_3.php)</a>";
        } else {
            $bigString = "Error"; 
        }
        return $bigString;
    }//getSelectedPhoneAsTable
    
}//class