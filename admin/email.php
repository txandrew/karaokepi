<?php
session_start();
error_reporting( E_ALL );
ini_set('display_errors', 1);

include "../db_init.php";

if ( !isset($_SESSION["admin"]) )
{
    Header ("Location: ..");
}
elseif ( isset( $_GET["userid"] ) )
{
    $qry_email = $conn->query("SELECT email FROM tbl_users WHERE user_id = '" . $_GET["userid"] . "';");
    $row = $qry_email->fetch_assoc();

    $str_wireless = exec("iwgetid -r");

    $str_self = exec("ip addr | grep -oE 'inet [0-9]+\.[0-9]+\.[0-9]+\.[0-9]+'");
    $str_self = str_replace("127.0.0.1","",$str_self);
    $str_self = str_replace("inet ","",$str_self);

    $headers = array("From: noreply@txandrew.ddns.net", "Reply-To: anderson.andrew.r@gmail.com", "X-Mailer: PHP/" . PHP_VERSION);

    $status = mail( $row["email"],"Let's Karaoke!",
        "To access the Karaoke-Pi system, follow these steps.\n\n" .
        "1.) Log into the following wireless router:\n" .
        $str_wireless . "\n\n" .
        "2.) Click on the following link:\n" .
        "http://" . $str_self, $headers );
    $_SESSION["message"] = $row["email"];
    Header("Location: email.php");
}
?>

<html>
  <head>
    <title>Admin Functions</title>
    <link rel="stylesheet" type="text/css" href="../karaoke.css">
  </head>
  <body>
    <h1>Send Email<h1>
<?php
$qry_users = $conn->query("select user_id from tbl_users where active=1");
while ( $row = $qry_users->fetch_assoc() )
{
    echo "<button onclick='location.replace(\"email.php?userid=" . $row["user_id"] . "\")'>" . $row["user_id"] . "</button><br /><br />\n";
}
?>
      <button onclick="location.replace('index.php')" >Admin Menu </button><br /><br />
  </body>
</html>
