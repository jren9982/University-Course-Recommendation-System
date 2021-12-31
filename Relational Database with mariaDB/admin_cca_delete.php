<!-- Created By Jia Jia-->
<?php

include "private/db_conn.php";

$id = $_GET['id']; // get id through query string

$del = mysqli_query($conn,"delete from uni_cca where cca_id = '$id'"); // delete query

if($del)
{
    mysqli_close($conn); // Close connection
    header("location:cca_admin.php"); // redirects to all records page
    exit;
}
else
{
    echo "Error deleting record"; // display error message if not delete
}
?>
