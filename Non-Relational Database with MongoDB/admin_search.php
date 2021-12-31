<?php
ob_start();
session_start();
include "private/database.inc.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php
    include "head.inc.php";
    ?>
    <title>ICT 2103 University</title>
</head>
<body>
<?php
include "nav.inc.php";
?>
<div class="container">
    <div class="table-wrapper">
        <div class="table-title">
            <div class="row">
                <div class="col-sm-8"><h2>University Course <b>Details</b></h2></div>
                <form class="d-flex" action="admin_search.php" method="post">
                    <input class="form-control mr-sm-2" type="text" name="search"
                           value="<?php if (isset($_POST['search'])) {
                               echo $_POST['search'];
                           } ?>" id="search" class="form-control"
                           size="23" placeholder="Search Course..." >
                    <button type="submit" class="btn btn-outline-primary">Search</button>
                    <!--<input type="button" class="btn btn-outline-primary" value="Search" name="submitcca">-->
                </form>
                <div class="col-sm-12"></div>
                <br>
                <div class="col-sm-8"></div>
                <span class="filter-icon"><i class="fa fa-filter"></i></span>
                <label>Uni Type</label>
                <div class="col-md-2">
                    <select class="form-control" name="unidropdown" id="unidropdown">
                        <option value="all">All University</option>
                        <?php
                        $uni_list_rows = $uni_list_collection->find();
                        foreach($uni_list_rows as $row){
                            echo "<option value=" . $row['uni_name'] . ">" . $row['uni_name'] . "</option>";
                        }
                        ?>
                    </select>
                </div>
                <button type="button" class="btn btn-info add-new" data-target='#adduni' data-toggle='modal'><i
                            class="fa fa-plus"></i> Add New
                </button>
            </div>
        </div>

        <div id="container">
            <table class="table table-bordered">
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
                <?php
                if (isset($_POST['search'])) {

                $search = $_POST['search'];
                $success = True;
                if ($search == "") {
                    header("Location: admin.php");
                    exit();
                }
                ?>
                    <tr>
                    <?php
                    if($success == True){
                    $result_JSON = $uni_course_collection->find(array('course_name'=>array('$regex'=>$search)), $options)->toArray();
                    $uni_JSON = $uni_list_collection->find()->toArray();
                    foreach($result_JSON as $course_OBJ){
                        foreach($uni_JSON as $uni_OBJ){
                            if($course_OBJ['uid'] == $uni_OBJ['uid']){
                                $course_OBJ['uni_name'] = $uni_OBJ['uni_name'];
                                continue;
                            }
                        }
                    }
                    
                    foreach($result_JSON as $obj){
                        echo '<td hidden>' .$obj['course_id']. '</td>';
                        echo '<td>' .$obj['uni_name']. '</td>';
                        echo '<td>' .$obj['course_name']. '</td>';
                        echo '<td>' .$obj['gpa10pct']. '</td>';
                        echo '<td>' .$obj['gpa90pct']. '</td>';
                    ?>
                        <td>
                            <a class="edit" title="Edit" data-toggle='modal'><i class="material-icons">&#xE254;</i></a>
                            <a href="admin_delete.php?id=<?php echo $obj['course_id']; ?>" class="delete"
                               title="Delete" data-toggle="tooltip"><i class="material-icons">&#xE872;</i></a>
                        </td>
                    </tr>
                        <?php
                    }
                } else {
                    echo "No records found";
                }
                ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Add Modal HTML -->
<div id="adduni" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="admin_add.php" method="post">
                <div class="modal-header">
                    <h4 class="modal-title">Add University Course</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="course">University: </label>
                        <select class="form-control" id="uni" name="uni" required>
                            <option value="NUS">NUS</option>
                            <option value="NTU">NTU</option>
                            <option value="SMU">SMU</option>
                        </select>
                        <br>
                        <label for="course">Course Name: </label>
                        <input type="text" id="cname" name="cname" size="35" required><br>
                        <label for="10pct">10 Percentile: </label>
                        <input type="text" id="10pct" name="10pct" maxlength="4" size="10" required><br>
                        <label for="90pct">90 Percentile: </label>
                        <input type="text" id="90pct" name="90pct" maxlength="4" size="10" required><br>
                    </div>
                </div>
                <div class="modal-footer">
                    <input type="button" class="btn btn-default" data-dismiss="modal" value="Cancel">
                    <button type="submit" class="btn btn-info" name="add" id="add">Add Course</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Modal HTML -->
<div id="updateuni" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="admin_update.php" method="post">
                <div class="modal-header">
                    <h4 class="modal-title">Edit University Course</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="row" id="row">
                    <div class="form-group">
                        <label for="course">University: </label>
                        <select class="form-control" id="euni" name="euni" required>
                            <option value="NUS">NUS</option>
                            <option value="NTU">NTU</option>
                            <option value="SMU">SMU</option>
                        </select>
                        <br>
                        <label for="course">Course Name: </label>
                        <input type="text" id="ecname" name="ecname" size="35" required><br>
                        <label for="10pct">10 Percentile: </label>
                        <input type="text" id="e10pct" name="e10pct" maxlength="4" size="10" required><br>
                        <label for="90pct">90 Percentile: </label>
                        <input type="text" id="e90pct" name="e90pct" maxlength="4" size="10" required><br>
                    </div>
                </div>
                <div class="modal-footer">
                    <input type="button" class="btn btn-default" data-dismiss="modal" value="Cancel">
                    <button type="submit" class="btn btn-info" name="update" id="update">Edit Course</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $("#unidropdown").on('change', function () {
            var value = $(this).val();

            $.ajax({
                url: "admin_fetch.php",
                type: "POST",
                data: "request=" + value,
                success: function (data) {
                    $("#container").html(data);
                }
            })
        });
    });

    $(document).ready(function () {
        $(document).on('click', '.edit', function(){
            $('#updateuni').modal('show');
            $tr =$(this).closest('tr');
            var data = $tr.children("td").map(function (){
                return $(this).text();
            }).get();
            console.log(data);

            $('#row').val(data[0]);
            $('#euni').val(data[1]);
            $('#ecname').val(data[2]);
            $('#e10pct').val(data[3]);
            $('#e90pct').val(data[4]);
        });
    });
</script>

</body>
</html>
<?php
}
?>
