<?php
include "header.php";
?>
<script>
var arr_Favorite = [];
var arr_My_Rate = [];
var arr_Avg_Rate = [];
var arr_YouTube  = [];
function queueSong(str_ytid,str_title)
{
    str_desc = document.getElementById("desc_" + str_ytid).value;
    if ( confirm("Add " + str_desc + " to queue?") )
    {
        //location.assign("queue.php?youtube_id=" + str_ytid + "&song=" + str_title);
        document.getElementById("wait").style.display = "block";
        window.open("queue.php?youtube_id=" + str_ytid + "&song=" + str_title);
        setTimeout(function(){document.getElementById("wait").style.display = "none";location.reload();},3000);
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
function queue_Random()
{
    int_rnd = Math.floor((Math.random() * document.getElementsByClassName("input_ytid").length));
    queueSong(document.getElementsByClassName("input_ytid")[int_rnd].value);
}

</script>
<div id="wait" class="modal">
    <div class="modal-content">
        <h2>Please Wait</h2>
    </div>
</div>
<?php

include "db_init.php";
mysqli_set_charset($conn, 'utf8');

$str_sql = "select count(*) queue_size, sum(if(queued_by = '" . $_SESSION["user_id"] . "',1,0)) my_queue from tbl_queue";
$qry_queue_sz = $conn->query($str_sql);
$row = $qry_queue_sz->fetch_assoc();
$int_my_queue_sz = $row["my_queue"];

echo "<h2>Songs I Have in Queue: " . $int_my_queue_sz . "</h2>";

$str_filter = "";

echo "<form method=post action='search.php' id='filters' ><table>";
foreach ( explode(";","artist;genre;song_type;added_by") as $filter )
{
    $str_attr = "";
    if ( isset($_POST[$filter]) and $_POST[$filter] != "") 
    { 
        $str_filter .= " AND $filter='" . $_POST[$filter] . "' "; 
        $str_attr = $_POST[$filter] ;
    }
    $str_sql = "SELECT DISTINCT $filter FROM qry_songs_w_ratings ORDER BY 1;";
    $qry_songs = $conn->query($str_sql);
    echo "<tr><td>$filter</td><td><select name=$filter>";
    if ( $str_attr != "" ) { echo "<option>$str_attr</option>"; }
    echo "<option></option>";
    while ( $rec_val = $qry_songs->fetch_assoc() ) { echo "<option>" . $rec_val[$filter] . "</option>"; }
    echo "</select></td></tr>";
}
echo "</table></form>";
echo "<div style='text-align:center'>";
echo "<button onclick=\"location.assign('search.php')\" style='width:30%;font-size:20px' >clear</button>";
echo "<button onclick='queue_Random()' style='width:30%;font-size:20px' >random</button>";
echo "<button type='submit' form='filters' style='width:30%;font-size:20px'>filter</button>";
echo "<br /><br /></div>";

$str_sql = "SELECT * FROM qry_songs_w_ratings WHERE user_id='" . $_SESSION["user_id"] . "' $str_filter ORDER BY favorite desc, rating desc, avg_ratings desc, title;";
$qry_songs = $conn->query($str_sql);

$int_row = 0;
if ( $qry_songs->num_rows > 0 )
{
    while ( $rec_song = $qry_songs->fetch_assoc()) 
    {
        $str_thumbnail = 'thumbnails/notfound.jpg';
        if ( file_exists('thumbnails/' . $rec_song["youtube_id"] . '.jpg'))
        {
            $str_thumbnail = 'thumbnails/' . $rec_song["youtube_id"] . '.jpg';
        }

        echo "\n\n";
        echo "<button>\n";
        echo " <table width='100%'>";
        echo "  <tr>";
        echo "   <td width='68%' onclick=\"queueSong('" . $rec_song["youtube_id"] . "','" . addslashes( $rec_song["title"]) . "')\";>\n";
        echo "    <span style='font-weight:900;text-align:left;font-size:25px'>" . ($rec_song["title"]) . "</span><br />\n";
        echo "    <span style='text-align:left;'>" . $rec_song["artist"] . "</span><br />\n";
        echo "    <span style='text-align:left;'>" . $rec_song["genre"] . "</span>\n";
        echo "    <input type='hidden' class='input_ytid' value='" . $rec_song["youtube_id"] . "' />";
        echo "    <input type='hidden' id='desc_" . $rec_song["youtube_id"] . "' value='" . $rec_song["title"] . " by " . $rec_song["artist"] . "' />";
        echo "   </td>";
        echo "   <td style='text-align:right'>\n";
        echo "    <img style='width:100px' src='$str_thumbnail' alt='" . $rec_song["title"] . "' />";
        echo "   </td>";
        echo "  </tr>";
        echo " </table>";
        echo "</button><br /><br />\n";

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
?>
<script>
var last_rec = <?php echo $int_row; ?>;
for ( var x = 0; x < 0; ++x )
{
    draw_Ratings(x);
}
</script>
</body>
</html>
