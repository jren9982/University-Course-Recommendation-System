<?php
include "private/database.inc.php";

if (isset($_POST['request'])) {

    $request = $_POST['request'];
    $record = $uni_list_collection->findOne(['uni_name'=>$request]);
    $uid = $record['uid'] ?? null;
    // INNERJOIN for UNI table and COURSE table (mongoDB)
    if ($request == 'all') {
        $result_JSON = $uni_course_collection->find($filter, $options)->toArray();
        $uni_JSON = $uni_list_collection->find()->toArray();
        foreach($result_JSON as $course_OBJ){
            foreach($uni_JSON as $uni_OBJ){
                if($course_OBJ['uid'] == $uni_OBJ['uid']){
                    $course_OBJ['uni_name'] = $uni_OBJ['uni_name'];
                    continue;
                }
            }
        }
        $success = True;
        
    } else {
        $uni_filter = ['uid' => $uid];
        $result_JSON = $uni_course_collection->find($uni_filter, $options)->toArray();
        $uni_JSON = $uni_list_collection->find()->toArray();
        foreach($result_JSON as $course_OBJ){
            foreach($uni_JSON as $uni_OBJ){
                if($course_OBJ['uid'] == $uni_OBJ['uid']){
                    $course_OBJ['uni_name'] = $uni_OBJ['uni_name'];
                    continue;
                }
            }
        }
       
       $success = True;
    }
    ?>
    <table class="table table-bordered">
        <?php
        if ($success == True) {
        ?>
        <thead>
        <tr>
            <th hidden>Course ID</th>
            <th>University</th>
            <th>Course</th>
            <th>10 Percentile GPA</th>
            <th>90 Percentile GPA</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody>
            <tr>
                <?php
                    foreach($result_JSON as $obj){
                    echo '<td hidden>' .$obj['course_id']. '</td>';
                    echo '<td>' .$obj['uni_name']. '</td>';
                    echo '<td>' .$obj['course_name']. '</td>';
                    echo '<td>' .$obj['gpa10pct']. '</td>';
                    echo '<td>' .$obj['gpa90pct']. '</td>';
                    
                ?>
                <td>
                    <a class="add" title="Add" data-toggle="tooltip"><i class="material-icons">&#xE03B;</i></a>
                    <a class="edit" title="Edit" data-toggle="tooltip"><i
                                class="material-icons">&#xE254;</i></a>
                    <a href="admin_delete.php?id=<?php echo $obj['course_id']; ?>" class="delete"
                       title="Delete" data-toggle="tooltip"><i class="material-icons">&#xE872;</i></a>
                </td>         
            </tr>
            <?php
                   }
            ?>
        <?php
        } else {
            echo "No records found";
        }
        $success = False;
        ?>
        </tbody>
    </table>
<?php
} else if ((isset($_POST['unidropdown'])) ||(isset($_POST['catdropdown']))) {

    $unidropdown = $_POST['unidropdown'];
    $catdropdown = $_POST['catdropdown'];
    
    if ($catdropdown == "Arts") {
        $catdropdown = "Arts & Culture";
    } else if ($catdropdown == "Interest") {
        $catdropdown = "Interest Clubs";
    }
    
    $record = $uni_list_collection->findOne(['uni_name'=>$unidropdown]);
    $uid = $record['uid'] ?? null;
    
    $record2 = $uni_cca_category_collection->findOne(['category_name'=>$catdropdown]);
    $catid = $record2['category_id'] ?? null;
    
    $uni_filter = ['uid'=>$uid];
    $cat_filter = ['category_id'=>$catid];
    $uni_cat_filter = ['uid' => $uid, 'category_id'=> $catid];
    
    if (($unidropdown == 'all') and ($catdropdown == 'all')) {
        //$sql = mysqli_query($conn, "SELECT c.cca_id, l.uni_name, cat.category_name, c.cca_name FROM uni_cca c, uni_list l, uni_cca_categories cat WHERE l.uid = c.uid AND cat.category_id = c.category_id");
        $result_JSON = $uni_cca_collection->find($filter, $options)->toArray();
        $cca_category_JSON = $uni_cca_category_collection->find()->toArray();
        $uni_JSON = $uni_list_collection->find()->toArray();
        foreach($result_JSON as $cca_OBJ){
            foreach($cca_category_JSON as $cca_cat_OBJ){
                if($cca_OBJ['category_id'] == $cca_cat_OBJ['category_id']){
                    $cca_OBJ['category_name'] = $cca_cat_OBJ['category_name'];
                    continue;
                }
            }
        }
        foreach($result_JSON as $cca_OBJ){
            foreach($uni_JSON as $uni_OBJ){
                if($cca_OBJ['uid']==$uni_OBJ['uid']){
                    $cca_OBJ['uni_name'] = $uni_OBJ['uni_name'];
                    continue;
                }
            }
        }
        $success = True;
        
        
        
    } else if (($unidropdown == 'all') and ($catdropdown != 'all')) {
        //$sql = mysqli_query($conn, "SELECT c.cca_id, l.uni_name, cat.category_name, c.cca_name FROM uni_cca c, uni_list l, uni_cca_categories cat WHERE l.uid = c.uid AND cat.category_id = c.category_id AND cat.category_name = '$catdropdown'");
        
        $result_JSON = $uni_cca_collection->find($cat_filter, $options)->toArray();
        $cca_category_JSON = $uni_cca_category_collection->find()->toArray();
        $uni_JSON = $uni_list_collection->find()->toArray();
        
        foreach($result_JSON as $cca_OBJ){
            foreach($cca_category_JSON as $cca_cat_OBJ){
                if($cca_OBJ['category_id'] == $cca_cat_OBJ['category_id']){
                    $cca_OBJ['category_name'] = $cca_cat_OBJ['category_name'];
                    continue;
                }
            }
        }
        foreach($result_JSON as $cca_OBJ){
            foreach($uni_JSON as $uni_OBJ){
                if($cca_OBJ['uid']==$uni_OBJ['uid']){
                    $cca_OBJ['uni_name'] = $uni_OBJ['uni_name'];
                    continue;
                }
            }
        }
        $success = True;
        
    } else if (($unidropdown != 'all') and ($catdropdown == 'all')) {
        //$sql = mysqli_query($conn, "SELECT c.cca_id, l.uni_name, cat.category_name, c.cca_name FROM uni_cca c, uni_list l, uni_cca_categories cat WHERE l.uid = c.uid AND cat.category_id = c.category_id AND l.uni_name = '$unidropdown'");
        
        $result_JSON = $uni_cca_collection->find($uni_filter, $options)->toArray();
        $cca_category_JSON = $uni_cca_category_collection->find()->toArray();
        $uni_JSON = $uni_list_collection->find()->toArray();
        
        foreach($result_JSON as $cca_OBJ){
            foreach($cca_category_JSON as $cca_cat_OBJ){
                if($cca_OBJ['category_id'] == $cca_cat_OBJ['category_id']){
                    $cca_OBJ['category_name'] = $cca_cat_OBJ['category_name'];
                    continue;
                }
            }
        }
        foreach($result_JSON as $cca_OBJ){
            foreach($uni_JSON as $uni_OBJ){
                if($cca_OBJ['uid']==$uni_OBJ['uid']){
                    $cca_OBJ['uni_name'] = $uni_OBJ['uni_name'];
                    continue;
                }
            }
        }
        $success = True;
    } else {
        //$sql = mysqli_query($conn, "SELECT c.cca_id, l.uni_name, cat.category_name, c.cca_name FROM uni_cca c, uni_list l, uni_cca_categories cat WHERE l.uid = c.uid AND cat.category_id = c.category_id AND cat.category_name = '$catdropdown' AND l.uni_name = '$unidropdown'");
        
        $result_JSON = $uni_cca_collection->find($uni_cat_filter, $options)->toArray();
        $cca_category_JSON = $uni_cca_category_collection->find()->toArray();
        $uni_JSON = $uni_list_collection->find()->toArray();
        
        foreach($result_JSON as $cca_OBJ){
            foreach($cca_category_JSON as $cca_cat_OBJ){
                if($cca_OBJ['category_id'] == $cca_cat_OBJ['category_id']){
                    $cca_OBJ['category_name'] = $cca_cat_OBJ['category_name'];
                    continue;
                }
            }
        }
        foreach($result_JSON as $cca_OBJ){
            foreach($uni_JSON as $uni_OBJ){
                if($cca_OBJ['uid']==$uni_OBJ['uid']){
                    $cca_OBJ['uni_name'] = $uni_OBJ['uni_name'];
                    continue;
                }
            }
        }
        $success = True;
    }
    ?>
    <table class="table table-bordered">
        <?php
        if ($success == True) {
        ?>
        <thead>
        <tr>
            <th hidden>CCA ID</th>
            <th>University</th>
            <th>CCA Category</th>
            <th>CCA</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody>
            <tr>
                <?php
                foreach($result_JSON as $obj){
                    echo '<td hidden>' .$obj['cca_id']. '</td>';
                    echo '<td>' .$obj['uni_name']. '</td>';
                    echo '<td>' .$obj['category_name']. '</td>';
                    echo '<td>' .$obj['cca_name']. '</td>';
                ?>
                <td>
                    <a class="add" title="Add" data-toggle="tooltip"><i class="material-icons">&#xE03B;</i></a>
                    <a class="edit" title="Edit" data-toggle="tooltip"><i
                                class="material-icons">&#xE254;</i></a>
                    <a href="admin_delete.php?id=<?php echo $obj['cca_id']; ?>" class="delete"
                       title="Delete" data-toggle="tooltip"><i class="material-icons">&#xE872;</i></a>
                </td>
            </tr>
                <?php
                }
                ?>
        <?php
        } else {
            echo "No records found";
            echo $unidropdown;
            echo $catdropdown;
        }
        ?>
        </tbody>
    </table>
    <?php
}
?>
