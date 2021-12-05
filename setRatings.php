<?php
session_start();

include "db_init.php";

if ( ! isset($_SESSION["user_id"]) )
{
    header('Location: login.php');
}

$str_sql = "DELETE FROM tbl_ratings 
            WHERE 
                youtube_id = '" . $_GET["youtube_id"] . "' and 
                user_id = '" . $_SESSION["user_id"] . "';";
$conn->query($str_sql);
$str_sql = "INSERT INTO tbl_ratings (youtube_id, user_id, rating, favorite)
            VALUES ('" . $_GET["youtube_id"] . "',
                    '" . $_SESSION["user_id"] . "',
                    "  . $_GET["rating"] . ",
                    "  . $_GET["favorite"] . ");";
$conn->query($str_sql);
echo $str_sql;
?>
