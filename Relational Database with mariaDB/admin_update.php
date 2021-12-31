<?php

include "private/db_conn.php";

if (isset($_POST['update'])) {
    $id = $_POST['row']; // get id through query string
    $uni = $_POST['euni'];
    $course = $_POST['ecname'];
    $u10pct = $_POST['e10pct'];
    $u90pct = $_POST['e90pct'];

    $sql = mysqli_query($conn, "SELECT uid FROM uni_list WHERE uni_name = '$uni'");
    if ($sql->num_rows > 0) {
        $row = mysqli_fetch_assoc($sql);
        $uid = $row['uid'];
    }


    $update = mysqli_query($conn, "UPDATE uni_courses SET uid='$uid', course_name='$course',gpa10pct='$u10pct', gpa90pct='$u90pct' WHERE course_id='$id'"); // update query

    if ($update) {
        mysqli_close($conn); // Close connection
        header("location:admin.php"); // redirects to all records page
        exit;
    } else {
        echo "Error updating record"; // display error message if not delete
    }
}


if (isset($_POST['updatecca'])) {
    $id = $_POST['row']; // get id through query string
    $euni = $_POST['euni'];
    $ecat = $_POST['ecat'];
    $ecca = $_POST['ecca'];

    $sql = mysqli_query($conn, "SELECT uid FROM uni_list WHERE uni_name = '$euni'");
    if ($sql->num_rows > 0) {
        $row = mysqli_fetch_assoc($sql);
        $uid = $row['uid'];
    }
    $sql = mysqli_query($conn, "SELECT category_id FROM uni_cca_categories WHERE category_name = '$ecat'");
    if ($sql->num_rows > 0) {
        $row = mysqli_fetch_assoc($sql);
        $category_id = $row['category_id'];
    }

    $update = mysqli_query($conn, "UPDATE uni_cca SET category_id='$category_id',uid='$uid', cca_name='$ecca' WHERE cca_id='$id'"); // update query

    if ($update) {
        mysqli_close($conn); // Close connection
        header("location:cca_admin.php"); // redirects to all records page
        exit;
    } else {
        echo "Error updating record"; // display error message if not delete
    }
}
?>



