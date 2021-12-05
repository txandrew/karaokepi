<?php
include "header.php";

$conn = new mysqli("localhost","kpi-server","karaokepi","karaoke");


if ( isset($_GET["ytid"]) )
{
    $str_sql = "SELECT * FROM tbl_songs WHERE youtube_id='" . $_GET['ytid'] . "';";
    $qry_song = $conn->query($str_sql);
    if ( $qry_song->num_rows > 0 )
    {
        $rec_song = $qry_song->fetch_assoc();

        echo "<div style='text-align:center'>";
        echo "<h2>" . $rec_song["title"] . " by " . $rec_song["artist"] . "</h2>";
        $str_sql = "DELETE FROM tbl_songs WHERE youtube_id = '" . $_GET["ytid"] . "';";
        echo $str_sql;
        $qry_del = $conn->query($str_sql);
        exec("rm videos/" . $_GET["ytid"] . ".mp4");
        echo "<hr /><h2>has been deleted</h2>";
    }
    else
    {
        echo "<h2>Could not find song in database</h2>";
    }
    echo "</div>";
}    
?>
</body>
</html>
