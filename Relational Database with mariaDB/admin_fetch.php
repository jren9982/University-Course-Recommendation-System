<!-- Created By Jia Jia-->
<?php
include "private/db_conn.php";
if (isset($_POST['request'])) {

    $request = $_POST['request'];
    if ($request == 'all') {
        $sql = mysqli_query($conn, "SELECT c.course_id, l.uni_name,c.course_name,c.gpa10pct,c.gpa90pct FROM uni_list l, uni_courses c
                                            WHERE l.uid = c.uid");
    } else {
        $sql = mysqli_query($conn, "SELECT c.course_id, l.uni_name,c.course_name,c.gpa10pct,c.gpa90pct FROM uni_list l, uni_courses c
                                            WHERE l.uid = c.uid AND l.uni_name = '$request'");
    }
    $count = mysqli_num_rows($sql);
    ?>
    <table class="table table-bordered">
        <?php
        if ($count) {
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
        <?php
        } else {
            echo "No records found";
        }
        ?>
        </thead>
        <tbody>
        <?php
        while ($row = mysqli_fetch_assoc($sql)) {
            ?>
            <tr>
                <td hidden><?php echo $row['course_id']; ?></td>
                <td><?php echo $row['uni_name']; ?></td>
                <td><?php echo $row['course_name']; ?></td>
                <td><?php echo $row['gpa10pct']; ?></td>
                <td><?php echo $row['gpa90pct']; ?></td>
                <td>
                    <a class="add" title="Add" data-toggle="tooltip"><i class="material-icons">&#xE03B;</i></a>
                    <a class="edit" title="Edit" data-toggle="tooltip"><i
                                class="material-icons">&#xE254;</i></a>
                    <a href="admin_delete.php?id=<?php echo $row['course_id']; ?>" class="delete"
                       title="Delete" data-toggle="tooltip"><i class="material-icons">&#xE872;</i></a>
                </td>
            </tr>
            <?php
        }
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

    if (($unidropdown == 'all') and ($catdropdown == 'all')) {
        $sql = mysqli_query($conn, "SELECT c.cca_id, l.uni_name, cat.category_name, c.cca_name FROM uni_cca c, uni_list l, uni_cca_categories cat WHERE l.uid = c.uid AND cat.category_id = c.category_id");
    } else if (($unidropdown == 'all') and ($catdropdown != 'all')) {
        $sql = mysqli_query($conn, "SELECT c.cca_id, l.uni_name, cat.category_name, c.cca_name FROM uni_cca c, uni_list l, uni_cca_categories cat WHERE l.uid = c.uid AND cat.category_id = c.category_id AND cat.category_name = '$catdropdown'");
    } else if (($unidropdown != 'all') and ($catdropdown == 'all')) {
        $sql = mysqli_query($conn, "SELECT c.cca_id, l.uni_name, cat.category_name, c.cca_name FROM uni_cca c, uni_list l, uni_cca_categories cat WHERE l.uid = c.uid AND cat.category_id = c.category_id AND l.uni_name = '$unidropdown'");
    } else {
        $sql = mysqli_query($conn, "SELECT c.cca_id, l.uni_name, cat.category_name, c.cca_name FROM uni_cca c, uni_list l, uni_cca_categories cat WHERE l.uid = c.uid AND cat.category_id = c.category_id AND cat.category_name = '$catdropdown' AND l.uni_name = '$unidropdown'");
    }
    $count = mysqli_num_rows($sql);
    ?>
    <table class="table table-bordered">
        <?php
        if ($count) {
        ?>
        <thead>
        <tr>
            <th hidden>CCA ID</th>
            <th>University</th>
            <th>CCA Category</th>
            <th>CCA</th>
            <th>Actions</th>
        </tr>
        <?php
        } else {
            echo "No records found";
        }
        ?>
        </thead>
        <tbody>
        <?php
        while ($row = mysqli_fetch_assoc($sql)) {
            ?>
            <tr>
                <td hidden><?php echo $row['cca_id']; ?></td>
                <td><?php echo $row['uni_name']; ?></td>
                <td><?php echo $row['category_name']; ?></td>
                <td><?php echo $row['cca_name']; ?></td>
                <td>
                    <a class="add" title="Add" data-toggle="tooltip"><i class="material-icons">&#xE03B;</i></a>
                    <a class="edit" title="Edit" data-toggle="tooltip"><i
                                class="material-icons">&#xE254;</i></a>
                    <a href="admin_delete.php?id=<?php echo $row['cca_id']; ?>" class="delete"
                       title="Delete" data-toggle="tooltip"><i class="material-icons">&#xE872;</i></a>
                </td>
            </tr>
            <?php
        }
        ?>
        </tbody>
    </table>
    <?php
}
?>
