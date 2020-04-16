<?php
/*
•	The application will consist of 3 pages: Events (listing of events with session schedule and venue location), Registrations (Manage registration for events and sessions) and Admin (add users, venues, events and sessions).
•	Users need to be logged in in order to use the application (this can be done as a separate page or other method). If the user isn’t logged in, you need to require them to login. Also, provide a logout option. 
•	You will use sessions to control access to the pages based on role:
*/
require_once('sitef.php');
require_once('db.php');
$sitef = new sitef();

if (!empty($_POST['logout'])){
    $sitef->LogOut();
}
$db = new DB();
if(!$sitef->CheckLogin()){
    $sitef->RedirectToURL('login.php');
} else {
    $nav = $sitef->starterWithLogout('Events',$_SESSION['username']);
    echo $nav;
    echo "<h3 id='Header'><b>Events</b></h3>";
    $errorStr = $sitef->GetErrorMessage();
    echo "<div><span class='error'>".$errorStr."</span></div>";
    $string = $db->getEventsAsTable();
    print_r($string);
}

if($sitef->checkIfManager($_SESSION['username']) or $sitef->checkIfAdmin($_SESSION['username'])){
    $footer = $sitef->footer();
    echo $footer;
} else {
    $footer = $sitef->footerForAttendee();
    echo $footer;
}

?>