<?php
header ("Content-Type: text/plain");


$str_arg = " -o '/var/www/html/karaoke/videos/%(id)s.%(ext)s' -f 'bestvideo[ext=mp4]+bestaudio[ext=m4a]/mp4' --cache-dir /var/karaoke/youtube-dl/ "; #136

include "../db_init.php";
mysqli_set_charset($conn, 'utf8');

$conn->query("INSERT INTO tbl_messages (EXEC_FILE, MSG_TYPE, MESSAGE) values ('downloader.php','youtube-dl arg','youtube-dl" . $str_arg . $rec_song["youtube_id"] . " -f " . $rec_song["format"]);

$str_sql = "SELECT * FROM tbl_songs WHERE downloaded<1 order by added_time;";
$qry_songs = $conn->query($str_sql);

$int_row = 0;

echo $qry_songs->num_rows;
if ( $qry_songs->num_rows > 0 )
{
    while ( $rec_song = $qry_songs->fetch_assoc()) 
    {
        echo $rec_song["youtube_id"] . " - " . $rec_song["title"] . "\n";
        echo "youtube-dl" . $str_arg . "'/" . $rec_song["youtube_id"] . "' -f " . $rec_song["format"] . "\n";
        $str_status = exec("youtube-dl" . $str_arg . $rec_song["youtube_id"] . " -f " . $rec_song["format"]);

        if ( file_exists( "/var/www/html/karaoke/videos/" .
                            $rec_song["youtube_id"] .
                            ".mp4" ))
        {
            echo "Downloaded";
            $conn->query("UPDATE tbl_songs 
                            SET downloaded = 1 
                            WHERE youtube_id = '" . $rec_song["youtube_id"] . "'
                            ;");
        }
        else
        {
            echo "Error!";
            $conn->query("INSERT INTO tbl_messages (
                            EXEC_FILE, MSG_TYPE, MESSAGE ) VALUES (
                            'downloader.php',
                            'ERROR',
                            'Could not download " .
                            $rec_song["title"] . " by " .
                            $rec_song["artist"] . " [" . 
                            $rec_song["youtube_id"] . "]');");
            $conn->query("UPDATE tbl_songs 
                            SET downloaded=-1 
                            WHERE youtube_id='" . $rec_song["youtube_id"] . "';");
        }
    }
}
$conn->close();
?>
