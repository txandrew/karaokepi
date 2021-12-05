<?php
include("header.php");

$str_msg = "";
if ( isset($_GET["CMD"]) )
{
    switch ($_GET["CMD"])
    {
    case "h":
        system ("sudo shutdown -h 0");
        break;
    case "r":
        system ("sudo shutdown -r 0");
        break;
    case "q":
        $conn->query("UPDATE tbl_status SET status='QUIT';");
        break;
    case "e":
        $qry_email = $conn->query("SELECT email FROM tbl_users WHERE user_id = '" . $_GET["ARGV"] . "';");
        $row = $qry_email->fetch_assoc();

        $str_wireless = exec("iwgetid -r");

        $str_self = exec("ip addr | grep -oE 'inet [0-9]+\.[0-9]+\.[0-9]+\.[0-9]+'");
        $str_self = str_replace("127.0.0.1","",$str_self);
        $str_self = str_replace("inet ","",$str_self);

        mail( $row["email"],"Let's Karaoke!",
            "To access the Karaoke-Pi system, follow these steps.\n\n" .
            "1.) Log into the following wireless router:\n" .
            $str_wireless . "\n\n" .
            "2.) Click on the following link:\n" .
            "http://" . $str_self );
        $_SESSION["message"] = $row["email"];
        break;
    }
}        
?>

<script>
function sendHTTP(str_url)
{
    xhttp = new XMLHttpRequest();
    xhttp.open("GET",str_url,true);
    xhttp.send();
}
function sendCmd(str_cmd)
{
    sendCmd(str_cmd,"");
}
function sendCmd(str_cmd,str_argv)
{
    if ( str_cmd == 'h' || str_cmd == 'r' || str_cmd == 'q' )
    {
        if ( confirm("Are you sure you want to end the program?" ) )
        {
            sendHTTP("shutdown.php?CMD=" + str_cmd + "&ARGV=" + str_argv);
        }
    }
    else
    {
        sendHTTP("shutdown.php?CMD=" + str_cmd + "&ARGV=" + str_argv);
    }
}
if ( window.location.search.length > 0 )
{
    window.close();
}
</script>

<button onclick="sendCmd('h')">Shutdown Pi</button><br /><br />
<button onclick="sendCmd('r')">Reboot Pi</button><br /><br />

<h1>Send Email Shortcuts To:</h1>
<div style="width:80%;float:right">
<?php
$qry_users = $conn->query("select user_id from tbl_users;");
while ( $row = $qry_users->fetch_assoc() )
{
    echo "<button onclick=\"sendCmd('e','" . $row["user_id"] . "')\">" . $row["user_id"] . "</button>\n";
    echo "<br /><br />";
}
echo $str_msg;
?>
</div>
</body>
</html>
