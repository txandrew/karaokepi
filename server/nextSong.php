<?php

include "../db_init.php";
mysqli_set_charset($conn, 'utf8');

$str_sql = "SELECT * FROM tbl_status;";
$qry_curStatus = $conn->query($str_sql);
$result_status = $qry_curStatus->fetch_assoc();

$str_sql = "SELECT ifnull(youtube_id,-1) youtube_id, queued_by FROM tbl_queue;";
$qry_nextSong = $conn->query($str_sql);

if ( mysqli_num_rows($qry_nextSong) > 0 )
{
    $result = $qry_nextSong->fetch_assoc();

    $str_sql = "UPDATE tbl_status
        SET status = 'PLAYING',
            youtube_id = '" . $result["youtube_id"] . "',
            queued_by = '" . $result["queued_by"] .  "';";
    $qry_status = $conn->query($str_sql);

    $str_sql = "INSERT INTO tbl_history (youtube_id, queued_by) VALUES ('" 
                . $result["youtube_id"] 
                . "','" . $result["queued_by"] . "');";
    $qry_status = $conn->query($str_sql);
    echo $str_sql;


    $str_sql = "DELETE FROM tbl_queue order by queue_val limit 1;";
    $qry_status = $conn->query($str_sql);
}
elseif ( $result_status['status'] != 'STANDBY' )
{
    $str_sql = "UPDATE tbl_status
        SET status = 'STANDBY',
            youtube_id = NULL,
            queued_by = NULL;";
    $qry_status = $conn->query($str_sql);
}
?>
