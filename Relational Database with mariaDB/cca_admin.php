<!-- Created By Jia Jia-->
<?php
include 'private/db_conn.php';

session_start();
if (isset($_SESSION['email']) && isset($_SESSION['name'])) {
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
                    <div class="col-sm-8"><h2>University CCA <b>Details</b></h2></div>
                    <form class="d-flex" action="admin_cca_search.php" method="post">
                        <input class="form-control mr-sm-2" type="text" name="ccasearch" value="<?php if(isset($_POST['ccasearch'])){echo $_POST['ccasearch'];} ?>" id="ccasearch" class="form-control"
                               size="23" placeholder="Search CCA..." required>
                        <button type="submit" class="btn btn-outline-primary">Search</button>
                    </form>
                    <div class="col-sm-12"></div><br>
                    <div class="col-sm-5"></div>
                    <span class="filter-icon"><i class="fa fa-filter"></i></span>
                    <label>Uni Type</label>
                    <div class="col-md-2">
                        <select class="form-control" name="unidropdown" id="unidropdown">
                            <option value="all">All University</option>
                            <?php
                            $sql = mysqli_query($conn, "SELECT uni_name FROM uni_list");
                            if ($sql->num_rows > 0) {
                                while ($row = mysqli_fetch_assoc($sql)) {
                                    echo "<option value=" . $row['uni_name'] . ">" . $row['uni_name'] . "</option>";
                                    //echo "<option value=\"uni\">" . $row['uni_name'] . "</option>";
                                }
                            }
                            ?>
                        </select>
                    </div>
                    <span class="filter-icon"><i class="fa fa-filter"></i></span>
                    <label>CCA Type</label>
                    <div class="col-md-2">
                        <select class="form-control" name="catdropdown" id="catdropdown">
                            <option value="all">All Category</option>
                            <?php
                            $sql = mysqli_query($conn, "SELECT category_name FROM uni_cca_categories");
                            if ($sql->num_rows > 0) {
                                while ($row = mysqli_fetch_assoc($sql)) {
                                    echo "<option value=" . $row['category_name'] . ">" . $row['category_name'] . "</option>";
                                    //echo "<option value=\"uni\">" . $row['uni_name'] . "</option>";
                                }
                            }
                            ?>
                        </select>
                    </div>
                    <button type="button" class="btn btn-info add-new" data-target='#addcca' data-toggle='modal'><i
                                class="fa fa-plus"></i> Add New
                    </button>
                </div>
            </div>
            <div id="container">
                <table class="table table-bordered">
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
                    <?php
                    //Fetch Data form database
                    $sql = mysqli_query($conn, "SELECT c.cca_id, l.uni_name, cat.category_name, c.cca_name FROM uni_cca c, uni_list l, uni_cca_categories cat WHERE l.uid = c.uid AND cat.category_id = c.category_id");
                    if ($sql->num_rows > 0) {
                        while ($row = mysqli_fetch_assoc($sql)) {
                            ?>
                            <tr>
                                <td hidden><?php echo $row['cca_id']; ?></td>
                                <td><span id='uname'><?php echo $row['uni_name']; ?></span></td>
                                <td><span id='catname'><?php echo $row['category_name']; ?></span></td>
                                <td><span id='cname'><?php echo $row['cca_name']; ?></span></td>
                                <td>
                                    <a class="edit" title="Edit" data-toggle='modal'><i
                                                class="material-icons">&#xE254;</i></a>
                                    <a href="admin_cca_delete.php?id=<?php echo $row['cca_id']; ?>" class="delete"
                                       title="Delete" data-toggle="tooltip"><i class="material-icons">&#xE872;</i></a>
                                </td>
                            </tr>
                            <?php
                        }
                    } else {
                        ?>
                        <?php
                    }
                    ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Add Modal HTML -->
    <div id="addcca" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="admin_add.php" method="post">
                    <div class="modal-header">
                        <h4 class="modal-title">Add CCA</h4>
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
                            <label for="course">CCA Category: </label>
                            <select class="form-control" id="catname" name="catname" required>
                                <option value="Arts & Culture">Arts & Culture</option>
                                <option value="Sports">Sports</option>
                                <option value="Religion">Religion</option>
                                <option value="Interest Clubs">Interest Clubs</option>
                            </select>
                            <br><label for="cname">CCA: </label><br>
                            <input type="text" id="cname" name="cname" size="50" required><br>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <input type="button" class="btn btn-default" data-dismiss="modal" value="Cancel">
                        <button type="submit" class="btn btn-info" name="addcca" id="addcca">Add CCA</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Modal HTML -->
    <div id="updatecca" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="admin_update.php" method="post">
                    <div class="modal-header">
                        <h4 class="modal-title">Edit University CCA</h4>
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
                            <label for="course">CCA Category: </label>
                            <select class="form-control" id="ecat" name="ecat" required>
                                <option value="Arts & Culture">Arts & Culture</option>
                                <option value="Sports">Sports</option>
                                <option value="Religion">Religion</option>
                                <option value="Interest Clubs">Interest Clubs</option>
                            </select>
                            <br><label for="ecca">CCA: </label><br>
                            <input type="text" id="ecca" name="ecca" size="50" required><br>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <input type="button" class="btn btn-default" data-dismiss="modal" value="Cancel">
                        <button type="submit" class="btn btn-info" name="updatecca" id="updatecca">Edit CCA</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script type="text/javascript">
        $(document).ready(function () {
            $("#unidropdown, #catdropdown").on('change', function () {
                var unidropdown = $("#unidropdown").val();
                var catdropdown = $("#catdropdown").val();

                $.ajax({
                    url: "admin_fetch.php",
                    type: "POST",
                    data: {
                        unidropdown: unidropdown,
                        catdropdown: catdropdown
                    },
                    success: function (data) {
                        $("#container").html(data);
                    }
                })
            });
        });

        $(document).ready(function () {
            $(document).on('click', '.edit', function () {
                $('#updatecca').modal('show');
                $tr = $(this).closest('tr');
                var data = $tr.children("td").map(function () {
                    return $(this).text();
                }).get();
                console.log(data);

                $('#row').val(data[0]);
                $('#euni').val(data[1]);
                $('#ecat').val(data[2]);
                $('#ecca').val(data[3]);
            });
        });
    </script>

    </body>
    </html>
    <?php
} else {
    header("Location: logout.php");
    exit();
}
?>