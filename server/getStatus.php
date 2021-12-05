<?php

$conn = new mysqli("localhost","kpi-server","karaokepi","karaoke");
mysqli_set_charset($conn, 'utf8');

$str_sql = "SELECT * FROM tbl_status t left join tbl_songs s on s.youtube_id = t.youtube_id;";
$qry_status = $conn->query($str_sql);
$song = $qry_status->fetch_assoc();

header ( "Content-Type: text/xml" );
?>
<?xml version="1.0" encoding="UTF-8" ?>
<sys_stat>
<?php 
foreach ( $song as $key => $value )
{
    echo "<$key id='tng$key'>$value</$key>\n";
}
$str_sql = "SELECT COUNT(*) QUE_SIZE FROM tbl_queue;";
$qry_status = $conn->query($str_sql);
$result = $qry_status->fetch_assoc();
echo "<que_size>" . $result["QUE_SIZE"] . "</que_size>";
?>

</sys_stat>
