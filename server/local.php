<?php
    error_reporting( E_ALL );
    ini_set('display_errors', 1);
    
    $conn = new mysqli("localhost","kpi-server","karaokepi","karaoke");


    if (isset($_GET["CMD"]))
    {
        switch ($_GET["CMD"])
        {
        case "start":
            shell_exec("/var/www/html/karaoke/server/karaoke-pi.py >> /dev/null &");
            header("Location: local.php"); 
            break;


        case "stop":
            $conn->query("UPDATE tbl_status SET status='QUIT';");
            header("Location: local.php"); 
            break;


        case "auto":
            $str_sql = "select autoplay from tbl_status";
            $qry_queue_sz = $conn->query($str_sql);
            $row = $qry_queue_sz->fetch_assoc();
            $str_c_autoplay = $row["autoplay"];

            if ($str_c_autoplay == 'Y')
            {
                $conn->query("UPDATE tbl_status SET autoplay='N';");
            }
            else
            {
                $conn->query("UPDATE tbl_status SET autoplay='Y';");
            }
            header("Location: local.php");
            break;
        }
    }

$str_wireless = exec("iwgetid -r");
$str_self = exec("ip addr | grep -oE 'inet [0-9]+\.[0-9]+\.[0-9]+\.[0-9]+'");
$str_self = str_replace("127.0.0.1","",$str_self);
$str_self = str_replace("inet ","",$str_self);


$str_sql = "select count(*) queue_size from tbl_queue";
$qry_queue_sz = $conn->query($str_sql);
$row = $qry_queue_sz->fetch_assoc();
$int_queue_sz = $row["queue_size"];

$str_sql = "
    select
        c.status,
        c.youtube_id,
        c.queued_by,
        s.title,
        s.artist,
        s.genre,
        s.song_type,
        u.color,
        c.autoplay
    from tbl_status         c
    left join tbl_songs     s on c.youtube_id = s.youtube_id
    left join tbl_users     u on u.user_id    = c.queued_by;";
$qry_playing = $conn->query($str_sql);
$row = $qry_playing->fetch_assoc();

?>
<html>
<head>
<script src="../icons.js"></script>
<link rel="stylesheet" type="text/css" href="/karaoke/karaoke.css">
<style>
h1
{
font-size:70px;
text-align:center;
}
h2
{
font-size:30px;
text-align:center;
}
</style>
<script>
function sendCmd(str_Command)
{
/*    if ( str_Command == "SKIPPING" )
    {
        str_www = "/server/nextSong.php";
    }
    else*/
    {
        str_www = "setStatus.php?status=" + str_Command;
    }
    var xhttp = new XMLHttpRequest();
    xhttp.open("GET",str_www,true);
    xhttp.send();
}
</script>
<head>
<body>
<table>
<tr>
<td width="30%" style="text-align:center">
<button onclick="sendCmd('PLAYED');"><canvas width=80px height=80px id="icn_P_Play">Play</canvas></button>
<hr>
<button onclick="sendCmd('PAUSED');" style="width:48%"><canvas width=50px height=50px id="icn_P_Pause">Pause</canvas></button>
<button onclick="sendCmd('SKIPPING');" style="width:48%"><canvas width=50px height=50px id="icn_P_Skip">Skip</canvas></button>
<hr>
<button onclick="location.assign('local.php?CMD=start')" style="width:48%;font-size:2em;">On</button>
<button onclick="location.assign('local.php?CMD=stop')" style="width:48%;font-size:2em;">Off</button>
<hr>
<button onclick="location.assign('local.php?CMD=auto')" style="width:48%;font-size:2em;<?php
if ( $row["autoplay"] == "Y" )
{
    echo "background-color:#4CAF50;color:black;"; 
}
?>">AP</button>
<button onclick="location.assign('/')" style="width:48%;font-size:2em;">Home</button>
<h3><?php echo $str_wireless; ?> @ <?php echo $str_self; ?></h3>
<hr />
<h3><?php echo $row["status"]; ?></h3>
</td>
<td>
<h2>
<?php
    switch ( $row["status"] )
    {
    case "READY":
        echo "<h2 style='color:white;'>Up Next...</h2><hr>";
        echo "<h1 style='color:" . $row["color"] . ";text-shadow: -1px 0 1 white, 0 1px 1 white, 1px 0 1 white, 0 -1px 1 white;' >" . $row["queued_by"] . "</h1><hr />";
        echo "<h2>" . $row["title"] . " by " . $row["artist"] . "<h2>";
        break;
    case "PLAYING": case "PLAYED":
        echo "<h1 style='color:" . $row["color"] . ";text-shadow: -2px 0 3px white, 0 2px 3px white, 2px 0 3px white, 0 -2px 3px white;' >" . $row["queued_by"] . "</h1><hr />";
        echo "<h2>" . $row["title"] . " by " . $row["artist"] . "<h2>";
        echo "<hr>";
        echo "<h2 style='font-size:3em'>Queue: " . $int_queue_sz . "</h2>";
        break;
    case "QUIT":
        echo "<h1>Karaoke-Pi</h1><hr /><h2>Offline</h2>";
        break;
    case "STANDBY":
        echo "<h1>Karaoke-Pi</h1><hr /><h2>Standby</h2>";
        break;
    default :
        echo "<h1>" . $row["status"] . "</h1>";
        break;
    }
?>
</h2>
</td>
</tr>
</table>
    <script>
icon_Pause("icn_P_Pause");
icon_Play("icn_P_Play");
icon_Skip("icn_P_Skip");
setTimeout(function(){ location.reload() }, 1000);
    </script>
</body>
</html>
