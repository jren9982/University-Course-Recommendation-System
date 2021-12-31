<?php

include "private/database.inc.php";

if(isset($_POST['add'])) {
    $uni = $_POST['uni'];
    $course = $_POST['cname'];
    $u10pct = $_POST['10pct'];
    $u90pct = $_POST['90pct'];

    $uni_list_rows = $uni_list_collection->find(['uni_name'=>$uni]);
    foreach($uni_list_rows as $row){
            $uid = $row['uid'];
    }
    
    $courseJSON = $uni_course_collection->find()->toArray();
    $len = sizeof($courseJSON)+1;
    $add_course = $uni_course_collection->insertOne(['course_id'=>$len, 'uid' => $uid, 'course_name' => $course, 
                                                     'gpa10pct'=>$u10pct, 'gpa90pct'=> $u90pct]);

    if($add_course)
    {
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

    $uni_list_rows = $uni_list_collection->find(['uni_name'=>$uni]);
    foreach($uni_list_rows as $row){
        $uid = $row['uid'];
    }

        
    $uni_cca_category_rows = $uni_cca_category_collection->find(['category_name'=>$catname]);
    foreach($uni_cca_category_rows as $row){
        $category_id = $row['category_id'];
    }

    $ccaJSON = $uni_cca_collection->find()->toArray();
    $len = sizeof($ccaJSON)+1;
    
    $add_cca = $uni_cca_collection->insertOne(['cca_id'=> $len, 'uid' => $uid, 'category_id' => $category_id, 'cca_name' => $cname]);

    if($add_cca)
    {
        header("location:cca_admin.php"); // redirects to all records page
        exit;
    }
    else
    {
        echo "Error adding record"; // display error message if not added
    }
}
?>
