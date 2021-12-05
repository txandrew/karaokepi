<?php
include "header.php";
?>
<script>
var arr_Favorite = [];
var arr_My_Rate = [];
var arr_Avg_Rate = [];
var arr_YouTube  = [];
function queueSong(str_ytid)
{
    if ( confirm("Add song to queue?") )
    {
        location.assign("queue.php?youtube_id=" + str_ytid);
    }
}
function draw_Ratings(int_row)
{
    int_fav = arr_Favorite[int_row];
    int_my  = arr_My_Rate[int_row];
    int_avg = arr_Avg_Rate[int_row];
    if ( int_fav == 1 )
    {
        icon_Heart("icn_P_Heart_" + int_row ,"crimson");
    }
    else
    {
        icon_Heart("icn_P_Heart_" + int_row,"white");
    }
    for ( var x = 1; x <= 5; ++x )
    {
        var color = "white";
        if ( x <= int_my ) { color = "gold"; }
        icon_Star("icn_P_M_Star_" + x + "_" + int_row,color);
    }  
    for ( var x = 1; x <= 5; ++x )
    {
        var color = "white";
        if ( x <= int_avg ) { color = "gold"; }
        icon_Star("icn_P_A_Star_" + x + "_" + int_row,color);
    }  
}
function rate_Song(int_row,int_new_rate)
{
    int_rate = arr_My_Rate[int_row];
    if ( int_rate == int_new_rate ) { int_new_rate = "NULL"; }
    arr_My_Rate[int_row] = int_new_rate;
    send_Ratings(int_row);
    draw_Ratings(int_row);
}
function edit_Song(str_ytid)
{
    location.replace("edit.php?ytid=" + str_ytid);
}
function replay_Song(str_ytid,str_title)
{
    if ( confirm("Add " + str_title + " to queue?") )
    {
        //location.assign("queue.php?youtube_id=" + str_ytid + "&song=" + str_title);
        window.open("queue.php?youtube_id=" + str_ytid + "&song=" + str_title);
    }
}
function delete_Song(str_ytid)
{
    if ( confirm("Are you sure you want to delete this song?") )
    {
        location.replace("delete.php?ytid=" + str_ytid);
    }
}
function queue_Update(str_ytid,qval,action)
{
    location.replace("qedit.php?ytid=" + str_ytid + "&qval=" + qval + "&action=" + action);
}
function mark_Favorite(int_row)
{
    arr_Favorite[int_row] = (arr_Favorite[int_row] + 1) % 2;
    send_Ratings(int_row);
    draw_Ratings(int_row);
}
function send_Ratings(int_row)
{
    int_fav  = arr_Favorite[int_row];
    int_rate = arr_My_Rate[int_row];
    str_ytid = arr_YouTube[int_row];
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            console.log(this.responseText);
        }};
    xhttp.open("GET","setRatings.php?youtube_id=" + str_ytid + "&rating=" + int_rate + "&favorite=" + int_fav, true);
    xhttp.send();
}

</script>
<?php

include "db_init.php";

mysqli_set_charset($conn, 'utf8');

$int_page = 0;
$int_page_size = 20;
if ( isset($_GET["page"]) )
{
    $int_page = $_GET["page"];
}
$int_page_begin = $int_page     * $int_page_size;
$int_page_end   = $int_page_begin + $int_page_size;

$str_filter = "";

$str_queue = "";
$str_hist  = "";
$str_down  = "";
$str_all   = "";
if ( ! isset ($_GET["list"]) or $_GET["list"] == "queue" )
{
    $str_queue = " class='active' ";
    $str_page  = "queue"; 
/*    $str_sql = "select l.queued_by, s.youtube_id, s.title, s.artist, s.genre, s.song_type, s.downloaded, q.queue_val, s.added_by, s.added_time, s.user_id, s.rating, s.favorite, s.avg_ratings, s.reviews  from qry_last_played l inner join tbl_queue q on l.queued_by = q.queued_by inner join qry_songs_w_ratings s on s.youtube_id = q.youtube_id group by l.queued_by order by last_played;";
 */
    $str_sql = "SELECT * FROM tbl_queue q 
                INNER JOIN qry_songs_w_ratings s on q.youtube_id = s.youtube_id
                INNER JOIN tbl_users u on q.queued_by = u.user_id
                WHERE s.user_id='" . $_SESSION["user_id"] . "' ORDER BY queue_val ASC";
}
elseif ( $_GET["list"] == "history" )
{
    $str_hist  = " class='active' ";
    $str_page  = "history";
    $str_sql = "SELECT * FROM tbl_history q 
                INNER JOIN qry_songs_w_ratings s on q.youtube_id = s.youtube_id
                INNER JOIN tbl_users u on q.queued_by = u.user_id
                WHERE s.user_id='" . $_SESSION["user_id"] . "' ORDER BY played DESC";
}
elseif ( $_GET["list"] == "downloading" )
{
    $str_down  = " class='active' ";
    $str_queue = "";
    $str_page  = "downloading";
    $str_sql = "SELECT * FROM qry_songs_w_ratings s WHERE s.user_id='" . $_SESSION["user_id"] . "' 
                and downloaded<1 ORDER BY added_time DESC";
}
elseif ( $_GET["list"] == "all" )
{
    $str_all   = " class='active' ";
    $str_queue = "";
    $str_page  = "all";
    $str_sql = "SELECT * FROM qry_songs_w_ratings s WHERE s.user_id='" . $_SESSION["user_id"] . "' 
                ORDER BY title";
}
#echo $str_sql;


$qry_song_count = $conn->query($str_sql . ";");
$int_rows = $qry_song_count->num_rows;

$qry_songs = $conn->query($str_sql . " LIMIT $int_page_begin, $int_page_size;");
$int_row = 0;

