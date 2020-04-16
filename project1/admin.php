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
    $nav = $sitef->starterWithLogout('Admin',$_SESSION['username']);
    echo $nav;
    $string = "<h3 id='Header'><b>Admin</b></h3>";    
    if ($sitef->checkIfAdmin($_SESSION['username'])){
        //user table 
        $string .="<h4 class='adminc'><b>Users</b></h4>";
        $string .= $db->getAllUsersAsTable();
        //delete user
        if(isset($_GET['did'])){
            $sitef->deleteUser($_GET['did']);
        }
        //edit user
        if(isset($_GET['eid'])){
            $string .= $sitef->editUserBox();
            if(isset($_POST['updateUser'])){
                $sitef->editUser($_GET['eid']);
                
            } 
        }
        //add user
        if(isset($_POST['addUserB'])){
            $string .= $sitef->addUserBox();
            
        }else if(isset($_POST['addUser'])){
                $sitef->addUser();
        }

        $string .="<hr></br><h4 class='adminc'><b>Events</b></h4>";
        $string .= $db->getAllEventAsTable();
        //delete event
        if(isset($_GET['dEid'])){
            $sitef->deleteEvent($_GET['dEid']);
        } 
        //edit event
        else if (isset($_GET['eEid'])){
            $string .= $sitef->editEventBox();
            if(isset($_POST['editEvent'])){
                $sitef->editEvent($_GET['eEid']);
            } 
        }
        //add event
        if(isset($_POST['addEventB'])){
            $string .= $sitef->addEventBox();
        } else if(isset($_POST['addEvent'])){
            $sitef->addEvent($_SESSION['username']);
        }
    
        $string .="<hr></br><h4 class='adminc'><b>Sessions</b></h4>";
        $string .= $db->getAllSessionAsTable();
        //delete session
        if(isset($_GET['dSid'])){
            $sitef->deleteSessionA($_GET['dSid']);
        } 
        //edit session
        else if (isset($_GET['eSid'])){
            $string .= $sitef->editSessionBox();
            if(isset($_POST['editSession'])){
                $sitef->editSession($_GET['eSid']);
            } 
        }
        //add session
        if(isset($_POST['addSessionB'])){
            $string .= $sitef->addSessionBox(); 
        } else if(isset($_POST['addSession'])){
                $sitef->addSession();
        }
        
        
        $string .="<hr></br><h4 class='adminc'><b>Attendee</b></h4>";
        $string .= $db->getAllAttendeeAsTable();
        //delete attendee
        if(isset($_GET['eventid']) && isset($_GET['sessionid']) && isset($_GET['dAid'])){
            $sitef->deleteAttendee($_GET['dAid'],$_GET['eventid'],$_GET['sessionid']);
        } 
        //edit attendee
        else if (isset($_GET['eAid'])){
            $string .= $sitef->editAttendeeBox();
            if(isset($_POST['editAttendee'])){
                $sitef->editAttendee($_SESSION['username'],$_GET['eAid']);
            } 
        }
        //add attendee
        if(isset($_POST['addAttendeeB'])){
            $string .= $sitef->addAttendeeBox(); 
        } else if(isset($_POST['addAttendee'])){
            $sitef->addAttendee();
        }
        
        $string .="<hr></br><h4 class='adminc'><b>Venue</b></h4>";
        $string .= $db->getAllVenueAsTable();
        //delete event
        if(isset($_GET['dVid'])){
            $sitef->deleteVenue($_GET['dVid']);
        } 
        //edit event
        else if (isset($_GET['eVid'])){
            $string .= $sitef->editVenueBox();
            if(isset($_POST['editVenue'])){
                $sitef->editVenue($_GET['eVid']);
            } 
        }
        //add event
        if(isset($_POST['addVenueB'])){
            $string .= $sitef->addVenueBox();
        } else if(isset($_POST['addVenue'])){
            $sitef->addVenue();
        }
        print_r($string);
    }
    elseif($sitef->checkIfManager($_SESSION['username'])){
        //event table
        $string .= $db->getEventsofMAsTable($_SESSION['username']);
        //delete event
        if(isset($_GET['deventid'])){
            $sitef->deleteEvent($_GET['deventid']);
        } 
        //edit event
        else if (isset($_GET['eeventid'])){
            $string .= $sitef->editEventBox();
            if(isset($_POST['editEvent'])){
                $sitef->editEvent($_GET['eeventid']);
            } 
        }
        //add event
        if(isset($_POST['addEventB'])){
            $string .= $sitef->addEventBox();
        } else if(isset($_POST['addEvent'])){
            $sitef->addEvent($_SESSION['username']);
        }
        //session table
        $string .= $db->getSessionofMAsTable($_SESSION['username']);
        //delete session
        if(isset($_GET['dsessionid'])){
            $sitef->deleteSessionA($_GET['dsessionid']);
        } 
        //edit session
        else if (isset($_GET['esessionid'])){
            $string .= $sitef->editSessionBox();
            if(isset($_POST['editSession'])){
                $sitef->editSession($_GET['esessionid']);
            } 
        }
        //add session
        if(isset($_POST['addSessionB'])){
            $string .= $sitef->addSessionBox(); 
        } else if(isset($_POST['addSession'])){
                $sitef->addSession();
        }
        //attendee table
        $string .= $db->getAttendeeofMAsTable($_SESSION['username']);
        //delete attendee
        if(isset($_GET['eventid']) && isset($_GET['sessionid']) && isset($_GET['dattendid'])){
            $sitef->deleteAttendee($_GET['dattendid'],$_GET['eventid'],$_GET['sessionid']);
        } 
        //edit attendee
        else if (isset($_GET['eattendid'])){
            $string .= $sitef->editAttendeeBoxM();
            if(isset($_POST['editAttendee'])){
                $sitef->editAttendeeM($_POST['selectEvent'],$_POST['selectSession'],$_GET['eattendid']);
            } 
        }
        //add attendee
        if(isset($_POST['addAttendeeB'])){
            $string .= $sitef->addAttendeeBox(); 
        } else if(isset($_POST['addAttendee'])){
            $sitef->addAttendee();
        }
        print_r($string);
    }
    if($sitef->GetErrorMessage() !== ''){
        echo "<script type='text/javascript'>alert('".$sitef->GetErrorMessage()."')</script>";
    }     
}
$footer = $sitef->footer();
echo $footer;

?>