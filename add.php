<?php
include "header.php";
include "db_init.php";

if ( isset($_POST["youtube_url"]) )
{
    echo "<h2 id='fetchFMT'>Getting Available Formats</h2>";
    flush();

    $youtube_id = youtube_id_from_url($_POST["youtube_url"]);
    $str_sql = "SELECT count(*) rec_count FROM tbl_songs where youtube_id = '" . $youtube_id . "';";
    
    $qry_playing = $conn->query($str_sql);
    $row = $qry_playing->fetch_assoc();
    
    if ( $row["rec_count"] > 0 )
    {
        echo "<h2 style='color:red'>Error! youtube_id already exists</h2>";
    }
    elseif ( ! $youtube_id )
    {
        echo "<script>alert('Failed to load! Invalid YouTube ID.');</script>";
    }
    elseif ( ! isset($_GET["format"] ))
    {
        exec("youtube-dl -F " . $youtube_id, $arr_output);

        $_SESSION["youtube_id"]   = $youtube_id;
        $_SESSION["title"]        = $conn->real_escape_string($_POST["title"]);
        $_SESSION["artist"]       = $conn->real_escape_string($_POST["artist"]);
        $_SESSION["genre"]        = $conn->real_escape_string($_POST["genre"]);
        $_SESSION["song_type"]    = $conn->real_escape_string($_POST["song_type"]);
        $_SESSION["added_by"]     = $conn->real_escape_string($_POST["added_by"]);

        foreach( $arr_output as $str_f_line )
        {
            if (preg_match('/^\s*(\d+)\s+mp4(.*)/',$str_f_line,$arr_matches))
            {
                if ( preg_match('/only/',$arr_matches[2]) < 1 )
                {
                    echo "<button>\n";
                    echo "<table width='100%'><tr><td width='%32' onclick='location.replace(\"add.php?format=";
                    echo $arr_matches[1];
                    echo "\")' >\n";
                    echo "<span style='font-weight:900;text-align:left;font-size:25px'>";
                    echo $arr_matches[1];
                    echo "</span></td><td style='text-align:right' onclick='location.replace(\"add.php?format=";
                    echo $arr_matches[1];
                    echo "\")' >\n";
                    echo str_replace(",","<br />",$arr_matches[2]);
                    echo "</td></tr></table></button>\n";
                }
            }
        }
    }
    echo "<script>document.getElementById('fetchFMT').style.display = 'none'</script>";
}
elseif ( isset($_SESSION["youtube_id"] ) )
{
    {


        $str_sql = "INSERT INTO tbl_songs (youtube_id,title,artist,genre,song_type,added_by,format) VALUES (" . 
            "'" . $_SESSION["youtube_id"] . "'," .
            "'" . $_SESSION["title"] . "'," .
            "'" . $_SESSION["artist"] . "'," .
            "'" . $_SESSION["genre"] . "'," .
            "'" . $_SESSION["song_type"] . "'," .
            "'" . $_SESSION["added_by"] . "'," .
            $_GET["format"] . ");";

        echo $str_sql;

        unset($_SESSION["youtube_id"]);
        unset($_SESSION["title"]);
        unset($_SESSION["artist"]);
        unset($_SESSION["genre"]);
        unset($_SESSION["song_type"]);
        unset($_SESSION["added_by"]);

        $new_page = "playing.php";
        if ( isset($_POST["Add_2_Queue"]) )
        {
            $new_page = "queue.php?youtube_id=" . $_POST["youtube_id"];
        }

        if ( $conn->query($str_sql) === TRUE )
        {
            exec ("php /var/www/html/karaoke/server/downloader.php > /dev/null 2>&1 &");
            $_SESSION["message"] .= "Song Successfully Added\n";
            Header("Location: " . $new_page);
        } 
    }
}
function youtube_id_from_url($url) {
    $pattern = 
        '%^# Match any youtube URL
        (?:https?://)?  # Optional scheme. Either http or https
        (?:www\.)?      # Optional www subdomain
        (?:             # Group host alternatives
         youtu\.be/    # Either youtu.be,
         | youtube\.com  # or youtube.com
         (?:           # Group path alternatives
          /embed/     # Either /embed/
          | /v/         # or /v/
          | /watch\?v=  # or /watch\?v=
         )             # End path alternatives.
        )               # End host alternatives.
        ([\w-]{10,12})  # Allow 10-12 for 11 char youtube id.
        $%x'
        ;
    $result = preg_match($pattern, $url, $matches);
    if ($result) {
        return $matches[1];
    }
    return false;
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
</script>
<div style="<?php
if ( isset($_SESSION["youtube_id"] ) )
{
    echo "visibility:hidden";
}
?>">
<form id="add_form" method="POST" accept-charset="ISO-8859-1">
<table width="100%" >
<?php
if ( isset($_GET["found_ytid"]) )
{
    echo "<h2 style='text-align:center'>Let's Download<hr>" . $_GET["found_ytname"] . "</h2><hr>";
    echo "<tr><td width='50px'>youtube id</td><td><input type='text' name='youtube_url' value='";
    echo $_GET["found_ytid"] . "' required autocomplete=off /></td></tr>";
}
else
{
    echo "<tr><td width='50px'>youtube id</td><td><input type='text' name='youtube_url' required autocomplete=off /></td></tr>";
}
?>
<tr><td>title</td><td><input type="text" name="title" required autocomplete=off /></td></tr>
<tr><td>artist</td><td>
    <select name="artist" id="artist" required style='width:80%'>
        <option />
        <?php
            $qry_artist = $conn->query("SELECT DISTINCT artist FROM tbl_songs ORDER BY artist;");
            while ( $row = $qry_artist->fetch_assoc() )
            {
                print "<option>" . $row["artist"] . "</option>";
            }
        ?>
    </select><button onclick="addField(prompt('Enter New Artist'),'artist')" style='width:18%;font-size:30px'>+</button>
    </td></tr>
<tr><td>genre</td><td>
    <select name="genre" id="genre" required style="width:80%">
        <option />
        <?php
            $qry_artist = $conn->query("SELECT DISTINCT genre FROM tbl_songs ORDER BY genre;");
            while ( $row = $qry_artist->fetch_assoc() )
            {
                print "<option>" . $row["genre"] . "</option>";
            }
        ?>
    </select><button onclick="addField(prompt('Enter New Genre'),'genre')" style='width:18%;font-size:30px'>+</button>
    </td></tr>
<tr><td>song-type</td><td>
<select name="song_type" required>
<option />
<option value="solo">Solo</option>
<option value="solo-male">Solo - Male</option>
<option value="solo-female">Solo - Female</optioin>
<option value="duet">Duet</option>
<option value="duet-male-female">Duet - Male/Female</option>
<option value="group">Group</option>
</select>
</td></tr>
<tr><td>Add to Queue</td><td><input type='Checkbox' name='Add_2_Queue' /></td></tr>
</table>
<input type='hidden' name='added_by' value='<?php echo $_SESSION["user_id"]; ?>' />
</form>
<button type="submit" form="add_form" value="Download"><canvas id="icn_Down" width="50px" height="50px">Download</canvas></button>
<script>icon_Download("icn_Down");</script>
</div>
</body>
</html>
