<?php
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
    $nav = $sitef->starterWithLogout('Registration',$_SESSION['username']);
    echo $nav;
    $string = "<h3 id='Header'><b>Registration</b></h3>";    
    echo "<div><span class='error'>".$sitef->GetErrorMessage()."</span></div>";
    echo "<div><span class='register'>".$sitef->GetRegisterMessage()."</span></div>";
    $string .= "<div clas='subheader'><h4>Your registered event(s)</h4></div>";
    $string .= $db->getREventsAsTable($_SESSION['username']);
    $string .= "<div clas='subheader'><h4>All events</h4></div>";
    $string .= $db->getEventsAsTableWA();
    if (isset($_GET['aidsession']) and $_GET['aidevent']!=0 ){
        $sitef->registerSession($_GET['aidsession'],$_GET['aidevent']);
    }
    if (isset($_GET['didsession']) and $_GET['didevent']!=0 ){
        $sitef->deleteSession($_GET['didsession'],$_GET['didevent']);
    }
    print_r($string);
    if($sitef->GetErrorMessage() !== ''){
        echo "<script type='text/javascript'>alert('".$sitef->GetErrorMessage()."')</script>";
    } 
}

if($sitef->checkIfManager($_SESSION['username']) or $sitef->checkIfAdmin($_SESSION['username'])){
    $footer = $sitef->footer();
    echo $footer;
} else {
    $footer = $sitef->footerForAttendee();
    echo $footer;
}
?>