<!-- Created By Jia Jia-->
<?php

include "private/db_conn.php";

if(isset($_POST['add'])) {
    $uni = $_POST['uni'];
    $course = $_POST['cname'];
    $u10pct = $_POST['10pct'];
    $u90pct = $_POST['90pct'];

    $sql = mysqli_query($conn, "SELECT uid FROM uni_list WHERE uni_name = '$uni'");
    if ($sql->num_rows > 0) {
        $row = mysqli_fetch_assoc($sql);
        $uid = $row['uid'];
    }

    $add = mysqli_query($conn,"INSERT INTO uni_courses (uid, course_name, gpa10pct, gpa90pct) VALUES ('$uid','$course','$u10pct','$u90pct')"); // added query

    if($add)
    {
        mysqli_close($conn); // Close connection
        header("location:admin.php"); // redirects to all records page
        exit;
    }
    else
    {
        echo "Error adding record"; // display error message if not added
        echo $add;
    }
}


if(isset($_POST['addcca'])) {
    $uni = $_POST['uni'];
    $catname = $_POST['catname'];
    $cname = $_POST['cname'];

    $sql = mysqli_query($conn, "SELECT uid FROM uni_list WHERE uni_name = '$uni'");
    if ($sql->num_rows > 0) {
        $row = mysqli_fetch_assoc($sql);
        $uid = $row['uid'];
    }
    $sql = mysqli_query($conn, "SELECT category_id FROM uni_cca_categories WHERE category_name = '$catname'");
    if ($sql->num_rows > 0) {
        $row = mysqli_fetch_assoc($sql);
        $category_id = $row['category_id'];
    }

    $add = mysqli_query($conn,"INSERT INTO uni_cca (category_id, uid, cca_name) VALUES ('$category_id','$uid','$cname')"); // added query

    if($add)
    {
        mysqli_close($conn); // Close connection
        header("location:cca_admin.php"); // redirects to all records page
        exit;
    }
    else
    {
        echo "Error adding record"; // display error message if not added
    }
}
?>