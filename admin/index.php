<html>
  <head>
    <link rel="stylesheet" type="text/css" href="../karaoke.css">
    <title>Karaoke Pi Admin</title>
  </head>
  <body>

<?php

session_start();
error_reporting( E_ALL );
ini_set('display_errors', 1);

if ( isset($_GET["logout"] ) )
{
    unset($_SESSION["admin"]);
    Header ("Location: /karaoke ");
}
if ( isset($_POST["pw"] ))
{
    if ( $_POST["pw"] == "kpi" )
    {
        $_SESSION["admin"] = 1;
    }
}
if ( !isset($_SESSION["admin"]) )
{
    echo "<h1>Admin Password</h1>";
    echo "<form method='POST' action='index.php' id='admin_login' >";
    echo "<input type='password' name='pw' /> <br /><br />";
    echo "<button type='submit' form='admin_login'>Login</button></form>";
    echo "<button onclick=\"location.assign('/karaoke')\" >Karaoke Menu</button>";
    echo "</form>";
}
else
{
    echo "<h1>Admin Menu</h1>";
    echo "<button onclick=\"location.replace('admin.php')\"     >Admin Functions</button><br /><br />";
    echo "<button onclick=\"location.replace('user.php')\"      >User  Functions</button><br /><br />";
    echo "<button onclick=\"location.replace('email.php')\"     >Email Functions</button><br /><br />";
    echo "<button onclick=\"location.replace('.?logout=true')\" >Logout</button>";
}
?>
  </body>
</html>
