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
/*Login Page*/    
    function checkLogin($name,$password) {
        $pwd = hash("sha256",$password);
        try{
            $data = array();
            $stmt = $this->dbh->prepare("select * from attendee where name = :name and password = :pwd");
            $stmt->bindParam(":name",$name,PDO::PARAM_STR);
            $stmt->bindParam(":pwd",$pwd,PDO::PARAM_STR);
            $stmt->execute();
            $data = $stmt->fetchAll();
            return $data;
        
        } catch (PDOException $e) {
            echo $e->getMessage();
            die();
        }
    }//getAttendeeByName
    
    function getAttendeeByName($name) {
        try{
            $data = array();
            $stmt = $this->dbh->prepare("select * from attendee where name = :name");
            $stmt->bindParam(":name",$name,PDO::PARAM_STR);
            $stmt->execute();
            $data = $stmt->fetchAll();
            return $data;
        
        } catch (PDOException $e) {
            echo $e->getMessage();
            die();
        }
    }//getAttendeeByName
    
    function register($name, $password) {
        $pwd = hash("sha256",$password);
        try{
            $stmt = $this->dbh->prepare("insert into attendee (name, password, role) 
            values (:name, :password, 3)");
            $stmt->execute(array("name"=>$name, "password"=>$pwd));
            return $this->dbh->lastInsertId();
        } catch (PDOException $e) {
            echo $e->getMessage();
            die();
        }
    }//register
    
/*Event Page*/
    function getEvents() {
        try{
            $data = array();
            $stmt = $this->dbh->prepare("select e.idevent as idevent, e.name as ename, e.datestart as estart, v.name as vname, s.numberallowed as snum, s.idsession as idsession, s.startdate as sstartdate, s.enddate as senddate 
            from event e left join session as s 
            on e.idevent = s.event 
            left join venue as v
            on e.venue = v.idvenue
            order by e.idevent, s.idsession");
            $stmt->execute();
            while ($row = $stmt->fetch()){
                $data[] = $row;
            }
            return $data;
        
        } catch (PDOException $e) {
            echo $e->getMessage();
            die();
        }
    }//getEvents
    
    function getEventsAsTable() {
        $data = $this->getEvents();
        if (count($data) > 0) {
            $bigString = "<div class='tables'><table id='events'>\n
                            <tr><th>ID</th><th>Name</th><th>Start Date&Time</th><th>Venue</th>
                            <th>Session#</th>
                            <th>Session Id</th>
                            <th>Session Start Time</th>
                            <th>Session End Time</th></tr>\n";
            foreach ($data as $row) {
                $bigString .="<tr><td>{$row['idevent']}</td>
                                  <td>{$row['ename']}</td>
                                  <td>{$row['estart']}</td>
                                  <td>{$row['vname']}</td>
                                  <td>{$row['snum']}</td>
                                  <td>{$row['idsession']}</td>
                                  <td>{$row['sstartdate']}</td>
                                  <td>{$row['senddate']}</td></tr>\n";
            }
            $bigString .="</table></div>\n";
        } else {
            $bigString = "<h2>No event exists</h2>"; 
        }
        return $bigString;
    }//getEventsAsTable
    
    
/*Admin Page*/
    function ifManager($name) {
        try{
            $data = array();
            $stmt = $this->dbh->prepare("select * from attendee where role=2 and name=:name");
            $stmt->bindParam(":name",$name,PDO::PARAM_STR);
            $stmt->execute();
            $data = $stmt->fetchAll();
            return $data;
        
        } catch (PDOException $e) {
            echo $e->getMessage();
            die();
        }
    }//ifManager
    
    function ifAdmin($name) {
        try{
            $data = array();
            $stmt = $this->dbh->prepare("select * from attendee where role=1 and name=:name");
            $stmt->bindParam(":name",$name,PDO::PARAM_STR);
            $stmt->execute();
            $data = $stmt->fetchAll();
            return $data;
        
        } catch (PDOException $e) {
            echo $e->getMessage();
            die();
        }
    }//ifAdmin    
//AS MANAGER
    function getEventofM($name){
         try{
            $data = array();
            $stmt = $this->dbh->prepare("select e.* 
            from event as e
            join manager_event as me 
            on e.idevent = me.event 
            join attendee as a 
            on me.manager = a.idattendee
            where a.name=:name");
            $stmt->bindParam(":name",$name,PDO::PARAM_STR);
            $stmt->execute();
            $data = $stmt->fetchAll();
            return $data;
        
        } catch (PDOException $e) {
            echo $e->getMessage();
            die();
        }
    }//getEventofM
    function getEventsofMAsTable($name) {
        $data = $this->getEventofM($name);
        if (count($data) > 0) {
            $bigString = "<div class='tableAdmin'><table id='alladmin'>\n";
            $bigString .= "<tr><td colspan='8' align='center'>
                        <form method='POST'>
                        <input type='submit' name='addEventB' value='Add Event' />
                        </form></td></tr>";
            $bigString .= "<tr><th>Delete</th><th>Edit</th><th>ID</th>
                            <th>Name</th><th>Start Date&Time</th>
                            <th>End Date&Time</th>
                            <th>Number Allowed</th>
                            <th>Venue</th></tr>\n";
            foreach ($data as $row) {
                $bigString .="<tr><td><a href='admin.php?deventid={$row['idevent']}'>Delete</a></td>
                                    <td><a href='admin.php?eeventid={$row['idevent']}'>Edit</a></td>
                                    <td>{$row['idevent']}</td>
                                  <td>{$row['name']}</td>
                                  <td>{$row['datestart']}</td>
                                  <td>{$row['dateend']}</td>
                                  <td>{$row['numberallowed']}</td>
                                  <td>{$row['venue']}</td></tr>\n";
            }
            $bigString .="</table></div>\n";
        } else {
            $bigString = "<h2>exists</h2>"; 
        }
        return $bigString;
    }//getEventsofMAsTable
    function getSessionofM($name){
         try{
            $data = array();
            $stmt = $this->dbh->prepare("select s.* 
            from session as s 
            join event as e 
            on s.event = e.idevent 
            where idevent in 
            (select event from
            manager_event as me
            join attendee as a
            on me.manager = a.idattendee
            where a.name = :name)");
            $stmt->bindParam(":name",$name,PDO::PARAM_STR);
            $stmt->execute();
            $data = $stmt->fetchAll();
            return $data;
        
        } catch (PDOException $e) {
            echo $e->getMessage();
            die();
        }
    }//getEventofM
    function getSessionofMAsTable($name) {
        $data = $this->getSessionofM($name);
        if (count($data) > 0) {
            $bigString = "<div class='tableAdmin'><table id='alladmin'>\n";
            $bigString .= "<tr><td colspan='8' align='center'>
                        <form action = '/~yj3010/ISTE-341/project1/admin.php' method='POST'>
                        <input type='submit' name='addSessionB' value='Add Session' />
                        </form></td></tr>";
            $bigString .= "<tr><th>Event ID</th><th>Delete</th><th>Edit</th><th>Session ID</th><th>Name</th><th>Number Allowed</th><th>Start Date&Time</th><th>End Date&Time</th></tr>\n";
            foreach ($data as $row) {
                $bigString .="<tr><td>{$row['event']}</td>
                            <td><a href='admin.php?eventid={$row['event']}&&dsessionid={$row['idsession']}'>Delete</a></td>
                            <td><a href='admin.php?eventid={$row['event']}&&esessionid={$row['idsession']}'>Edit</a></td>
                            <td>{$row['idsession']}</td>
                            <td>{$row['name']}</td>
                            <td>{$row['numberallowed']}</td>
                            <td>{$row['startdate']}</td>
                            <td>{$row['enddate']}</td></tr>\n";

            }
            $bigString .="</table></div>\n";
        } else {
            $bigString = "Error"; 
        }
        return $bigString;
    }//getSessionofMAsTable
    function getAttendeeofM($name){
         try{
            $dadatate = array();
            $stmt = $this->dbh->prepare("select ae.event as eventid, a.idattendee as id, 
            a.name as name, 
            ats.session as sessionid 
            from attendee as a 
            join attendee_session as ats 
            on a.idattendee = ats.attendee
            join attendee_event as ae
            on a.idattendee = ae.attendee
            where ae.event in 
            (select event from
            manager_event as me
            join attendee as a
            on me.manager = a.idattendee
            where a.name = :name)");
            $stmt->bindParam(":name",$name,PDO::PARAM_STR);
            $stmt->execute();
            $data = $stmt->fetchAll();
            return $data;
        
        } catch (PDOException $e) {
            echo $e->getMessage();
            die();
        }
    }//getAttendeeofM
    function getAttendeeofMAsTable($name) {
        $data = $this->getAttendeeofM($name);
        
        if (count($data) > 0) {
            $bigString = "<div class='tableAdmin'><table id='alladmin'>\n";
            $bigString .= "<tr><td colspan='6' align='center'>
                        <form action = '/~yj3010/ISTE-341/project1/admin.php' method='POST'>
                        <input type='submit' name='addAttendeeB' value='Add Attendee' />
                        </form></td></tr>";
            $bigString .= "<tr><th>Delete</th><th>Edit</th>
                            <th>Event ID</th><th>Session ID</th><th>Attendee ID</th><th>Name</th></tr>\n";
            foreach ($data as $row) {
                $bigString .="<tr>
                <td><a href='admin.php?eventid={$row['eventid']}&&sessionid={$row['sessionid']}&&dattendid={$row['id']}'>Delete</a></td>
                <td><a href='admin.php?&&eattendid={$row['id']}'>Edit</a></td>
                <td>{$row['eventid']}</td>
                <td>{$row['sessionid']}</td>
                <td>{$row['id']}</td>
                <td>{$row['name']}</td></tr>\n";

            }
            $bigString .="</table></div>\n";
        } else {
            $bigString = "Error"; 
        }
        return $bigString;
    }//getAttendeeofMAsTable
//---Delete an event
    function deleteEventWithId($id){
        try{
            $stmt = $this->dbh->prepare("delete from event where idevent = :idevent;");
            $stmt->execute(array("idevent"=>$id));
            $deleted = $stmt->rowCount();
            return $deleted;
        } catch (PDOException $e) {
            echo $e->getMessage();
            die();
        }
    }//deleteEvent
    function deleteEventSession($id){
        try{
            $stmt = $this->dbh->prepare("delete from session where event = :event;");
            $stmt->execute(array("event"=>$id));
            $deleted = $stmt->rowCount();
            return $deleted;
        } catch (PDOException $e) {
            echo $e->getMessage();
            die();
        }
    }//deleteEvent
    function deleteEventAttend($id){
        try{
            $stmt = $this->dbh->prepare("delete from attendee_event where event = :event;");
            $stmt->execute(array("event"=>$id));
            $deleted = $stmt->rowCount();
            return $deleted;
        } catch (PDOException $e) {
            echo $e->getMessage();
            die();
        }
    }//deleteEventAttend
    function deleteEventManager($id){
        try{
            $stmt = $this->dbh->prepare("delete from manager_event where event = :event;");
            $stmt->execute(array("event"=>$id));
            $deleted = $stmt->rowCount();
            return $deleted;
        } catch (PDOException $e) {
            echo $e->getMessage();
            die();
        }
    }//deleteEventManager
    function deleteEventUpdate($id){
        try{
            $stmt = $this->dbh->prepare("update attendee set role='3' where idattendee in (select manager from manager_event where event = :id);");
            $stmt->execute(array("id"=>$id));
            $deleted = $stmt->rowCount();
            return $deleted;
        } catch (PDOException $e) {
            echo $e->getMessage();
            die();
        }
    }//deleteEventUpdate
//---Edit an event
    function editEvent($id,$name,$startd,$endd,$num,$venue){
        try{
            $data = array();
            $stmt = $this->dbh->prepare("update event set name = :name, datestart = :startd, dateend = :endd, numberallowed = :num, venue = :venue where idevent = :id;");
            $stmt->execute(array("name"=>$name,"startd"=>$startd,"endd"=>$endd,"num"=>$num,"venue"=>$venue,"id"=>$id));
            $updated = $stmt->rowCount();
            return $updated;
        } catch (PDOException $e) {
            echo $e->getMessage();
            die();
        }
    }//editEvent
//---Add an event
    function addEvent($name, $startd,$endd,$num,$venue) {
        try{
            $stmt = $this->dbh->prepare("insert into event (name, datestart, dateend, numberallowed, venue) 
            values (:name, :startd, :endd, :num, :venue);");
            $stmt->execute(array("name"=>$name, "startd"=>$startd, "endd"=>$endd, "num"=>$num, "venue"=>$venue));
            return $this->dbh->lastInsertId();
        } catch (PDOException $e) {
            echo $e->getMessage();
            die();
        }
    }//addEvent
    function addEventManager($name,$eventname) {
        try{
            $stmt = $this->dbh->prepare("insert into manager_event (event,manager)
            select e.idevent, a.idattendee 
            from event as e 
            join attendee as a
            where a.name = :name and e.name = :eventname;");
            $stmt->execute(array("name"=>$name, "eventname"=>$eventname));
            return $this->dbh->lastInsertId();
        } catch (PDOException $e) {
            echo $e->getMessage();
            die();
        }
    }//addEvent
//---Delete a session
    function deleteSessionWithId($id){
        try{
            $stmt = $this->dbh->prepare("delete from session where idsession = :idsession;");
            $stmt->execute(array("idsession"=>$id));
            $deleted = $stmt->rowCount();
            return $deleted;
        } catch (PDOException $e) {
            echo $e->getMessage();
            die();
        }
    }//deleteSessionWithId
    function deleteSessionAttend($id){
        try{
            $stmt = $this->dbh->prepare("delete from attendee_session where session = :session;");
            $stmt->execute(array("session"=>$id));
            $deleted = $stmt->rowCount();
            return $deleted;
        } catch (PDOException $e) {
            echo $e->getMessage();
            die();
        }
    }//deleteSessionAttend
//---Edit a session
    function editSession($id,$name,$startd,$endd,$num){
        try{
            $data = array();
            $stmt = $this->dbh->prepare("update session set name = :name, startdate = :startd, enddate = :endd, numberallowed = :num where idsession = :id;");
            $stmt->execute(array("name"=>$name,"startd"=>$startd,"endd"=>$endd,"num"=>$num,"id"=>$id));
            $updated = $stmt->rowCount();
            return $updated;
        } catch (PDOException $e) {
            echo $e->getMessage();
            die();
        }
    }//editSession
//---Add a session
    function addSession($name, $startd,$endd,$num,$eventid) {
        try{
            $stmt = $this->dbh->prepare("insert into session (name, startdate, enddate, numberallowed, event) 
            values (:name, :startd, :endd, :num, :eventid) ;");
            $stmt->execute(array("name"=>$name, "startd"=>$startd, "endd"=>$endd, "num"=>$num, "eventid"=>$eventid));
            return $this->dbh->lastInsertId();
        } catch (PDOException $e) {
            echo $e->getMessage();
            die();
        }
    }//addSession
//---Delete an attendee of the event and the session
    function deleteAttendeeSession($id,$sessionid){
        try{
            $stmt = $this->dbh->prepare("delete from attendee_session where attendee = :attendee and session=:sessionid;");
            $stmt->execute(array("attendee"=>$id,"sessionid"=>$sessionid));
            $deleted = $stmt->rowCount();
            return $deleted;
        } catch (PDOException $e) {
            echo $e->getMessage();
            die();
        }
    }//deleteAttendeeEvent
    function checkifAttendeeinEvent($id,$eventid){
        try{
            $stmt = $this->dbh->prepare("select attendee,session from attendee_session
                    where attendee = :attendee and session in (
                    select idsession from session 
                    where event = :eventid);");
            $stmt->execute(array("attendee"=>$id,"eventid"=>$eventid));
            $data = $stmt->fetchAll();
            return $data;
        } catch (PDOException $e) {
            echo $e->getMessage();
            die();
        }
    }//checkifAttendeeinEvent
    function deleteAttendeeEvent($id,$eventid){
        try{
            $stmt = $this->dbh->prepare("delete from attendee_event where attendee = :attendee and event = :eventid;");
            $stmt->execute(array("attendee"=>$id,"eventid"=>$eventid));
            $deleted = $stmt->rowCount();
            return $deleted;
        } catch (PDOException $e) {
            echo $e->getMessage();
            die();
        }
    }//deleteAttendeeEvent

//---Edit an attendee
    function editAttendeeEvent($eventid,$id){
        try{
            $data = array();
            $stmt = $this->dbh->prepare("update attendee_event set event = :event where attendee = :id;");
            $stmt->execute(array("event"=>$eventid,"id"=>$id));
            $updated = $stmt->rowCount();
            return $updated;
        } catch (PDOException $e) {
            echo $e->getMessage();
            die();
        }
    }//editAttendeeEvent
    function editAttendeeSession($sessionid,$id){
        try{
            $data = array();
            $stmt = $this->dbh->prepare("update attendee_session set session = :session where attendee = :id;");
            $stmt->execute(array("session"=>$sessionid,"id"=>$id));
            $updated = $stmt->rowCount();
            return $updated;
        } catch (PDOException $e) {
            echo $e->getMessage();
            die();
        }
    }//editAttendeeSession
    function editAttendeeRole($id,$role){
        try{
            $stmt = $this->dbh->prepare("update attendee set role=:role where idattendee = :id;");
            $stmt->execute(array("role"=>$role,"id"=>$id));
            $updated = $stmt->rowCount();
            return $updated;
        } catch (PDOException $e) {
            echo $e->getMessage();
            die();
        }
    }//editAttendeeRole
    function deleteManager($id){
        try{
            $stmt = $this->dbh->prepare("delete from manager where manger = :id);");
            $stmt->execute(array("id"=>$id));
            $deleted = $stmt->rowCount();
            return $deleted;
        } catch (PDOException $e) {
            echo $e->getMessage();
            die();
        }
    }//deleteManager
    function addManager($eventid,$id) {
        try{
            $stmt = $this->dbh->prepare("insert into manager (event, manager) 
            values (:eventid, :id);");
            $stmt->execute(array("eventid"=>$eventid, "id"=>$id));
            return $this->dbh->lastInsertId();
        } catch (PDOException $e) {
            echo $e->getMessage();
            die();
        }
    }//addManager
//---Add an attendee
    function addAttendeeSession($sessionid,$id) {
        try{
            $stmt = $this->dbh->prepare("insert into attendee_session (session, attendee) 
            values (:session, :attendee);");
            $stmt->execute(array("session"=>$sessionid, "attendee"=>$id));
            return $this->dbh->lastInsertId();
        } catch (PDOException $e) {
            echo $e->getMessage();
            die();
        }
    }//addAttendeeSession
    function checkifAttendeeinEventAdd($id,$eventid){
        try{
            $stmt = $this->dbh->prepare("select attendee,event from attendee_event
                    where attendee = :attendee and event in (
                    select event from session 
                    where event = :eventid);");
            $stmt->execute(array("attendee"=>$id,"eventid"=>$eventid));
            $data = $stmt->fetchAll();
            return $data;
        } catch (PDOException $e) {
            echo $e->getMessage();
            die();
        }
    }//checkifAttendeeinEventAdd
    function addAttendeeEvent($eventid,$id) {
        try{
            $stmt = $this->dbh->prepare("insert into attendee_event (event, attendee) 
            values (:event, :attendee);");
            $stmt->execute(array("event"=>$eventid, "attendee"=>$id));
            return $this->dbh->lastInsertId();
        } catch (PDOException $e) {
            echo $e->getMessage();
            die();
        }
    }//addSession

//AS ADMIN
//---users
    function getAllUsers(){
        try{
            $data = array();
            $stmt = $this->dbh->prepare("select idattendee, name, role from attendee order by idattendee");
            $stmt->execute();
            while ($row = $stmt->fetch()){
                $data[] = $row;
            }
            return $data;
        
        } catch (PDOException $e) {
            echo $e->getMessage();
            die();
        }
    }//getAllUsers
    function getAllUsersAsTable() {
        $data = $this->getAllUsers();
        if (count($data) > 0) {
            $bigString = "<div class='tableAdmin'><table id='alladmin'>\n";
            $bigString .= "<tr><td colspan='5' align='center'>
                        <form action = '/~yj3010/ISTE-341/project1/admin.php' method='POST'>
                        <input type='submit' name='addUserB' value='Add' />
                        </form></td></tr>";
            $bigString .= "<tr><th>Delete</th><th>Edit</th><th>User ID</th><th>User Name</th><th>User Role</th></tr>\n";
            foreach ($data as $row) {
                $bigString .="<tr><td><a href='admin.php?did={$row['idattendee']}'>Delete</a></td>
                                  <td><a href='admin.php?eid={$row['idattendee']}'>Edit</a></td>
                                  <td>{$row['idattendee']}</td>
                                  <td>{$row['name']}</td>
                                  <td>{$row['role']}</td></tr>\n";
            }
            $bigString .="</table></div>\n";
        } else {
            $bigString = "<h2>No event exists</h2>"; 
        }
        return $bigString;
    }//getAllUsersAsTable
    function checkUser($id){
        try{
            $data = array();
            $stmt = $this->dbh->prepare("select idattendee, name, role from attendee where idattendee = :id");
            $stmt->bindParam(":id",$id,PDO::PARAM_INT);
            $stmt->execute();
            $data = $stmt->fetchAll();
            return $data;
        
        } catch (PDOException $e) {
            echo $e->getMessage();
            die();
        }
    }//checkUser
    function deleteUser($id){
        try{
            $stmt = $this->dbh->prepare("delete from attendee where idattendee = :idattendee;");
            $stmt->execute(array("idattendee"=>$id));
            $deleted = $stmt->rowCount();
            return $deleted;
        } catch (PDOException $e) {
            echo $e->getMessage();
            die();
        }
    }//deleteUser
    function editUser($id,$name,$role){
        try{
            $data = array();
            $stmt = $this->dbh->prepare("update attendee set name = :name, role = :role where idattendee = :idattendee;");
            $stmt->execute(array("name"=>$name,"role"=>$role,"idattendee"=>$id));
            $updated = $stmt->rowCount();
            return $updated;
        } catch (PDOException $e) {
            echo $e->getMessage();
            die();
        }
    }//editUser
    function addUser($name, $password,$role) {
        $pwd = hash("sha256",$password);
        try{
            $stmt = $this->dbh->prepare("insert into attendee (name, password, role) 
            values (:name, :password, :role);");
            $stmt->execute(array("name"=>$name, "password"=>$pwd, "role"=>$role));
            return $this->dbh->lastInsertId();
        } catch (PDOException $e) {
            echo $e->getMessage();
            die();
        }
    }//addUser
//---events
    function getAllEvent(){
        try{
           $data = array();
           $stmt = $this->dbh->prepare("select e.* from event as e order by e.idevent;");
           $stmt->execute();
           $data = $stmt->fetchAll();
           return $data;
       } catch (PDOException $e) {
           echo $e->getMessage();
           die();
       }
   }//getAllEvent
   function getAllEventAsTable() {
        $data = $this->getAllEvent();
        if (count($data) > 0) {
            $bigString = "<div class='tableAdmin'><table id='alladmin'>\n";
            $bigString .= "<tr><td colspan='8' align='center'><form method='POST'><input type='submit' name='addEventB' value='Add Event' /></form></td></tr>";
            $bigString .= "<tr><th>Delete</th><th>Edit</th><th>Event ID</th><th>Event Name</th><th>Start Date&Time</th><th>End Date&Time</th><th>Number allowed</th><th>Venue</th></tr>\n";
            foreach ($data as $row) {
                $bigString .="<tr><td><a href='admin.php?dEid={$row['idevent']}'>Delete</a></td>
                              <td><a href='admin.php?eEid={$row['idevent']}'>Edit</a></td>
                              <td>{$row['idevent']}</td>
                              <td>{$row['name']}</td>
                              <td>{$row['datestart']}</td>
                              <td>{$row['dateend']}</td>
                              <td>{$row['numberallowed']}</td>
                              <td>{$row['venue']}</td></tr>\n";
            }
            $bigString .="</table></div>\n";
        } else {
            $bigString = "<h2>No event exists</h2>"; 
        }
        return $bigString;
    }//getAllEventAsTable
    function getAllSession(){
        try{
           $data = array();
           $stmt = $this->dbh->prepare("select s.* from session as s order by s.idsession;");
           $stmt->execute();
           $data = $stmt->fetchAll();
           return $data;
       } catch (PDOException $e) {
           echo $e->getMessage();
           die();
       }
   }//getAllSession
//---sessions
   function getAllSessionAsTable() {
        $data = $this->getAllSession();
        if (count($data) > 0) {
            $bigString = "<div class='tables'><table id='alladmin'>\n";
            $bigString .= "<tr><td colspan='8' align='center'><form method='POST'><input type='submit' name='addSessionB' value='Add Session' /></form></td></tr>";
            $bigString .= "<tr><th>Delete</th><th>Edit</th><th>Session ID</th><th>Session Name</th><th>Start Date&Time</th><th>End Date&Time</th><th>Event ID</th><th>Number allowed</th></tr>\n";
            foreach ($data as $row) {
                $bigString .="<tr><td><a href='admin.php?dSid={$row['idsession']}'>Delete</a></td>
                              <td><a href='admin.php?eSid={$row['idsession']}'>Edit</a></td>
                              <td>{$row['idsession']}</td>
                              <td>{$row['name']}</td>
                              <td>{$row['startdate']}</td>
                              <td>{$row['enddate']}</td>
                              <td>{$row['event']}</td>
                              <td>{$row['numberallowed']}</td>
                              </tr>\n";
            }
            $bigString .="</table></div>\n";
        } else {
            $bigString = "<h2>No event exists</h2>"; 
        }
        return $bigString;
    }//getAllSessionAsTable
    function getAllAttendee(){
        try{
           $data = array();
           $stmt = $this->dbh->prepare("select ae.attendee as attendee, 
                                        a.role as role,
                                        ae.event as event,
                                        ats.session as session 
                                        from attendee_event as ae 
                                        join attendee_session ats 
                                        on ae.attendee = ats.attendee 
                                        join attendee as a
                                        on ae.attendee = a.idattendee
                                        order by ae.attendee;");
           $stmt->execute();
           $data = $stmt->fetchAll();
           return $data;
       } catch (PDOException $e) {
           echo $e->getMessage();
           die();
       }
   }//getAllAttendee
//---attendee
   function getAllAttendeeAsTable() {
        $data = $this->getAllAttendee();
        if (count($data) > 0) {
            $bigString = "<div class='tables'><table id='alladmin'>\n";
            $bigString .= "<tr><td colspan='6' align='center'><form method='POST'><input type='submit' name='addAttendeeB' value='Add Attendee' /></form></td></tr>";
            $bigString .= "<tr><th>Delete</th><th>Edit</th><th>Attendee ID</th><th>Role</th><th>Event ID</th><th>Session ID</th></tr>\n";
            foreach ($data as $row) {
                $bigString .="<tr><td><a href='admin.php?eventid={$row['event']}&&sessionid={$row['session']}&&dAid={$row['attendee']}'>Delete</a></td>
                              <td><a href='admin.php?eAid={$row['attendee']}'>Edit</a></td>
                              <td>{$row['attendee']}</td>
                              <td>{$row['role']}</td>
                              <td>{$row['event']}</td>
                              <td>{$row['session']}</td></tr>\n";
            }
            $bigString .="</table></div>\n";
        } else {
            $bigString = "<h2>No event exists</h2>"; 
        }
        return $bigString;
    }//getAllAttendeeAsTable
//---venue
    function getAllVenue(){
        try{
           $data = array();
           $stmt = $this->dbh->prepare("select v.* from venue as v;");
           $stmt->execute();
           $data = $stmt->fetchAll();
           return $data;
       } catch (PDOException $e) {
           echo $e->getMessage();
           die();
       }
   }//getAllVenue
   function getAllVenueAsTable() {
        $data = $this->getAllVenue();
        if (count($data) > 0) {
            $bigString = "<div class='tables'><table id='alladmin'>\n";
            $bigString .= "<tr><td colspan='5' align='center'><form method='POST'><input type='submit' name='addVenueB' value='Add Venue' /></form></td></tr>";
            $bigString .= "<tr><th>Delete</th><th>Edit</th><th>Venue ID</th><th>Venue Name</th><th>Capacity</th></tr>\n";
            foreach ($data as $row) {
                $bigString .="<tr><td><a href='admin.php?dVid={$row['idvenue']}'>Delete</a></td>
                              <td><a href='admin.php?eVid={$row['idvenue']}'>Edit</a></td>
                              <td>{$row['idvenue']}</td>
                              <td>{$row['name']}</td>
                              <td>{$row['capacity']}</td></tr>\n";
            }
            $bigString .="</table></div>\n";
        } else {
            $bigString = "<h2>No event exists</h2>"; 
        }
        return $bigString;
    }//getAllVenueAsTable
//---Delete a venue
    function deleteVenue($id){
        try{
            $stmt = $this->dbh->prepare("delete from venue where idvenue = :idvenue;");
            $stmt->execute(array("idvenue"=>$id));
            $deleted = $stmt->rowCount();
            return $deleted;
        } catch (PDOException $e) {
            echo $e->getMessage();
            die();
        }
    }//deleteVenue
//---Edit a venue
    function editVenue($id,$name,$capacity){
        try{
            $data = array();
            $stmt = $this->dbh->prepare("update venue set name = :name, capacity = :capacity where idvenue = :id;");
            $stmt->execute(array("name"=>$name,"capacity"=>$capacity,"id"=>$id));
            $updated = $stmt->rowCount();
            return $updated;
        } catch (PDOException $e) {
            echo $e->getMessage();
            die();
        }
    }//editVenue
//---Add a venue
    function addVenue($name, $capacity) {
        try{
            $stmt = $this->dbh->prepare("insert into venue (name, capacity) 
            values (:name, :capacity);");
            $stmt->execute(array("name"=>$name, "capacity"=>$capacity));
            return $this->dbh->lastInsertId();
        } catch (PDOException $e) {
            echo $e->getMessage();
            die();
        }
    }//addVenue

/*Register Page*/
    function getEventsAsTableWA() {
        $data = $this->getEvents();
        if (count($data) > 0) {
            $bigString = "<div class='tables'><table id='events'>\n
                            <tr><th>Add</th><th>ID</th><th>Name</th><th>Start Date&Time</th><th>Venue</th>
                            <th>Session#</th>
                            <th>Session Id</th>
                            <th>Session Start Time</th>
                            <th>Session End Time</th></tr>\n";
            foreach ($data as $row) {
                $bigString .="<tr><td><a href='register.php?aidsession={$row['idsession']}&aidevent={$row['idevent']}'>Add</a></td>
                                <td>{$row['idevent']}</td>
                                  <td>{$row['ename']}</td>
                                  <td>{$row['estart']}</td>
                                  <td>{$row['vname']}</td>
                                  <td>{$row['snum']}</td>
                                  <td>{$row['idsession']}</td>
                                  <td>{$row['sstartdate']}</td>
                                  <td>{$row['senddate']}</td></tr>\n";
            }
            $bigString .="</table></div>\n";
        } else {
            $bigString = "<h2>No event exists</h2>"; 
        }
        return $bigString;
    }//getEventsAsTableWA
    
    function getREvents($name){
        try{
            $data = array();
            $stmt = $this->dbh->prepare("select e.idevent as idevent, 
            e.name as ename, 
            e.datestart as estart, 
            v.name as vname, 
            s.numberallowed as snum, 
            s.idsession as idsession, 
            s.startdate as sstartdate, 
            s.enddate as senddate 
            from session as s
            left join event as e 
            on s.event = e.idevent  
            left join attendee_session as ats
            on  s.idsession = ats.session
            left join venue v 
            on e.venue = v.idvenue 
            where ats.attendee 
            in (select idattendee FROM attendee a WHERE a.name = :name ) order by e.idevent, s.idsession");
            $stmt->bindParam(":name",$name,PDO::PARAM_STR);
            $stmt->execute();
            while ($row = $stmt->fetch()){
                $data[] = $row;
            }
            return $data;
        
        } catch (PDOException $e) {
            echo $e->getMessage();
            die();
        }
    }//getREvents
    function getREventsAsTable($name) {
        $data = $this->getREvents($name);
        if (count($data) > 0) {
            $bigString = "<div class='tables'><table id='events'>\n
                            <tr><th>Delete</th><th>ID</th><th>Name</th><th>Start Date&Time</th><th>Venue</th>
                            <th>Session#</th>
                            <th>Session Id</th>
                            <th>Session Start Time</th>
                            <th>Session End Time</th></tr>\n";
            foreach ($data as $row) {
                $bigString .="<tr><td><a href='register.php?didsession={$row['idsession']}&didevent={$row['idevent']}'>Delete</a></td>
                                  <td>{$row['idevent']}</td>
                                  <td>{$row['ename']}</td>
                                  <td>{$row['estart']}</td>
                                  <td>{$row['vname']}</td>
                                  <td>{$row['snum']}</td>
                                  <td>{$row['idsession']}</td>
                                  <td>{$row['sstartdate']}</td>
                                  <td>{$row['senddate']}</td></tr>\n";
            }
            $bigString .="</table></div>\n";
        } else {
            $bigString = "<h2>No event exists</h2>"; 
        }
        return $bigString;
    }//getREventsAsTable
    
    function checkRegister($name,$idsession){
        try{
            $data = array();
            $stmt = $this->dbh->prepare("select * from attendee_session as ats join attendee as a on ats.attendee = a.idattendee where ats.session = :idsession and a.name = :name");
            $stmt->bindParam(":idsession",$idsession,PDO::PARAM_INT);
            $stmt->bindParam(":name",$name,PDO::PARAM_STR);
            $stmt->execute();
            $data = $stmt->fetchAll();
            return $data;
        
        } catch (PDOException $e) {
            echo $e->getMessage();
            die();
        }
    }//checkRegister
    function checkRegisterE($name,$idevent){
        try{
            $data = array();
            $stmt = $this->dbh->prepare("select * from attendee_event as ae join attendee as a on ae.attendee = a.idattendee where ae.event = :idevent and a.name = :name");
            $stmt->bindParam(":idevent",$idevent,PDO::PARAM_INT);
            $stmt->bindParam(":name",$name,PDO::PARAM_STR);
            $stmt->execute();
            $data = $stmt->fetchAll();
            return $data;
        
        } catch (PDOException $e) {
            echo $e->getMessage();
            die();
        }
    }//checkRegisterE
    
    function registerSession($name,$idsession){
        try{
            $stmt = $this->dbh->prepare("insert into attendee_session (session,attendee) SELECT :idsession,idattendee FROM attendee a WHERE a.name = :name;");
            $stmt->execute(array("idsession"=>$idsession, "name"=>$name));
            return $this->dbh->lastInsertId();
        } catch (PDOException $e) {
            echo $e->getMessage();
            die();
        }
    }//registerSession
    function registerEvent($name,$idevent){
        try{
            $stmt = $this->dbh->prepare("insert into attendee_event (event,attendee) SELECT :idevent,idattendee FROM attendee a WHERE a.name = :name;");
            $stmt->execute(array("idevent"=>$idevent, "name"=>$name));
            return $this->dbh->lastInsertId();
        } catch (PDOException $e) {
            echo $e->getMessage();
            die();
        }
    }//registerEvent
    function deleteSession($name,$idsession){
        try{
            $stmt = $this->dbh->prepare("delete from attendee_session where session = :idsession and attendee in (select idattendee from attendee where name = :name);");
            $stmt->execute(array("idsession"=>$idsession, "name"=>$name));
            $deleted = $stmt->rowCount();
            return $deleted;
        } catch (PDOException $e) {
            echo $e->getMessage();
            die();
        }
    }//deleteSession
    function deleteEvent($name,$idevent){
        try{
            $stmt = $this->dbh->prepare("delete from attendee_event where event = :idevent and attendee in (select idattendee from attendee where name = :name);");
            $stmt->execute(array("idevent"=>$idevent, "name"=>$name));
            $deleted = $stmt->rowCount();
            return $deleted;
        } catch (PDOException $e) {
            echo $e->getMessage();
            die();
        }
    }//deleteEvent

}//db

?>