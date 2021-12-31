<?php

include "private/database.inc.php";

$id = (int)$_GET['id']; // get id through query string

$del = $uni_course_collection->deleteOne(['course_id'=> $id]); // deleting by 

if($del)
{
    header("location:admin.php"); // redirects to all records page
    exit;
}
else
{
    echo "Error deleting record"; // display error message if not delete

}
?>
