<?PHP
require_once("sitef.php");
$sitef = new sitef();
if(isset($_POST['submit']))
{
   if($sitef->Login())
   {
        $sitef->RedirectToURL("events.php");
   }
}
if(isset($_POST['register']))
{
    
   !$sitef->RegisterUser();
   
}
$nav = $sitef->starter('Login Page');
echo $nav;
?>
<body>
	<form action = "/~yj3010/ISTE-341/project1/login.php" method="POST">
        <fieldset >
            <legend>Login</legend>
            <div><span class='error'><?php echo $sitef->GetErrorMessage(); ?></span></div>
            <div><span class='register'><?php echo $sitef->GetRegisterMessage(); ?></span></div>
            <div class='container'>
                <label for='username' >Username*: </label><input type='text' name='username' id='username' placeholder="Username"/><br/>  
            </div>
            <div class='container'>
                <label for='password' >Password*: </label>
                <input type='password' name='password' id='password' maxlength="50" placeholder="Your password"/><br/> 
            </div>
            <div class='container'>
                <input type='submit' name='submit' value='Submit' />
            </div>
            <div class='container'>
            	<input type='submit' name='register' value='Register' />
            </div>
        </fieldset>
	</form>
<?PHP
$footer = $sitef->footerForAttendee();
echo $footer;
?>


