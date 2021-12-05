<?php
include "header.php";

if ( isset($_GET["youtube_id"]) )
{
    include "db_init.php";
    
    $str_sql = "SELECT COUNT(*) usr_count FROM tbl_users;";
    $qry_cnt = $conn->query($str_sql);
    $row = $qry_cnt->fetch_assoc();
    $int_usrs = $row["usr_count"];
    
    $str_sql = "SELECT MAX(queue_val) + " . $int_usrs . " NextVal FROM tbl_queue WHERE queued_by = '" . $_SESSION["user_id"] . "';";
    $qry_max = $conn->query($str_sql);
    $row = $qry_max->fetch_assoc();
    $int_val = $row["NextVal"];

    echo $int_val;

    if ( is_null($int_val) )
    {
        $str_sql = "SELECT MIN(queue_val) - 1 NextVal FROM tbl_queue;";
        $qry_max = $conn->query($str_sql);
        $row = $qry_max->fetch_assoc();
        $int_val = $row["NextVal"];
    }
    if ( ! isset($int_val) )
    {
        $int_val = 0;
    }

//    if ( ! isset($int_val) )
//    {
//        $int_val = 0;
//    }

    $str_sql = "INSERT INTO tbl_queue (youtube_id, queue_val, queued_by) VALUES ('" . $_GET["youtube_id"] . "'," . $int_val . ",'" . $_SESSION["user_id"] . "');";

    echo $str_sql;
    if ( $conn->query($str_sql) === TRUE )
    {
        $_SESSION["message"] .= $_GET["song"] . " has been sucessfully added to the queue.\n";
//        header("Location: search.php");
    }
    else
    {
        $_SESSION["message"] .= "ERROR! Could not load into queue." . $str_sql . "\n";
//        header("Location: search.php");
    }
}
else
{
    $_SESSION["message"] .= "No Youtube Id Found";
//    header("Location: search.php");
}
?>
<script>
window.close();
</script>
</body>
</html>