echo "<div style='text-align:center'>";
echo "<button $str_queue onclick=\"location.assign('list.php?list=queue'  )\" style='width:45%;font-size:20px' >queue</button>";
echo "<button $str_hist  onclick=\"location.assign('list.php?list=history')\" style='width:45%;font-size:20px' >history</button><br />";
echo "<button $str_down  onclick=\"location.assign('list.php?list=downloading')\" style='width:45%;font-size:20px' >downloading</button>";
echo "<button $str_all  onclick=\"location.assign('list.php?list=all')\" style='width:45%;font-size:20px' >all songs</button>";
echo "<br />";

echo "Page " . ($int_page + 1) . "<br /><br /></div>";

echo "<div style='text-align:center'>";
if ( $int_page != 0 ) 
{
    echo "<button onclick=\"location.assign('list.php?list=" . $str_page . "&page=" . ( $int_page - 1 ) . "')\" style='width:45%;font-size:20px' >prev</button>";
}
if ( $int_rows > ( $int_page * $int_page_size ) + $int_page_size )
{
    echo "<button onclick=\"location.assign('list.php?list=" . $str_page . "&page=" . ( $int_page + 1 ) . "')\" style='width:45%;font-size:20px' >next</button>";
}
echo "</div>";
echo "<br /><br />";


if ( $qry_songs->num_rows > 0 )
{
    
    while ( $rec_song = $qry_songs->fetch_assoc()) 
    {
        echo "\n\n";
        echo "<button>\n";
        echo "<table width='100%'><tr><td width='68%' onclick=\"\";>\n";
        echo "<span style='font-weight:900;text-align:left;font-size:25px'>" . $rec_song["title"] . "</span><br />\n";
        echo "<span style='text-align:left;'>" . $rec_song["artist"] . "</span><br />\n";
        echo "<span style='text-align:left;'>" . $rec_song["genre"] . "</span><br />\n";

        echo "</td><td style='text-align:right'>\n";
        echo $rec_song["song_type"];
        echo "<canvas id='icn_P_Heart_" . $int_row . "' onclick='mark_Favorite(" . $int_row . ");' width=20px height=20px>Fav</canvas><br />";
        echo "My ";
        for ( $int_x = 1; $int_x <= 5; ++$int_x )
        {
            echo "<canvas onclick='rate_Song(" . $int_row . "," . $int_x . ")' id='icn_P_M_Star_" . $int_x . "_" . $int_row . "' width=15px height=15px>S1</canvas>";
        }
        echo "<br />";
        echo "(" . $rec_song["reviews"] . ")";
        for ( $int_x = 1; $int_x <= 5; ++$int_x )
        {
            echo "<canvas id='icn_P_A_Star_" . $int_x . "_" . $int_row . "' width=15px height=15px>S1</canvas>";
        }
        echo "</td></tr>";

        if ( $str_page == "queue" or $str_page == "history" )
        {
            echo "<tr><td colspan=2 style='text-align:center;font-weight:700;color:#" . $rec_song["color"];
            echo "'>Queued by " . $rec_song["queued_by"] . "</span>\n";
        }
        if ( $rec_song["downloaded"] == -1 )
        {
            echo "<tr><td colspan=2 style='text-align:center;color:red;font-weight:700'>";
            echo "Download Error</td></tr>";
        }

        if ( $str_page == "queue" )
        {
            echo "<tr><td style='text-align:left;font-weight:700' onclick='queue_Update(\"";
            echo $rec_song["youtube_id"] . "\"," . $rec_song["queue_val"] . ",\"top\")' >";
            echo "Next Song</td>";
            echo "<td style='text-align:right;font-weight:700' onclick='queue_Update(\"";
            echo $rec_song["youtube_id"] . "\"," . $rec_song["queue_val"] . ",\"cancel\")' >";
            echo "Cancel</td></tr>";
        }
        else
        {
            echo "<tr><td width='33%' style='text-align:left;font-weight:700'   onclick='edit_Song(\"" . $rec_song["youtube_id"] . "\")' >Edit Song</td>";
            echo "    <td width='33%' style='text-align:center;font-weight:700' onclick='replay_Song(\"" . $rec_song["youtube_id"] . "\", \"" . $rec_song["title"] . "\")' >Queue</td>";
            echo "    <td width='33%' style='text-align:right;font-weight:700'  onclick='delete_Song(\"" . $rec_song["youtube_id"] . "\")' >Delete Song</td></tr>";
        }
        
        echo "</table></button><br /><br />\n";

        echo "<script>";
        echo "arr_Favorite[" . $int_row . "] = " . $rec_song["favorite"] . ";";
        echo "arr_My_Rate[" . $int_row . "] = " . $rec_song["rating"] . ";";
        echo "arr_Avg_Rate[" . $int_row . "] = " . $rec_song["avg_ratings"] . ";";
        echo "arr_YouTube[" . $int_row . "] = '" . $rec_song["youtube_id"] . "';";
        echo "</script>";

        $int_row++;
    }
}
$conn->close();

echo "<div style='text-align:center'>";
if ( $int_page != 0 ) 
{
    echo "<button onclick=\"location.assign('list.php?list=" . $str_page . "&page=" . ( $int_page - 1 ) . "')\" style='width:45%;font-size:20px' >prev</button>";
}
if ( $int_rows > ( $int_page * $int_page_size ) + $int_page_size )
{
    echo "<button onclick=\"location.assign('list.php?list=" . $str_page . "&page=" . ( $int_page + 1 ) . "')\" style='width:45%;font-size:20px' >next</button>";
}
echo "</div>";
?>

<script>
var last_rec = <?php echo $int_row; ?>;
for ( var x = 0; x < last_rec; ++x )
{
    draw_Ratings(x);
}
</script>
</body>
</html>
