<?php
session_start();
error_reporting( E_ALL );
ini_set('display_errors', 1);

$conn = new mysqli("localhost","kpi-server","karaokepi","karaoke");

if ( !isset($_SESSION["admin"]) )
{
    Header ("Location: ..");
}
elseif ( isset( $_GET["cmd"] ) )
{
    if ( $_GET["cmd"] == "shutdown" )
    {
        system ("sudo shutdown -h 0");
    }
    elseif ( $_GET["cmd"] == "reboot" )
    {
        system ("sudo shutdown -r 0");
    }
    elseif ( $_GET["cmd"] == "killpi" )
    {
        shell_exec("killall -9 karaoke-pi.py");
    }
    elseif ( $_GET["cmd"] == "killvid" )
    {
        shell_exec("killall -9 omxplayer.bin");
    }
    elseif ( $_GET["cmd"] == "quit" )
    {
        $conn->query("UPDATE tbl_status SET status='QUIT';");
    }
    elseif ( $_GET["cmd"] == "screen" )
    {
        $file = fopen("/sys/class/backlight/rpi_backlight/bl_power","w");
        $txt = "1";
        if ( $_GET["chng"] == "on" )
        {
            $txt = "0";
        }
        fwrite($file,$txt);
        fclose($file);
    }
}
?>

<html>
  <head>
    <title>Admin Functions</title>
    <link rel="stylesheet" type="text/css" href="../karaoke.css">
  </head>
  <body>
    <h1>Karaoke Admin Functions<h1>
      <button onclick="location.replace('admin.php?cmd=screen&chng=on')"  >Screen On </button><br /><br />
      <button onclick="location.replace('admin.php?cmd=screen&chng=off')" >Screen Off</button><br /><br />
      <button onclick="location.replace('admin.php?cmd=shutdown')" >Shutdown Pi</button><br /><br />
      <button onclick="location.replace('admin.php?cmd=reboot')"   >Reboot Pi  </button><br /><br />
      <button onclick="location.replace('admin.php?cmd=quit')"     >Quit K-Pi  </button><br /><br />
      <button onclick="location.replace('admin.php?cmd=killpi')"   >Kill K-Pi  </button><br /><br />
      <button onclick="location.replace('admin.php?cmd=killvid')"  >Kill Video </button><br /><br />
      <button onclick="location.replace('index.php')"              >Admin Menu </button><br /><br />
  </body>
</html>
