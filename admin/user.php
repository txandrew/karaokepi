<?php
session_start();
error_reporting( E_ALL );
ini_set('display_errors', 1);

include "../db_init.php";

$str_active = " ";
$str_userid = " ";
$str_email  = " ";
$str_color  = " ";

if ( !isset($_SESSION["admin"]) )
{
    Header ("Location: ..");
}
elseif ( isset( $_POST["action"] ) )
{
    $_POST["color"] = substr($_POST["color"], 1);
    $int_active = 0;
    if ( isset( $_POST["active"] )) 
    {
        $int_active = 1;
    }

    if ( $_POST["action"] == "new" )
    {
        $str_sql = "INSERT INTO tbl_users (user_id, email, color, active)" .
            " VALUES ('" . $_POST["user_id"] . "','" .
                           $_POST["email"] . "','" .
                           $_POST["color"] . "'," .
                           $int_active . ");";

        $conn->query($str_sql);
    }
    elseif ( $_POST["action"] == "edit" )
    {
        $str_sql = "UPDATE tbl_users SET " .
            "email = '" . $_POST["email"] . "', " .
            "color = '" . $_POST["color"] . "', " .
            "active = " . $int_active . " " .
            "WHERE user_id = '" . $_POST["existing"] . "';";
        $conn->query($str_sql);
    }
    elseif ( $_POST["action"] == "delete" )
    {
        $str_sql = "DELETE FROM tbl_users WHERE user_id = '" . $_POST["existing"] . "';";
        $conn->query($str_sql);
    }
    Header ("Location: index.php");
}
elseif ( isset($_GET["load"] ) )
{
    $str_sql = "select * from tbl_users where user_id = '" . $_GET["load"] . "';";
    $qry_load = $conn->query($str_sql);
    if ( $rec_song = $qry_load->fetch_assoc() )
    {
        $str_userid = $rec_song["user_id"];
        $str_email = " value='" . $rec_song["email"] . "' ";
        $str_color = " value='#" . $rec_song["color"] . "' ";
        if ( $rec_song["active"] == 1 )
        {
            $str_active = " checked ";
        }
    }
}
?>

<html>
  <head>
    <title>Admin Functions</title>
    <link rel="stylesheet" type="text/css" href="../karaoke.css">
    <script>
function actionChange()
{
    if (document.getElementById("selAction").value != "new" )
    {
        document.getElementById("divUsers").style.visibility = "visible";
    }
    else
    {
        document.getElementById("divUsers").style.visibility = "hidden";
    }
}
function userChange()
{
    if (document.getElementById("selAction").value == "edit" )
    {
        location.replace("user.php?load=" + document.getElementById("selUser").value);
    }
}
    </script>
  </head>
  <body>
    <h1>Karaoke User Functions<h1>


    <form id='user_form' method='POST'>
      <h3>Action
        <select name='action' onchange='actionChange()' id="selAction" >
<?php
if ( isset( $_GET["load"] ) )
{
    echo "<option value='edit'>Edit User</option>";
}
else
{
    echo "<option value='new'>New User</option>";
    echo "<option value='edit'>Edit User</option>";
    echo "<option value='delete'>Delete User</option>";
}
?>
        </select>
        <h3 <?php if(!isset($_GET["load"])){echo " style='visibility:hidden' ";} ?> id="divUsers">User
          <select name='existing' id='selUser' onchange="userChange()" >
            <?php
                if ( isset($_GET["load"]) )
                {
                    echo "<option>" . $str_userid . "</option>";
                }
            ?>
            <option />
            <?php
                $qry_users = $conn->query("select * from tbl_users;");
                while ( $rec_user = $qry_users->fetch_assoc() )
                {
                    echo "<option>" . $rec_user["user_id"] . "</option>";
                }
            ?>
          </select>
        </h3>
        <h3 <?php if(isset($_GET["load"])){echo " style='visibility:hidden' ";} ?> >user id<input type='input' name='user_id' ></h3>
        <h3>email<input type='email' name='email' <?php echo $str_email; ?> ></h3>
        <h3>color<input type='color' name='color' <?php echo $str_color; ?> ></h3>
        <h3>active<input type='checkbox' name='active' <?php echo $str_active; ?> ></h3>
      <button type='submit' form='user_form'>Submit</button>
      </form><br />
      <button onclick="location.replace('index.php')" >Admin Menu </button><br /><br />
  </body>
</html>
