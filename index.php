<?php
ini_set('display_errors',1);
ini_set('display_startup_errors',1);
session_start();
error_reporting( E_ALL );

include "db_init.php";

if ( ! isset($_GET["logout"]) )
{
    if ( isset($_POST["user_id"] ))
    {
        $_SESSION["user_id"] = $_POST["user_id"];
        header("Location: playing.php");
    }
    if ( isset($_SESSION["user_id"]) )
    {
        header("Location: playing.php");
    }
}
 
?>
<html>
<head><title>Karaoke Pi</title>
<link rel="stylesheet" type="text/css" href="karaoke.css">
    <script>
function login(str_user)
{
    document.getElementById("user").value = str_user;
    document.getElementById("loginFrm").submit();
}
</script>
<body>
<h1>Login</h1>
<button onclick="location.assign('player.php')">Party Room!</button><br /><br />
<?php
if ( isset($_GET["logout"]))
{
    unset($_SESSION["user_id"]);
    echo "Logging Out<br />";
}

if ( $conn->connect_error)
{
    die("Connection failed: " . $conn->connect_error);
}

$str_sql = "SELECT user_id from tbl_users where active = 1;";
$qry_users = $conn->query($str_sql);

echo "Login As:";

echo "<form method='POST' id='loginFrm' action='index.php'><input type='hidden' id='user' name='user_id' /></form>";

while ( $row = $qry_users->fetch_assoc())
{
    echo "<button onclick=\"login('" . $row["user_id"] . "')\" >" . $row["user_id"] . "</button><br /><br />";
}
$conn->close();
?>
<button onclick="location.assign('admin')">Admin</button><br /><br />
<button onclick="location.assign('/')">Main Page</button>
</body>
</html>
