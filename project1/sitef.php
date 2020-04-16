<?php
require_once('db.php');
class sitef {
    
    var $error_message;
    var $register_message;
//Handle Messages
    function HandleError($err)
    {
        $this->error_message .= $err;
    }
    function GetErrorMessage()
    {
        if(empty($this->error_message))
        {
            return '';
        }
        $errormsg = $this->error_message;
        return $errormsg;
    }    
    function HandleRegister($str)
    {
        $this->register_message .= $str;
    }
    function GetRegisterMessage()
    {
        if(empty($this->register_message))
        {
            return '';
        }
        $msg = $this->register_message;
        return $msg;
    }  
//General
    function RedirectToURL($url)
    {
        header("Location: $url");
        exit;
    }
    function validate_alpha($value) {
    $reg = "/^[A-Za-z]+$/";
    return preg_match($reg,$value);
    }
    function validate_alphabeticNumeric($value) {
        $reg = "/^[A-Za-z0-9]+$/";
        return preg_match($reg,$value);
    }
    function validate_req($value){
        if(!isset($value)||strlen($value)<=0){
            return false;
        }
        return true;
    }
    function validate_maxlen($value,$max_len){
        if(isset($value) )
        {
            $length = strlen($value);
            if($length > $max_len)
            {
                return false;
            }
        }
        return true;
    }
    //function validate_time($value){
    //    $reg = "/^(20[1-2][0-9])-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1]) ([0-1][0-9]|2[0-4]):([0-6][0-9]):([0-6][0-9]) $/";
    //    return preg_match($reg,$value);
    //}
    function validate_date($date, $format = 'Y-m-d H:i:s'){
        $d = DateTime::createFromFormat($format, $date);
        return $d && $d->format($format) == $date;
    }
    function ValidateForm(){
        $errorMsg = false;
        $errorText = "";
        if(isset($_POST['submit'])){
            $name = isset($_POST['name']) ? trim($_POST['name']) : '';
            $password = isset($_POST['password']) ? trim($_POST['password']) : '';
            Sanitize($name);
            Sanitize($password);
            if (!validate_req($name)){
                $errorText = $errorText.'Please insert name\n';
                $errorMsg = true;
            }
            if (!validate_alphabeticNumeric($name)){
                $errorText = $errorText.'Please insert letters\n';
                $errorMsg = true;
            }
            if(!validate_maxlen($name,40)){
                $errorText = $errorText.'Maximum length of name is 40 characters\n';
                $errorMsg = true;
            }
            if (!validate_req($password)){
                $errorText = $errorText.'Please insert password\n';
                $errorMsg = true;
            }
            if(!validate_maxlen($password,40)){
                $errorText = $errorText.'Maximum length of password is 40 characters\n';
                $errorMsg = true;
            }  
        }
        return $errorMsg;

    }
    function ValidateRegistrationSubmission()
    {
        
        if($this->ValidateForm() != '')
        {
            $error = $this->ValidateForm();
            $this->HandleError($error);
            return false;
        }        
        return true;
        
    }
    function Sanitize($str,$remove_nl=true)
    {
        $str = $this->StripSlashes($str);

        if($remove_nl)
        {
            $injections = array('/(\n+)/i',
                '/(\r+)/i',
                '/(\t+)/i',
                '/(%0A+)/i',
                '/(%0D+)/i',
                '/(%08+)/i',
                '/(%09+)/i'
                );
            $str = preg_replace($injections,'',$str);
        }

        return $str;
    }    
    function StripSlashes($str)
    {
        if(get_magic_quotes_gpc())
        {
            $str = stripslashes($str);
        }
        strip_tags($str);
        return $str;
    }  
/* Login */
    function Login()
    {
        if(empty($_POST['username']))
        {
            $this->HandleError("UserName is empty!");
            return false;
        }
        
        if(empty($_POST['password']))
        {
            $this->HandleError("Password is empty!");
            return false;
        }
        
        $username = trim($_POST['username']);
        $password = trim($_POST['password']);
        $db = new DB();
        if(!isset($_SESSION)){ session_start(); }
        if(count($db->checkLogin($username,$password))<=0)
        {
            $this->HandleError("Error logging in. The username or password does not match");
            return false;
        }
        
        $_SESSION['username'] = $username;
        
        return true;
    }
    function CheckLogin()
    {
         if(!isset($_SESSION)){ session_start(); }

         if(empty($_SESSION['username']))
         {
            return false;
         }
         return true;
    }
    function LogOut()
    {
        session_start();

        $_SESSION['username']=NULL;
        
        unset($_SESSION['username']);
        session_destroy();
    }

