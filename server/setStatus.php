<?php
include "../db_init.php";
mysqli_set_charset($conn, 'utf8');

$str_sql = "UPDATE tbl_status
                    SET status = '" . $_GET['status'] . "';";
$qry_status = $conn->query($str_sql);
echo $str_sql;
?>
