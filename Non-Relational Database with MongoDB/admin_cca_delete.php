<?php

include "private/database.inc.php";

$id = (int)$_GET['id']; // get id through int

$del = $uni_cca_collection->deleteOne(['cca_id'=> $id]); // deleting by course_id

if($del)
{
    header("location:cca_admin.php"); // redirects to all records page
    exit;
}
else
{
    echo "Error deleting record"; // display error message if not delete

}