    function RegisterUser()
    {
        if(empty($_POST['username']))
        {
            $this->HandleError("UserName is empty!");
            return false;
        }
        
        if(empty($_POST['password']))
        {
            $this->HandleError("Password is empty!");
            return false;
        }
        
        $username = trim($_POST['username']);
        $password = trim($_POST['password']);
        $formvars = array();
        
        if(!$this->ValidateRegistrationSubmission())
        {
            return false;
        }
        
        $this->CollectRegistrationSubmission($formvars);
        
        if(!$this->SaveToDatabase($formvars))
        {
            return false;
        }
        $this->HandleRegister("Successfully registered! And please login");
        return true;
        
    }
    
    function CollectRegistrationSubmission(&$formvars)
    {
        $formvars['username'] = $this->Sanitize($_POST['username']);
        $formvars['password'] = $this->Sanitize($_POST['password']);
    }
    function SaveToDatabase(&$formvars)
    {
        if(!$this->IsFieldUnique($formvars['username']))
        {
            $this->HandleError("This UserName is already used. Please try another username");
            return false;
        }
        $db = new DB();
        if(!$db->register($formvars['username'],$formvars['password']))
        {
            $this->HandleError("Inserting to Database failed!");
            return false;
        }
        return true;
    }
    function IsFieldUnique($field)
    {
        $db = new DB();
        if(count($db->getAttendeeByName($field)) > 0)
        {
            return false;
        }
        return true;
    }
/* Register Page */ 
    function registerSession($idsession,$idevent){
        $db = new DB();
        if(count($db->checkRegister($_SESSION['username'],$idsession)) > 0 and 
           count($db->checkRegisterE($_SESSION['username'],$idevent)) > 0)
        {
            $this->HandleError("You already registered for this session");
            return false;
        } 
        elseif(count($db->checkRegisterE($_SESSION['username'],$idevent)) > 0 and 
               count($db->checkRegister($_SESSION['username'],$idsession)) <= 0)
        {
            $db->registerSession($_SESSION['username'],$idsession);
            
        } 
        elseif(count($db->checkRegisterE($_SESSION['username'],$idevent)) <= 0 and 
               count($db->checkRegister($_SESSION['username'],$idsession)) > 0)
        {
            $db->registerEvent($_SESSION['username'],$idevent);
        } 
        elseif(count($db->checkRegisterE($_SESSION['username'],$idevent)) <= 0 and 
               count($db->checkRegister($_SESSION['username'],$idsession)) <= 0)
        { 
            $db->registerSession($_SESSION['username'],$idsession);
            $db->registerEvent($_SESSION['username'],$idevent);
        }
        //$this->HandleRegister("Successfully registered!");
        $this->RedirectToURL("register.php");
        return true;
    }
    function deleteSession($idsession,$idevent){
        $db = new DB();
        if( count($db->checkRegister($_SESSION['username'],$idsession)) > 0 and 
            count($db->checkRegisterE($_SESSION['username'],$idevent)) > 0)
        {
            
            $db->deleteSession($_SESSION['username'],$idsession);
            $db->deleteEvent($_SESSION['username'],$idevent);
            
        }
        elseif ( count($db->checkRegister($_SESSION['username'],$idsession)) > 0 and 
        count($db->checkRegisterE($_SESSION['username'],$idevent)) <= 0)
        {
            $db->deleteSession($_SESSION['username'],$idsession);
        
        }
        elseif ( count($db->checkRegister($_SESSION['username'],$idsession)) <= 0 and 
        count($db->checkRegisterE($_SESSION['username'],$idevent)) > 0)
        {
            $db->deleteEvent($_SESSION['username'],$idevent);
        
        }  
        //$this->HandleRegister("Successfully deleted!");
        $this->RedirectToURL("register.php");
        return true;
    }
/* Admin Page */
//--Check status admin/manager
    function checkIfManager($name){
        $db = new DB();
        if(count($db->ifManager($name)) <= 0)
        {
            return false;
        }
        return true;
    }//checkIfManager
    function checkIfAdmin($name){
        $db = new DB();
        if(count($db->ifAdmin($name)) <= 0)
        {
            return false;
        }
        return true;
    }//checkIfAdmin
//--AED users
    function deleteUser($id){
        $db = new DB();
        if(count($db->checkUser($id))<=0){
            $this->HandleError('There is no such user');
            return false;
        }
        if($db->deleteUser($id)){
            //$this->HandleRegister("Successfully Deleted!");
            $this->RedirectToURL("admin.php");
        } else {
            $this->HandleError("Unable to delete the user");
        }
        return true;
    }//deleteUser
    function editUserBox(){
        $string = "<form class='popform' method='post'>
                <label>Username: </label>
                <input type='text' name='usern' id='usern' placeholder='Username'/><br/>
                <label>Role: </label>
                <input type='text' name='role' id='role' placeholder='Role'/><br/>
                <input type='submit' name='updateUser' value='Update' /></form>";
        return $string;
    }//editUserBox
    function addUserBox(){
        $string = "<form class='popform' action='/~yj3010/ISTE-341/project1/admin.php' method='post'>
                <label>Username: </label>
                <input type='text' name='usera' id='usera' placeholder='Username'/><br/>
                <label>Password: </label>
                <input type='password' name='pwd' id='pwd' placeholder='Password'/><br/>
                <label>Role: </label>
                <input type='text' name='roleAdd' id='roleAdd' placeholder='Role'/><br/>
                <input type='submit' name='addUser' value='Add' /></form>";
        return $string;
    }//addUserBox
    function editUser($id){
        
        if(empty($_POST['usern']))
        {
            $this->HandleError("Username is empty!");
            return false;
        }
        
        if(empty($_POST['role']))
        {
            $this->HandleError("Role is empty!");
            return false;
        }
        if(isset($_POST['updateUser'])){
            $name = isset($_POST['usern']) ? trim($_POST['usern']) : '';
            $role = isset($_POST['role']) ? trim($_POST['role']) : '';
            $this->Sanitize($name);
            $this->Sanitize($role);
            if (!$this->validate_alphabeticNumeric($name)){
                $this->HandleError("User name is not alphabetic!");
                return false;
            }
            if(!$this->validate_maxlen($name,40)){
                $this->HandleError('Maximum length of user name is 40 characters');
                return false;
            }
            $db = new DB();
            if(count($db->checkUser($id))>0){
                if($db->editUser($id,$name,$role)){
                    //$this->HandleRegister("Successfully Edited!");
                    $this->RedirectToURL("admin.php");
                };
            }
        }
        
        return true;
    }//editUser
    function addUser()
    {
        if(empty($_POST['usera']))
        {
            $this->HandleError("UserName is empty!");
            return false;
        }
        
        if(empty($_POST['pwd']))
        {
            $this->HandleError("Password is empty!");
            return false;
        }
        if(empty($_POST['roleAdd']))
        {
            $this->HandleError("Role is empty!");
            return false;
        }
        
        $username = trim($_POST['usera']);
        $password = trim($_POST['pwd']);
        $role = trim($_POST['roleAdd']);
        
        if(!$this->ValidateRegistrationSubmission())
        {
            return false;
        }
        if(!$this->IsFieldUnique($username))
        {
            $this->HandleError("This UserName is already used. Please try another username");
            return false;
        }
        $db = new DB();
        if($db->addUser($username, $password,$role))
        {
            $this->RedirectToURL("admin.php");
            //$this->HandleRegister("Successfully Added!");
        } else {
            $this->HandleError("Inserting to Database failed!");
            return false;
        }
        return true;
        
    }//addUser
//--AED Events
    function deleteEvent($id){
        if(isset($id)){
            $db = new DB();
            $db->deleteEventWithId($id);
            $db->deleteEventSession($id);
            $db->deleteEventAttend($id);
            $db->deleteEventManager($id);
            $db->deleteEventUpdate($id);
            //$this->HandleRegister("Successfully Deleted!");
            $this->RedirectToURL("admin.php");
        } else {
            $this->HandleError("Unable to delete!");
        } 
        return true;
    }//deleteEvent
    function editEventBox(){
        $string = "<div class = 'popform'><form  method='post'>
                <label>Event Name: </label>
                <input type='text' name='eventname' id='eventname' placeholder='Event Name'/><br/>
                <label>Start Date and Time: </label>
                <input type='text' name='starttime' id='starttime' placeholder='Format:yyyy-mm-dd hh:mm:ss'/><br/>
                <label>End Date and Time: </label>
                <input type='text' name='endtime' id='endtime' placeholder='Format:yyyy-mm-dd hh:mm:ss'/><br/>
                <label>Number allowed: </label>
                <input type='text' name='numa' id='numa' placeholder='Number Allowed'/><br/>
                <label>Venue: </label>
                <input type='text' name='venue' id='venue' placeholder='Venue ID'/><br/>
                <input type='submit' name='editEvent' value='Edit Event' /></form></div>";
        return $string;
    }//editEventBox
    function addEventBox(){
        $string = "<div class = 'popform'><form action='/~yj3010/ISTE-341/project1/admin.php'  method='post'>
                <label>Event Name: </label>
                <input type='text' name='eventnameAdd' placeholder='Event Name'/><br/>
                <label>Start Date and Time: </label>
                <input type='text' name='starttimeAdd' placeholder='Format:yyyy-mm-dd hh:mm:ss'/><br/>
                <label>End Date and Time: </label>
                <input type='text' name='endtimeAdd' placeholder='Format:yyyy-mm-dd hh:mm:ss'/><br/>
                <label>Number allowed: </label>
                <input type='text' name='numaAdd' placeholder='Number Allowed'/><br/>
                <label>Venue: </label>
                <input type='text' name='venueAdd' placeholder='Venue ID'/><br/>
                <input type='submit' name='addEvent' value='Add Event' /></form></div>";
        return $string;
    }//addEventBox
    function editEvent($id){
        
        if(empty($_POST['eventname']))
        {
            $this->HandleError("Event name is empty!");
            return false;
        }
        
        if(empty($_POST['starttime']))
        {
            $this->HandleError("Start Time is empty!");
            return false;
        }
        if(empty($_POST['endtime']))
        {
            $this->HandleError("End Time is empty!");
            return false;
        }
        if(empty($_POST['numa']))
        {
            $this->HandleError("Number allowed is empty!");
            return false;
        }
        if(empty($_POST['venue']))
        {
            $this->HandleError("Venue is empty!");
            return false;
        }
        
        if(isset($_POST['editEvent'])){
            $eventname = isset($_POST['eventname']) ? trim($_POST['eventname']) : '';
            $starttime = isset($_POST['starttime']) ? trim($_POST['starttime']) : '';
            $endtime = isset($_POST['endtime']) ? trim($_POST['endtime']) : '';
            $numa = isset($_POST['numa']) ? trim($_POST['numa']) : '';
            $venue = isset($_POST['venue']) ? trim($_POST['venue']) : '';
            $this->Sanitize($eventname);
            $this->Sanitize($starttime);
            $this->Sanitize($endtime);
            $this->Sanitize($numa);
            $this->Sanitize($venue);
            if (!$this->validate_alphabeticNumeric($eventname)){
                $this->HandleError("Event name is not alphabetic!");
                return false;
            }
            if(!$this->validate_maxlen($eventname,40)){
                $this->HandleError('Maximum length of event name is 40 characters');
                return false;
            }
            if(!$this->validate_date($starttime)){
                $this->HandleError('Format for start time is incorrect. format:yyyy-mm-dd hh:mm:ss');
                return false;
            }
            if(!$this->validate_date($endtime)){
                $this->HandleError('Format for end time is incorrect. format:yyyy-mm-dd hh:mm:ss');
                return false;
            }
            $db = new DB();
            if($db->editEvent($id,$eventname,$starttime,$endtime,$numa,$venue)){
                $this->RedirectToURL("admin.php");
                //$this->HandleRegister("Successfully Edited!");
            } 
        }
        
        //
        //header("Location: ".$_SERVER('REQUEST_URI'));
        
        return true;
    }//editEvent
    function addEvent($name)
    {
        if(empty($_POST['eventnameAdd']))
        {
            $this->HandleError("Event name is empty!");
            return false;
        }
        
        if(empty($_POST['starttimeAdd']))
        {
            $this->HandleError("Start Time is empty!");
            return false;
        }
        if(empty($_POST['endtimeAdd']))
        {
            $this->HandleError("End Time is empty!");
            return false;
        }
        if(empty($_POST['numaAdd']))
        {
            $this->HandleError("Number allowed is empty!");
            return false;
        }
        if(empty($_POST['venueAdd']))
        {
            $this->HandleError("Venue is empty!");
            return false;
        }
        
        if(isset($_POST['addEvent'])){
            $eventnameAdd = isset($_POST['eventnameAdd']) ? trim($_POST['eventnameAdd']) : '';
            $starttimeAdd = isset($_POST['starttimeAdd']) ? trim($_POST['starttimeAdd']) : '';
            $endtimeAdd = isset($_POST['endtimeAdd']) ? trim($_POST['endtimeAdd']) : '';
            $numaAdd = isset($_POST['numaAdd']) ? trim($_POST['numaAdd']) : '';
            $venueAdd = isset($_POST['venueAdd']) ? trim($_POST['venueAdd']) : '';
            $this->Sanitize($eventnameAdd);
            $this->Sanitize($starttimeAdd);
            $this->Sanitize($endtimeAdd);
            $this->Sanitize($numaAdd);
            $this->Sanitize($venueAdd);
            if (!$this->validate_alphabeticNumeric($eventnameAdd)){
                $this->HandleError("Event name is not alphabetic!");
                return false;
            }
            if(!$this->validate_maxlen($eventnameAdd,40)){
                $this->HandleError('Maximum length of event name is 40 characters');
                return false;
            }
            if(!$this->validate_date($starttimeAdd)){
                $this->HandleError('Format for start time is incorrect. format:yyyy-mm-dd hh:mm:ss');
                return false;
            }
            if(!$this->validate_date($endtimeAdd)){
                $this->HandleError('Format for end time is incorrect. format:yyyy-mm-dd hh:mm:ss');
                return false;
            }
            $db = new DB();
            if($db->addEvent($eventnameAdd,$starttimeAdd,$endtimeAdd,$numaAdd,$venueAdd)){
                if($this->checkIfManager($name)){
                    $db->addEventManager($name,$eventnameAdd);
                    //$this->HandleRegister("Successfully Added the event!");
                    $this->RedirectToURL("admin.php");
                } else if ($this->checkIfAdmin($name)){
                    //$this->HandleRegister("Successfully Added the event!");
                    $this->RedirectToURL("admin.php");
                }
                //header("Location: ".$_SERVER('REQUEST_URI'));
            } else {
                 $this->HandleError("Unable to add!");
            }
        }
        
        return true;
    }//addEvent
//--AED Session
    function deleteSessionA($id){
        if(isset($id)){
            $db = new DB();
            $db->deleteSessionWithId($id);
            $db->deleteSessionAttend($id);
            //$this->HandleRegister("Successfully Deleted!");
            $this->RedirectToURL("admin.php");
        } else {
            $this->HandleError("Unable to delete!");
        } 
        return true;
    }//deleteSessionA
    function editSessionBox(){
        $string = "<div class = 'popform'><form  method='post'>
                <label>Session Name: </label>
                <input type='text' name='sessionname' placeholder='Session Name'/><br/>
                <label>Number allowed: </label>
                <input type='text' name='sessionnum' placeholder='Number Allowed'/><br/>
                <label>Start Date and Time: </label>
                <input type='text' name='sessionstart' placeholder='Format:yyyy-mm-dd hh:mm:ss'/><br/>
                <label>End Date and Time: </label>
                <input type='text' name='sessionend' placeholder='Format:yyyy-mm-dd hh:mm:ss'/><br/>
                <input type='submit' name='editSession' value='Edit Session' /></form></div>";
        return $string;
    }//editEventBox
    function addSessionBox(){
        $string = "<div class = 'popform'><form method='post'>
                <label>Event ID: </label>
                <input type='number' name='eventidAdd' placeholder='Event ID'/><br/>
                <label>Session Name: </label>
                <input type='text' name='sessionnameAdd' placeholder='Session Name'/><br/>
                <label>Number allowed: </label>
                <input type='text' name='sessionnumAdd' placeholder='Number Allowed'/><br/>
                <label>Start Date and Time: </label>
                <input type='text' name='sessionstartAdd' placeholder='Format:yyyy-mm-dd hh:mm:ss'/><br/>
                <label>End Date and Time: </label>
                <input type='text' name='sessionendAdd' placeholder='Format:yyyy-mm-dd hh:mm:ss'/><br/>
                <input type='submit' name='addSession' value='Add Session' /></form></div>";
        return $string;
    }//addEventBox
    function editSession($id){
        if(empty($_POST['sessionname']))
        {
            $this->HandleError("Session name is empty!");
            return false;
        }

        if(empty($_POST['sessionnum']))
        {
            $this->HandleError("Number allowed is empty!");
            return false;
        }
        if(empty($_POST['sessionstart']))
        {
            $this->HandleError("Start Time is empty!");
            return false;
        }
        if(empty($_POST['sessionend']))
        {
            $this->HandleError("End Time is empty!");
            return false;
        }

        if(isset($_POST['editSession'])){
            $sessionname = isset($_POST['sessionname']) ? trim($_POST['sessionname']) : '';
            $sessionnum = isset($_POST['sessionnum']) ? trim($_POST['sessionnum']) : '';
            $sessionstart = isset($_POST['sessionstart']) ? trim($_POST['sessionstart']) : '';
            $sessionend = isset($_POST['sessionend']) ? trim($_POST['sessionend']) : '';
            $this->Sanitize($sessionname);
            $this->Sanitize($sessionnum);
            $this->Sanitize($sessionstart);
            $this->Sanitize($sessionend);
            if (!$this->validate_req($sessionname)){
                $this->HandleError("Session name is required!");
                return false;
            }
            if(!$this->validate_alphabeticNumeric($sessionname)){
                $this->HandleError("Session name cannot be other than letters and numbers");
                return false;
            }
            if(!$this->validate_maxlen($sessionname,40)){
                $this->HandleError('Maximum length of session name is 40 characters');
                return false;
            }
            if(!$this->validate_date($sessionstart)){
                $this->HandleError('Format for start time is incorrect. format:yyyy-mm-dd hh:mm:ss');
                return false;
            }
            if(!$this->validate_date($sessionend)){
                $this->HandleError('Format for end time is incorrect. format:yyyy-mm-dd hh:mm:ss');
                return false;
            }
            $db = new DB();
            if($db->editSession($id,$sessionname,$sessionstart,$sessionend,$sessionnum)){
                //$this->HandleRegister("Successfully Edited!");
                $this->RedirectToURL("admin.php");
                
            } else {
                $this->HandleError("Unable to edit!");
            }
        }
        
        return true;
    }//editSession
    function addSession(){
        if(empty($_POST['eventidAdd']))
        {
            $this->HandleError("Event ID is empty!");
            return false;
        }
        if(empty($_POST['sessionnameAdd']))
        {
            $this->HandleError("Session name is empty!");
            return false;
        }

        if(empty($_POST['sessionnumAdd']))
        {
            $this->HandleError("Number allowed is empty!");
            return false;
        }
        if(empty($_POST['sessionstartAdd']))
        {
            $this->HandleError("Start Time is empty!");
            return false;
        }
        if(empty($_POST['sessionendAdd']))
        {
            $this->HandleError("End Time is empty!");
            return false;
        }

        if(isset($_POST['addSession'])){
            $eventid = isset($_POST['eventidAdd']) ? trim($_POST['eventidAdd']) : '';
            $sessionname = isset($_POST['sessionnameAdd']) ? trim($_POST['sessionnameAdd']) : '';
            $sessionnum = isset($_POST['sessionnumAdd']) ? trim($_POST['sessionnumAdd']) : '';
            $sessionstart = isset($_POST['sessionstartAdd']) ? trim($_POST['sessionstartAdd']) : '';
            $sessionend = isset($_POST['sessionendAdd']) ? trim($_POST['sessionendAdd']) : '';
            $this->Sanitize($eventid);
            $this->Sanitize($sessionname);
            $this->Sanitize($sessionnum);
            $this->Sanitize($sessionstart);
            $this->Sanitize($sessionend);
            if (!$this->validate_alphabeticNumeric($sessionname)){
                $this->HandleError("Session name is not alphabetic!");
                return false;
            }
            if(!$this->validate_maxlen($sessionname,40)){
                $this->HandleError('Maximum length of session name is 40 characters');
                return false;
            }
            if(!$this->validate_date($sessionstart)){
                $this->HandleError('Format for start time is incorrect. format:yyyy-mm-dd hh:mm:ss');
                return false;
            }
            if(!$this->validate_date($sessionend)){
                $this->HandleError('Format for end time is incorrect. format:yyyy-mm-dd hh:mm:ss');
                return false;
            }
        }
        $db = new DB();
        if($db->addSession($sessionname, $sessionstart,$sessionend,$sessionnum,$eventid)){
            
            //$this->HandleRegister("Successfully Edited!");
            $this->RedirectToURL("admin.php");   
        } else {
            $this->HandleError("Unable to add!");
        }
        return true;
    }//addSession
//AED attendee
    function deleteAttendee($id,$eventid,$sessionid){
        if(isset($id)){
            $db = new DB();
            if($db->deleteAttendeeSession($id,$sessionid)){
                if(count($db->checkifAttendeeinEvent($id,$eventid))<=0){
                    $db->deleteAttendeeEvent($id,$eventid);
                }
                //$this->HandleRegister("Successfully Deleted!");
                $this->RedirectToURL("admin.php");
            } else {
                $this->HandleError("Unable to delete!");
            }
        } 
        return true;
    }//deleteAttendee
    function editAttendeeBoxM(){
        $db = new DB();
        $eventdata = $db->getEventofM($_SESSION['username']);
        $sessiondata = $db->getSessionofM($_SESSION['username']);
        $bigString = "";
        if (count($eventdata)>0 and count($sessiondata)>0){
            $bigString .="<div class = 'popform'><form method='post'><label>Event ID: </label><select name = 'selectEvent'>";
            foreach($eventdata as $ed){
                $bigString .= "<option value='{$ed['idevent']}'>{$ed['idevent']}</option>";
            }
            $bigString .="</select><br/><label>Session ID: </label><select name='selectSession'>";
            foreach($sessiondata as $sd){
                $bigString .= "<option value='{$sd['idsession']}'>{$sd['idsession']}</option>";
            }
            $bigString .= "</select><br/><input type='submit' name='editAttendee' value='Edit' /></form></div>";
        } 
        return $bigString;
    }
    function editAttendeeM($eventid,$sessionid,$id){
        //getAttendeeofMAsTable
        $db = new DB();
        if($_POST['editAttendee']){
            $db->editAttendeeEvent($eventid,$id);
            $db->editAttendeeSession($sessionid,$id);
            //$this->HandleRegister("Successfully Edited!");
            $this->RedirectToURL("admin.php");
            
        } else {
            $this->HandleError("Unable to edit!");
        }
        
        return true;
    }//editAttendee
    function editAttendeeBox(){
        $string = "<div class = 'popform'><form method='post'>
                <label>Role: </label>
                <input type='number' name='role' placeholder='1-admin,2-manager,3-attendee'/><br/>
                <label>Event ID: </label>
                <input type='number' name='eventid' placeholder='Event ID'/><br/>
                <label>Session ID: </label>
                <input type='number' name='sessionid' placeholder='Session ID'/><br/>
                <input type='submit' name='editAttendee' value='Edit Attendee' /></form></div>";
        return $string;
    }//addAttendeeBox
    function editAttendee($name,$id){
        if(empty($_POST['role']))
        {
            $this->HandleError("Role is empty!");
            return false;
        }
        if(empty($_POST['eventid']))
        {
            $this->HandleError("Event ID is empty!");
            return false;
        }
        if(empty($_POST['sessionid']))
        {
            $this->HandleError("Session ID is empty!");
            return false;
        }

        if(isset($_POST['editAttendee'])){
            $role = isset($_POST['role']) ? trim($_POST['role']) : '';
            $eventid = isset($_POST['eventid']) ? trim($_POST['eventid']) : '';
            $sessionid = isset($_POST['sessionid']) ? trim($_POST['sessionid']) : '';
            $this->Sanitize($role);
            $this->Sanitize($eventid);
            $this->Sanitize($sessionid);
            if (!$this->validate_req($role)){
                $this->HandleError("Role is required!");
                return false;
            }
            if (!$this->validate_req($eventid)){
                $this->HandleError("Event ID is required!");
                return false;
            }
            if(!$this->validate_req($sessionid)){
                $this->HandleError('Session ID is required');
                return false;
            }
        }
        $db = new DB();
        if($db){
            $db->editAttendeeEvent($eventid,$id);
            $db->editAttendeeSession($sessionid,$id);
            if($role = '3' && $this->checkIfManager($name)){
                $db->editAttendeeRole($id,$role);
                $db->deleteManager($id);
            } else if($role = '2' && !$this->checkIfManager($name)) {
                $db->editAttendeeRole($id,$role);
                $db->addManager($eventid,$id);
            }            
            //$this->HandleRegister("Successfully Edited!");
            $this->RedirectToURL("admin.php");   
        } else {
            $this->HandleError("Unable to edit!");
        }
        return true;
    }//addAttendee 
    function addAttendeeBox(){
        $string = "<div class = 'popform'><form method='post'>
                <label>Event ID: </label>
                <input type='number' name='eventidA' placeholder='Event ID'/><br/>
                <label>Session ID: </label>
                <input type='number' name='sessionidAdd' placeholder='Session ID'/><br/>
                <label>Attendee ID: </label>
                <input type='number' name='attendeeidAdd' placeholder='Attendee ID'/><br/>
                <label>Role: </label>
                <input type='number' name='roleAdd' placeholder='1-admin,2-manager,3-attendee'/><br/>
                <input type='submit' name='addAttendee' value='Add Attendee' /></form></div>";
        return $string;
    }//addAttendeeBox
    function addAttendee(){
        if(empty($_POST['roleAdd']))
        {
            $this->HandleError("Role is empty!");
            return false;
        }
        if(empty($_POST['eventidA']))
        {
            $this->HandleError("Event ID is empty!");
            return false;
        }
        if(empty($_POST['sessionidAdd']))
        {
            $this->HandleError("Session ID is empty!");
            return false;
        }

        if(empty($_POST['attendeeidAdd']))
        {
            $this->HandleError("Attendee ID is empty!");
            return false;
        }
        if(isset($_POST['addAttendee'])){
            $role = isset($_POST['roleAdd']) ? trim($_POST['roleAdd']) : '';
            $eventid = isset($_POST['eventidA']) ? trim($_POST['eventidA']) : '';
            $sessionid = isset($_POST['sessionidAdd']) ? trim($_POST['sessionidAdd']) : '';
            $attendeeid = isset($_POST['attendeeidAdd']) ? trim($_POST['attendeeidAdd']) : '';
            $this->Sanitize($eventid);
            $this->Sanitize($sessionid);
            $this->Sanitize($attendeeid);
            $this->Sanitize($role);
            if (!$this->validate_req($eventid)){
                $this->HandleError("Event ID is required!");
                return false;
            }
            if(!$this->validate_req($sessionid)){
                $this->HandleError('Session ID is required');
                return false;
            }
            if(!$this->validate_req($attendeeid)){
                $this->HandleError('Attendee ID is required');
                return false;
            }
            if(!$this->validate_req($role)){
                $this->HandleError('Attendee ID is required');
                return false;
            }
        }
        $db = new DB();
        if($db){
            $db->addAttendeeSession($sessionid, $attendeeid);
            if(count($db->checkifAttendeeinEventAdd($attendeeid,$eventid))<=0){
                $db->addAttendeeEvent($eventid,$attendeeid);
            }
            $db->editAttendeeRole($attendeeid,$role);
            //$this->HandleRegister("Successfully Added!");
            $this->RedirectToURL("admin.php");   
        } else {
            $this->HandleError("Unable to add!");
        }
        return true;
    }//addAttendee 
//AED venue
    function deleteVenue($id){
        if(isset($id)){
            $db = new DB();
            if($db->deleteVenue($id)){
                //$this->HandleRegister("Successfully Deleted!");
                $this->RedirectToURL("admin.php");
            } else {
                $this->HandleError("Unable to delete!");
            }
        } 
        return true;
    }//deleteVenue
    function editVenueBox(){
        $string = "<div class = 'popform'><form method='post'>
                <label>Venue Name: </label>
                <input type='text' name='venuename' placeholder='Venue Name'/><br/>
                <label>Capacity: </label>
                <input type='text' name='capacity' placeholder='Capacity'/><br/>
                <input type='submit' name='editVenue' value='Edit Venue' /></form></div>";
        return $string;
    }
    function editVenue($id){
        if(empty($_POST['venuename']))
        {
            $this->HandleError("Venue name is empty!");
            return false;
        }
        if(empty($_POST['capacity']))
        {
            $this->HandleError("Capacity is empty!");
            return false;
        }
        if(isset($_POST['editVenue'])){
            $venuename = isset($_POST['venuename']) ? trim($_POST['venuename']) : '';
            $capacity = isset($_POST['capacity']) ? trim($_POST['capacity']) : '';
            $this->Sanitize($venuename);
            $this->Sanitize($capacity);
            if (!$this->validate_alphabeticNumeric($venuename)){
                $this->HandleError("Venue name is invalid!");
                return false;
            }
            if(!$this->validate_req($capacity)){
                $this->HandleError('Capacity is required');
                return false;
            }
            $db = new DB();
            if($db->editVenue($id,$venuename,$capacity)){
                //$this->HandleRegister("Successfully Edited!");
                $this->RedirectToURL("admin.php");   
            } else {
                $this->HandleError("Unable to edit!");
            }
        }
        return true;
    }//editVenue
    function addVenueBox(){
        $string = "<div class = 'popform'><form method='post'>
                <label>Venue Name: </label>
                <input type='text' name='venuenameAdd' placeholder='Venue Name'/><br/>
                <label>Capacity: </label>
                <input type='text' name='capacityAdd' placeholder='Capacity'/><br/>
                <input type='submit' name='addVenue' value='Add Venue' /></form></div>";
        return $string;
    }//addVenueBox
    function addVenue(){
        if(empty($_POST['venuenameAdd']))
        {
            $this->HandleError("Venue name is empty!");
            return false;
        }
        if(empty($_POST['capacityAdd']))
        {
            $this->HandleError("Capacity is empty!");
            return false;
        }
        if(isset($_POST['addVenue'])){
            $venuename = isset($_POST['venuenameAdd']) ? trim($_POST['venuenameAdd']) : '';
            $capacity = isset($_POST['capacityAdd']) ? trim($_POST['capacityAdd']) : '';
            $this->Sanitize($venuename);
            $this->Sanitize($capacity);
            if (!$this->validate_alphabeticNumeric($venuename)){
                $this->HandleError("Venue name is invalid!");
                return false;
            }
            if(!$this->validate_req($capacity)){
                $this->HandleError('Capacity is required');
                return false;
            }
            $db = new DB();
            if($db->addVenue($venuename,$capacity)){
                //$this->HandleRegister("Successfully Added!");
                $this->RedirectToURL("admin.php");   
            } else {
                $this->HandleError("Unable to add!");
            }
        }
        return true;
    }//addVenue 
//Starter and Footer
    function starter($title){
        $nav = "<html>
                <head>
                <meta http-equiv='content-type' content='text/html; charset=utf-8' />
                <title>".$title."</title>
                <link rel='STYLESHEET' type='text/css' href='styles.css' />      
                </head>";
        $nav .= "<body><div id='header'><div id='title'><h2 id='header'>Rochester Events<h2></div>";
        $nav .= "<ul id='nav'><li><a href='events.php'>Events</a></li>";
        $nav .= "<li><a href='register.php'>Registration</a></li></ul></div>\n";
        return $nav;
    }//starter

    function starterWithLogout($title,$name){
        $nav = "<html>
                <head>
                <meta http-equiv='content-type' content='text/html; charset=utf-8' />
                <title>".$title."</title>
                <link rel='STYLESHEET' type='text/css' href='styles.css' />      
                </head>";
        $nav .= "<body><div id='header'><div id='title'><h2 id='header'>Rochester Events, Hello ".$name."<h2></div>";
        $nav .= "<ul id='nav'><li><a href='events.php'>Events</a></li>";
        $nav .= "<li><a href='register.php'>Registration</a></li>";
        $nav .= "<li><form method='post' id='logout'>
        <input type='submit' value='Log out' name='logout'>
      </form></li></ul></div>\n";
        return $nav;
    }//starter

    function footer() {
        $string = "<div id='footer'>
        <p>Copy right by MJY</p>
        <a id='footerLink' href='admin.php'>Admin</a></div>";
        $string .= "</body></html>";
        return $string;
    }//footer

    function footerForAttendee() {
        $string = "<div id='footer'>
        <p>Copy right by MJY</p></div>";
        $string .= "</body></html>";
        return $string;
    }//footerForAttendee
}
?>