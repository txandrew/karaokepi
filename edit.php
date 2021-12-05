<?php
include "header.php";

$conn = new mysqli("localhost","kpi-server","karaokepi","karaoke");

if ( isset($_POST["youtube_id"]) )
{
    $str_sql = "UPDATE tbl_songs SET " .
        "title      = '" . $conn->real_escape_string($_POST['title']) . "', " .
        "artist     = '" . $conn->real_escape_string($_POST['artist']) . "', " .
        "genre      = '" . $conn->real_escape_string($_POST['genre']) . "', " .
        "song_type  = '" . $conn->real_escape_string($_POST['song_type']) . "' WHERE " .
        "youtube_id = '" . $_POST['youtube_id'] . "';";

    $qry_update = $conn->query($str_sql);

    $_SESSION["message"] .= "Song Successfully Updated\n";
    Header("Location: playing.php");
    
}
elseif ( isset($_GET["ytid"] ) )
{
    $str_sql = "SELECT * FROM tbl_songs WHERE youtube_id = '" . $_GET["ytid"] . "';";

    $qry_songs = $conn->query($str_sql);
    if ( $qry_songs->num_rows > 0 )
    {
        $rec_song = $qry_songs->fetch_assoc();

        $str_youtube_id = $rec_song["youtube_id"];
        $str_title      = $rec_song["title"];
        $str_artist     = $rec_song["artist"];
        $str_genre      = $rec_song["genre"];
        $str_song_type  = $rec_song["song_type"];
    }
}
else
{
    echo "<h2>Could not find song</h2>";
}
?>

<script>
function addField(str_value,str_id)
{
    var sel = document.getElementById(str_id);
    var opt = document.createElement('option');
    opt.innerHTML = str_value;
    opt.value = str_value;
    sel.appendChild(opt);
    sel.value = str_value;
}
function delete_Song(str_ytid)
{
    if ( confirm("Are you sure you want to delete this song?") )
    {
        location.replace("delete.php?ytid=" + str_ytid);
    }
}
</script>
<div style="<?php
if ( isset($_SESSION["youtube_id"] ) )
{
    echo "visibility:hidden";
}
?>">
<form id="edit_form" method="POST" accept-charset="ISO-8859-1">
<table width="100%" >
<tr><td width="50px">youtube id</td><td><input type="text" name="youtube_id" required autocomplete=off value="<?php echo $str_youtube_id; ?>" readonly /></td></tr>
<tr><td>title</td><td><input type="text" name="title" required autocomplete=off value="<?php echo $str_title; ?>" /></td></tr>
<tr><td>artist</td><td>
    <select name="artist" id="artist" required style='width:80%'>
    <option><?php echo $str_artist; ?></option>
        <?php
            $qry_artist = $conn->query("SELECT DISTINCT artist FROM tbl_songs ORDER BY artist;");
            while ( $row = $qry_artist->fetch_assoc() )
            {
                print "<option>" . $row["artist"] . "</option>";
            }
        ?>
    </select><button onclick="addField(prompt('Enter New Artist'),'artist')" style='width:18%;height:30px;font-size:20px'>+</button>
    </td></tr>
<tr><td>genre</td><td>
    <select name="genre" id="genre" required style="width:80%">
    <option><?php echo $str_genre; ?></option>
        <?php
            $qry_artist = $conn->query("SELECT DISTINCT genre FROM tbl_songs ORDER BY genre;");
            while ( $row = $qry_artist->fetch_assoc() )
            {
                print "<option>" . $row["genre"] . "</option>";
            }
        ?>
    </select><button onclick="addField(prompt('Enter New Genre'),'genre')" style='width:18%;height:30px;font-size:20px'>+</button>
    </td></tr>
<tr><td>song-type</td><td>
<select name="song_type" required>
<option><?php echo $str_song_type; ?>
<option value="solo">Solo</option>
<option value="solo-male">Solo - Male</option>
<option value="solo-female">Solo - Female</optioin>
<option value="duet">Duet</option>
<option value="duet-male-female">Duet - Male/Female</option>
<option value="group">Group</option>
</select>
</td></tr>
</table>
</form>
<button type="submit" form="edit_form" value="Edit">Edit</button><br /><br />
<button onclick='delete_Song("<?php echo $str_youtube_id; ?>")' >Delete</button>
</div>
</body>
</html>
