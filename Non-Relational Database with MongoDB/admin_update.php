<?php
include "private/database.inc.php";
    if(isset($_POST['updateCCA'])){
        $cca_id = $_POST['cca_id']; 
        $cca_name = $_POST['cca_name'];
        $newCca = $_POST['ecca'];
        $newCategory = $_POST['ecat'];
        $newUni = $_POST['euni'];   
        $updateResult = $uni_cca_collection->updateOne(
        ['cca_id' => (int)$cca_id],
        ['$set' => ['cca_name' => $newCca, 'category_id'=>$newCategory,'uid'=>$newUni]]
        );
        if ($updateResult) {
            echo "Result updated successfully";
            unset($_POST); // delete POST variables
            header('location:cca_admin.php');
            exit;
            } else {
             echo "Error updating record"; // display error message if not delete
        }
 }
    else if(isset($_POST['updateCourse'])){
        $editedUni = $_POST['euni'];
        $newCourse = $_POST['ecname'];
        $new10gpa = $_POST['e10pct'];
        $new90gpa = $_POST['e90pct'];
        $oldCourse = $_POST['course_name'];
        $record = $uni_list_collection->findOne(['uni_name'=>$editedUni]);
        $uid = $record['uid'];

        // var_dump($uid);
        // echo $editedUni.$newCourse.$new10gpa.$new90gpa.$oldCourse;
        $updateResult = $uni_course_collection->updateOne(
        ['course_name' => $oldCourse],
        ['$set' => 
        [
         'uid'=>$uid,
         'course_name'=>$newCourse,
         'gpa10pct'=>$new10gpa,
         'gpa90pct'=>$new90gpa,
        ]]
        );
        if ($updateResult) {
            echo "Result updated successfully";
            unset($_POST); // delete POST variables
            header('location:admin.php');
            exit;
            } else {
             echo "Error updating record"; // display error message if not delete
        }

        unset($_POST); // delete POST variables
    }
?>