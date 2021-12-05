<?php
include "header.php";

if ( isset($_GET["ytid"]) and isset($_GET["qval"] ))
{
    if ( ! isset($_GET["action"]) )
    {
        echo "<h2>Error, no action given</h2>";
    }
    elseif ( $_GET["action"] == "top" )
    {
        $str_sql = "SELECT MIN(queue_val) - 1 lowest FROM tbl_queue;";
        $qry_conn = $conn->query($str_sql);

        $int_newqval = 0;
        if ( $qry_conn->num_rows > 0 )
        {
            $rec_song = $qry_conn->fetch_assoc();

            $int_newqval = $rec_song["lowest"];
        }

        $str_sql = "UPDATE tbl_queue SET queued_by='Admin', queue_val = " . $int_newqval . 
                    " WHERE youtube_id = '" . $_GET["ytid"] . "' AND " .
                    " queue_val = " . $_GET["qval"] . ";";

        echo $str_sql;

        $conn->query($str_sql);

 #       $str_sql = "INSERT INTO tbl_history (youtube_id, queued_by) VALUES ('" +  $_GET["ytid"] + "', '" .
                     
    }
    elseif ( $_GET["action"] == "cancel" )
    {
        $str_sql = "DELETE FROM tbl_queue WHERE youtube_id = '" . $_GET["ytid"] . "' and 
                        queue_val = " . $_GET["qval"] . ";";

        $conn->query($str_sql);

        Header ("Location: list.php"); 
    }
}
else
{
    echo "<h2>Error, no parameters given</h2>";
}
?>
</body>
</html>
