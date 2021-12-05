<?php
include "header.php";

$str_sql = "select count(*) queue_size, sum(if(queued_by = '" . $_SESSION["user_id"] . "',1,0)) my_queue from tbl_queue";
$qry_queue_sz = $conn->query($str_sql);
$row = $qry_queue_sz->fetch_assoc();
$int_queue_sz = $row["queue_size"];
$int_my_queue_sz = $row["my_queue"];

$str_sql = "
    select
        c.status,
        c.youtube_id,
        c.queued_by,
        s.title,
        s.artist,
        s.genre,
        s.song_type,
        r.rating,
        r.favorite
    from tbl_status         c
    left join tbl_songs     s on c.youtube_id = s.youtube_id
    left join tbl_ratings   r on c.youtube_id = r.youtube_id and r.user_id = '" . $_SESSION["user_id"] . "';";
$qry_playing = $conn->query($str_sql);
$row = $qry_playing->fetch_assoc();
?>

<script>
var int_rate = <?php if (isset($row["rating"]  )) {echo $row["rating"];} else {echo 0;} ?>;
var int_fav  = <?php if (isset($row["favorite"])) {echo $row["favorite"];} else {echo 0;} ?>;

function sendCmd(str_Command)
{
/*    if ( str_Command == "SKIPPING" )
    {
        str_www = "/server/nextSong.php";
    }
    else*/
    {
        str_www = "server/setStatus.php?status=" + str_Command;
    }
    var xhttp = new XMLHttpRequest();
    xhttp.open("GET",str_www,true);
    xhttp.send();
}
function send_Ratings()
{
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            console.log(this.responseText);
        }};
    xhttp.open("GET","setRatings.php?youtube_id=" + <?php echo "'" . $row["youtube_id"] . "'"; ?> + "&rating=" + int_rate + "&favorite=" + int_fav, true);
    xhttp.send();
}
function mark_Favorite()
{
    int_fav = (int_fav + 1) % 2;
    send_Ratings();
    draw_Ratings();
}
function mark_Rating(new_rating)
{
    console.log("new rating");
    if ( int_rate == new_rating ) { new_rating = "NULL"; }
    int_rate = new_rating;
    send_Ratings();
    draw_Ratings();
}
function draw_Ratings()
{
    if ( int_fav == 1 )
    {
        icon_Heart("icn_P_Heart","crimson");
    }
    else
    {
        icon_Heart("icn_P_Heart","white");
    }
    for ( var x = 1; x <= 5; ++x )
    {
        var color = "white";
        if ( x <= int_rate ) { color = "gold"; }
        //:w
        //icon_Star("icn_P_Star_" + x,color);
    }  
}
</script>
<h1><?php echo $row["status"]; ?></h1>
<div style="text-align:center">
<a href="#" onclick="sendCmd('PAUSED');"><i class="material-icons" style="font-size:80px;color:white">pause_circle_filled</i></a>
<a href="#" onclick="sendCmd('PLAYED');"><i class="material-icons" style="font-size:80px;color:white">play_circle_filled</i></a>
<a href="#" onclick="sendCmd('SKIPPING');"><i class="material-icons" style="font-size:80px;color:white">skip_next</i></a>
</div>
<h2>
<?php 
    if ( $row["status"] != "STANDBY" )
    {
        echo $row["title"] . " by " . $row["artist"];
    }
?>
<h2><?php echo $row["genre"]; ?></h2>
<h2><?php echo $row["queued_by"]; ?></h2>
<h2>Songs I Have in Queue: <?php echo $int_my_queue_sz; ?></h2>
<h2>Songs in Queue: <?php echo $int_queue_sz; ?></h2>
<br /><br />
<script>
icon_Pause("icn_P_Pause");
icon_Play("icn_P_Play");
icon_Skip("icn_P_Skip");

//draw_Ratings();

setTimeout(function(){location.reload()},5000);
</script>
</body>
</html>
