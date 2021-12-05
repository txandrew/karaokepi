<?php

$conn = new mysqli("localhost","kpi-server","karaokepi","karaoke");

mysqli_set_charset($conn, 'utf8');


$str_sql     = "SELECT * FROM tbl_queue q 
                INNER JOIN qry_songs_w_ratings s on q.youtube_id = s.youtube_id
                INNER JOIN tbl_users u on q.queued_by = u.user_id
                WHERE s.user_id='Andrew' ORDER BY queue_val ASC
                ;";

$qry_song_count = $conn->query($str_sql . ";");
$int_rows = $qry_song_count->num_rows;

$qry_songs = $conn->query($str_sql);
$int_row = 0;
$arr_songs = array();

if ( $qry_songs->num_rows > 0 )
{
    while ( $rec_song = $qry_songs->fetch_array(MYSQLI_ASSOC)) 
    {
        $arr_songs[] = $rec_song;
    }
}

echo json_encode($arr_songs);

$conn->close();

?>
