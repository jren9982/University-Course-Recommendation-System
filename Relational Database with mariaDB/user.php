<?php
    
    global $errorMsg, $dbhost,$dbaccount, $dbpw, $db;
    
    //set the db connection info here
    include "connection_settings.php";
    
    
    //Helper function that checks input for malicious or unwanted content.
    function sanitize_input($data) {
        
       $data = trim($data);
       $data = stripslashes($data);
       $data = htmlspecialchars($data);
       return $data;
       
    }
    
    function fetchlocation(){
        global $errorMsg, $dbhost,$dbaccount, $dbpw, $db;
        $conn = new mysqli($dbhost, $dbaccount, $dbpw, $db);
        
        $result = $conn->query("SELECT * FROM uni_location;");
        if($result->num_rows> 0){
            
        
            for ($row_no = ($result->num_rows) -1 ; $row_no >= 0 ; $row_no--) {

                $result->data_seek($row_no);
                $row = $result->fetch_assoc();    
                echo "<option value=\"".$row["lid"]."\">".$row["location"]."</option>";

            }
        }
        else{
            echo "Error in <br>".$conn->error;
        }
        
        
    }
    function fetchschool(){
        global $errorMsg, $dbhost,$dbaccount, $dbpw, $db;
        $conn = new mysqli($dbhost, $dbaccount, $dbpw, $db);
        
        $result = $conn->query("SELECT * FROM uni_list;");
        if($result->num_rows> 0){
            
        
            for ($row_no = ($result->num_rows) -1 ; $row_no >= 0 ; $row_no--) {

                $result->data_seek($row_no);
                $row = $result->fetch_assoc();    
                echo "<option value=\"".$row["uid"]."\">".$row["uni_name"]."</option>";

            }
        }
        else{
            echo "Error in <br>".$conn->error;
        }
        
        
    }
    function fetchcca(){
        
        global $errorMsg, $dbhost,$dbaccount, $dbpw, $db;
        
        if(!isset($_POST['school']) || $_POST['school'] == 'All'){
            $conn = new mysqli($dbhost, $dbaccount, $dbpw, $db);
            
            $result = $conn->query("SELECT * FROM uni_cca;");
            if($result->num_rows> 0){


                for ($row_no = ($result->num_rows) -1 ; $row_no >= 0 ; $row_no--) {

                    $result->data_seek($row_no);
                    $row = $result->fetch_assoc();    
                    echo "<option value=\"".$row["cca_id"]."\">".$row["cca_name"]."</option>";

                }
            }
        }
    }
    function fetchcca_category(){
        
        global $errorMsg, $dbhost,$dbaccount, $dbpw, $db;
        
        $conn = new mysqli($dbhost, $dbaccount, $dbpw, $db);
            
        $result = $conn->query("SELECT * FROM uni_cca_categories;");
        if($result->num_rows> 0){
            
        for ($row_no = ($result->num_rows) -1 ; $row_no >= 0 ; $row_no--) {

            $result->data_seek($row_no);
            $row = $result->fetch_assoc();    
            echo "<option value=\"".$row['category_id']."\">".$row['category_name']."</option>";

                }
            }
    }
    function fetchresults(){
        global $errorMsg, $dbhost,$dbaccount, $dbpw, $db;
        
        $conn = new mysqli($dbhost, $dbaccount, $dbpw, $db);
        
        if (isset($_POST['gpa']) && isset($_POST['school']) && isset($_POST['location']) && isset($_POST['cca'])) {

            $gpa = sanitize_input($_POST['gpa']);
            $school = sanitize_input($_POST['school']);
            $schoollocation = sanitize_input($_POST['location']);
            $cca_category = sanitize_input($_POST['cca']);

            if($school == "All") {
                $school = "%";
            }
            if($schoollocation == "All"){
                $schoollocation = "%";
            }
            if($cca_category == "All"){
                $cca_category = "%";
            }
            
            //if all values are not null ,not empty and sort set to 10th Percentile ascending
            if(!empty($_POST['gpa']) && !empty($_POST['school']) && !empty($_POST['location']) && !empty($_POST['cca']) && !empty($_POST['sort'])){
                
                if($_POST['sort'] == '10asc'){
                    $stmt = $conn->prepare("SELECT a.course_id, c.uni_name, a.course_name, a.gpa10pct, a.gpa90pct, b.vacancies FROM uni_courses AS a INNER JOIN uni_vacancies AS b ON b.course_id = a.course_id AND a.gpa10pct >= ? AND a.uid IN (SELECT DISTINCT uid FROM uni_cca WHERE category_id like ?) INNER JOIN uni_list AS c ON a.uid = c.uid INNER JOIN uni_location AS d WHERE c.lid= d.lid AND d.lid LIKE ? AND c.uid LIKE ? AND  a.gpa10pct != 'NUL' AND a.gpa90pct != 'NUL' ORDER BY a.gpa10pct ASC;");
                }
                else if ($_POST['sort'] == '10desc'){
                    $stmt = $conn->prepare("SELECT a.course_id, c.uni_name, a.course_name, a.gpa10pct, a.gpa90pct, b.vacancies FROM uni_courses AS a INNER JOIN uni_vacancies AS b ON b.course_id = a.course_id AND a.gpa10pct >= ? AND a.uid IN (SELECT DISTINCT uid FROM uni_cca WHERE category_id like ?) INNER JOIN uni_list AS c ON a.uid = c.uid INNER JOIN uni_location AS d WHERE c.lid= d.lid AND d.lid LIKE ? AND c.uid LIKE ? AND a.gpa10pct != 'NUL' AND a.gpa90pct != 'NUL' ORDER BY a.gpa10pct DESC;");
                }
                else if ($_POST['sort'] == '90asc'){
                    $stmt = $conn->prepare("SELECT a.course_id, c.uni_name, a.course_name, a.gpa10pct, a.gpa90pct, b.vacancies FROM uni_courses AS a INNER JOIN uni_vacancies AS b ON b.course_id = a.course_id AND a.gpa10pct >= ? AND a.uid IN (SELECT DISTINCT uid FROM uni_cca WHERE category_id like ?) INNER JOIN uni_list AS c ON a.uid = c.uid INNER JOIN uni_location AS d WHERE c.lid= d.lid AND d.lid LIKE ? AND c.uid LIKE ? AND a.gpa10pct != 'NUL' AND a.gpa90pct != 'NUL' ORDER BY a.gpa90pct ASC;");
                }
                else if ($_POST['sort'] == '90desc'){
                    $stmt = $conn->prepare("SELECT a.course_id, c.uni_name, a.course_name, a.gpa10pct, a.gpa90pct, b.vacancies FROM uni_courses AS a INNER JOIN uni_vacancies AS b ON b.course_id = a.course_id AND a.gpa10pct >= ? AND a.uid IN (SELECT DISTINCT uid FROM uni_cca WHERE category_id like ?) INNER JOIN uni_list AS c ON a.uid = c.uid INNER JOIN uni_location AS d WHERE c.lid= d.lid AND d.lid LIKE ? AND c.uid LIKE ? AND a.gpa10pct != 'NUL' AND a.gpa90pct != 'NUL' ORDER BY a.gpa90pct DESC;");
                }
                else if ($_POST['sort'] == 'vasc'){
                    $stmt = $conn->prepare("SELECT a.course_id, c.uni_name, a.course_name, a.gpa10pct, a.gpa90pct, b.vacancies FROM uni_courses AS a INNER JOIN uni_vacancies AS b ON b.course_id = a.course_id AND a.gpa10pct >= ? AND a.uid IN (SELECT DISTINCT uid FROM uni_cca WHERE category_id like ?) INNER JOIN uni_list AS c ON a.uid = c.uid INNER JOIN uni_location AS d WHERE c.lid= d.lid AND d.lid LIKE ? AND c.uid LIKE ? AND a.gpa10pct != 'NUL' AND a.gpa90pct != 'NUL' ORDER BY b.vacancies ASC;");
                }
                else if ($_POST['sort'] == 'vdesc'){
                    $stmt = $conn->prepare("SELECT a.course_id, c.uni_name, a.course_name, a.gpa10pct, a.gpa90pct, b.vacancies FROM uni_courses AS a INNER JOIN uni_vacancies AS b ON b.course_id = a.course_id AND a.gpa10pct >= ? AND a.uid IN (SELECT DISTINCT uid FROM uni_cca WHERE category_id like ?) INNER JOIN uni_list AS c ON a.uid = c.uid INNER JOIN uni_location AS d WHERE c.lid= d.lid AND d.lid LIKE ? AND c.uid LIKE ? AND a.gpa10pct != 'NUL' AND a.gpa90pct != 'NUL' ORDER BY b.vacancies DESC;");
                }
                // else if ($_POST['sort'] == 'uni'){
                //     $stmt = $conn->prepare("SELECT a.course_id, c.uni_name, a.course_name, a.gpa10pct, a.gpa90pct, b.vacancies FROM uni_courses AS a INNER JOIN uni_vacancies AS b ON b.course_id = a.course_id AND a.gpa10pct >= ? AND a.uid IN (SELECT DISTINCT uid FROM uni_cca WHERE category_id like ?) INNER JOIN uni_list AS c ON a.uid = c.uid INNER JOIN uni_location AS d WHERE c.lid= d.lid AND d.lid LIKE ? AND c.uid LIKE ? AND a.gpa10pct != 'NUL' AND a.gpa90pct != 'NUL' ORDER BY a.uid ASC;");
                // }
                $stmt->bind_param("dsss", $gpa, $cca_category, $schoollocation, $school);

                $stmt_agg = $conn->prepare("SELECT COUNT(*) as cnt, MIN(gpa10pct), CAST(AVG(gpa10pct) AS DECIMAL(10,2)) AS AVGgpa10pct, MAX(gpa10pct), MIN(gpa90pct), CAST(AVG(gpa90pct) AS DECIMAL(10,2)) AS AVGgpa90pct, MAX(gpa90pct), SUM(vacancies) FROM (SELECT a.course_id AS id, c.uni_name AS uni_name, a.course_name AS course_name, a.gpa10pct AS gpa10pct, a.gpa90pct AS gpa90pct, b.vacancies AS vacancies FROM uni_courses AS a INNER JOIN uni_vacancies AS b ON b.course_id = a.course_id AND a.gpa10pct >= ? AND a.uid IN (SELECT DISTINCT uid FROM uni_cca WHERE category_id like ?) INNER JOIN uni_list AS c ON a.uid = c.uid INNER JOIN uni_location AS d WHERE c.lid= d.lid AND d.lid LIKE ? AND c.uid LIKE ? AND  a.gpa10pct != 'NUL' AND a.gpa90pct != 'NUL' ORDER BY a.gpa10pct ASC) AS SUBQUERY;");
                $stmt_agg->bind_param("dsss", $gpa, $cca_category, $schoollocation, $school);
            }
                
            //if only gpa is null
            else if(empty($_POST['gpa']) && !empty($_POST['school']) && !empty($_POST['location']) && !empty($_POST['cca']) && !empty($_POST['sort'])){
                
                //$stmt = $conn->prepare("SELECT a.course_id, a.course_name, c.uni_name, a.gpa10pct, a.gpa90pct, b.vacancies FROM uni_courses AS a INNER JOIN uni_vacancies AS b ON b.course_id = a.course_id AND a.uid LIKE ?  AND a.uid IN (SELECT DISTINCT uid FROM uni_cca WHERE category_id LIKE ?)INNER JOIN uni_list as c on a.uid = c.uid WHERE gpa10pct != 'NUL' AND gpa90pct != 'NUL' ORDER BY b.vacancies DESC, a.uid ASC, a.gpa10pct ASC;");
                
                if($_POST['sort'] == '10asc'){
                    $stmt = $conn->prepare("SELECT a.course_id, c.uni_name, a.course_name, a.gpa10pct, a.gpa90pct, b.vacancies FROM uni_courses AS a INNER JOIN uni_vacancies AS b ON b.course_id = a.course_id AND a.gpa10pct >= 0 AND a.uid IN (SELECT DISTINCT uid FROM uni_cca WHERE category_id like ?) INNER JOIN uni_list AS c ON a.uid = c.uid INNER JOIN uni_location AS d WHERE c.lid= d.lid AND d.lid LIKE ? AND c.uid LIKE ? AND a.gpa10pct != 'NUL' AND a.gpa90pct != 'NUL' ORDER BY a.gpa10pct ASC;");
                }
                else if ($_POST['sort'] == '10desc'){
                    $stmt = $conn->prepare("SELECT a.course_id, c.uni_name, a.course_name, a.gpa10pct, a.gpa90pct, b.vacancies FROM uni_courses AS a INNER JOIN uni_vacancies AS b ON b.course_id = a.course_id AND a.gpa10pct >= 0 AND a.uid IN (SELECT DISTINCT uid FROM uni_cca WHERE category_id like ?) INNER JOIN uni_list AS c ON a.uid = c.uid INNER JOIN uni_location AS d WHERE c.lid= d.lid AND d.lid LIKE ? AND c.uid LIKE ? AND a.gpa10pct != 'NUL' AND a.gpa90pct != 'NUL' ORDER BY a.gpa10pct DESC;");
                }
                else if ($_POST['sort'] == '90asc'){
                    $stmt = $conn->prepare("SELECT a.course_id, c.uni_name, a.course_name, a.gpa10pct, a.gpa90pct, b.vacancies FROM uni_courses AS a INNER JOIN uni_vacancies AS b ON b.course_id = a.course_id AND a.gpa10pct >= 0 AND a.uid IN (SELECT DISTINCT uid FROM uni_cca WHERE category_id like ?) INNER JOIN uni_list AS c ON a.uid = c.uid INNER JOIN uni_location AS d WHERE c.lid= d.lid AND d.lid LIKE ? AND c.uid LIKE ? AND a.gpa10pct != 'NUL' AND a.gpa90pct != 'NUL' ORDER BY a.gpa90pct ASC;");
                }
                else if ($_POST['sort'] == '90desc'){
                    $stmt = $conn->prepare("SELECT a.course_id, c.uni_name, a.course_name, a.gpa10pct, a.gpa90pct, b.vacancies FROM uni_courses AS a INNER JOIN uni_vacancies AS b ON b.course_id = a.course_id AND a.gpa10pct >= 0 AND a.uid IN (SELECT DISTINCT uid FROM uni_cca WHERE category_id like ?) INNER JOIN uni_list AS c ON a.uid = c.uid INNER JOIN uni_location AS d WHERE c.lid= d.lid AND d.lid LIKE ? AND c.uid LIKE ? AND a.gpa10pct != 'NUL' AND a.gpa90pct != 'NUL' ORDER BY a.gpa90pct DESC;");
                }
                else if ($_POST['sort'] == 'vasc'){
                    $stmt = $conn->prepare("SELECT a.course_id, c.uni_name, a.course_name, a.gpa10pct, a.gpa90pct, b.vacancies FROM uni_courses AS a INNER JOIN uni_vacancies AS b ON b.course_id = a.course_id AND a.gpa10pct >= 0 AND a.uid IN (SELECT DISTINCT uid FROM uni_cca WHERE category_id like ?) INNER JOIN uni_list AS c ON a.uid = c.uid INNER JOIN uni_location AS d WHERE c.lid= d.lid AND d.lid LIKE ? AND c.uid LIKE ? AND a.gpa10pct != 'NUL' AND a.gpa90pct != 'NUL' ORDER BY b.vacancies ASC;");
                }
                else if ($_POST['sort'] == 'vdesc'){
                    $stmt = $conn->prepare("SELECT a.course_id, c.uni_name, a.course_name, a.gpa10pct, a.gpa90pct, b.vacancies FROM uni_courses AS a INNER JOIN uni_vacancies AS b ON b.course_id = a.course_id AND a.gpa10pct >= 0 AND a.uid IN (SELECT DISTINCT uid FROM uni_cca WHERE category_id like ?) INNER JOIN uni_list AS c ON a.uid = c.uid INNER JOIN uni_location AS d WHERE c.lid= d.lid AND d.lid LIKE ? AND c.uid LIKE ? AND a.gpa10pct != 'NUL' AND a.gpa90pct != 'NUL' ORDER BY b.vacancies DESC;");
                }
                // else if ($_POST['sort'] == 'uni'){
                //     $stmt = $conn->prepare("SELECT a.course_id, c.uni_name, a.course_name, a.gpa10pct, a.gpa90pct, b.vacancies FROM uni_courses AS a INNER JOIN uni_vacancies AS b ON b.course_id = a.course_id AND a.gpa10pct >= 0 AND a.uid IN (SELECT DISTINCT uid FROM uni_cca WHERE category_id like ?) INNER JOIN uni_list AS c ON a.uid = c.uid INNER JOIN uni_location AS d WHERE c.lid= d.lid AND d.lid LIKE ? AND c.uid LIKE ? AND a.gpa10pct != 'NUL' AND a.gpa90pct != 'NUL' ORDER BY a.uid ASC;");
                // }
                $stmt->bind_param("sss", $cca_category, $schoollocation, $school);
                $stmt_agg = $conn->prepare("SELECT COUNT(*) as cnt, MIN(gpa10pct), CAST(AVG(gpa10pct) AS DECIMAL(10,2)) AS AVGgpa10pct, MAX(gpa10pct), MIN(gpa90pct), CAST(AVG(gpa90pct) AS DECIMAL(10,2)) AS AVGgpa90pct, MAX(gpa90pct), SUM(vacancies) FROM (SELECT a.course_id AS id, c.uni_name AS uni_name, a.course_name AS course_name, a.gpa10pct AS gpa10pct, a.gpa90pct AS gpa90pct, b.vacancies AS vacancies FROM uni_courses AS a INNER JOIN uni_vacancies AS b ON b.course_id = a.course_id AND a.gpa10pct >= 0 AND a.uid IN (SELECT DISTINCT uid FROM uni_cca WHERE category_id like ?) INNER JOIN uni_list AS c ON a.uid = c.uid INNER JOIN uni_location AS d WHERE c.lid= d.lid AND d.lid LIKE ? AND c.uid LIKE ? AND a.gpa10pct != 'NUL' AND a.gpa90pct != 'NUL' ORDER BY a.gpa10pct ASC) AS SUBQUERY;");
                $stmt_agg->bind_param("sss", $cca_category, $schoollocation, $school);
            }   
            ////if only enddate is null
            //else if(!empty($_POST['startdate']) && empty($_POST['enddate']) && !empty($_POST['leavetype']) && !empty($_POST['leavestatus'])){

            //    $stmt = $conn->prepare("SELECT leaveid, startdate, enddate, leavetype, leavestatus FROM leavestable WHERE empid = ? AND startdate >= ? AND enddate >= ? AND leavetype LIKE ? AND leavestatus LIKE ?;");
            //    $stmt->bind_param("issss", $_SESSION["empid"], $startdate, $currentdate, $leavetype, $leavestatus);
                    
            //}
               
            //if both startdate and enddate is null
            //else if(empty($_POST['startdate']) && empty($_POST['enddate']) && !empty($_POST['leavetype']) && !empty($_POST['leavestatus'])){
            //    $stmt = $conn->prepare("SELECT leaveid, startdate, enddate, leavetype, leavestatus FROM leavestable WHERE empid = ? AND enddate >= ? AND leavetype LIKE ? AND leavestatus LIKE ?;");
            //    $stmt->bind_param("isss", $_SESSION["empid"], $currentdate, $leavetype, $leavestatus);
            //}
            if ($stmt->execute()) {
                
                echo "<table class=\"table\">";
                echo "<tr>
                          <th scope=\"col\">Course ID</th>
                          <th scope=\"col\">Course Name</th>
                          <th scope=\"col\">School</th>
                          <th scope=\"col\">10th Percentile GPA</th>
                          <th scope=\"col\">90th Percentile GPA</th>
                          <th scope=\"col\">vacancies</th>
                     </tr>";
                    
                $result = $stmt->get_result();
                if($result->num_rows === 0){
                    echo "<tr><td>No Courses found</td></tr>";
                }
                else{
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr><td>".$row['course_id']."</td><td>".$row['course_name']."</td><td>".$row['uni_name']."</td><td>".$row['gpa10pct']."</td><td>".$row['gpa90pct']."</td><td>".$row['vacancies']."</td></tr>";
                    }
                }
                echo "</table>";
                $stmt->close();
                }
                else{
                    $errormsg= "Error with command";
                }
                //mysqli_close($conn);
            }
            if ($stmt_agg->execute()) {
                
                echo "<table class=\"table\">";
                echo "<tr>
                          <th scope=\"col\">Total Count</th>
                          <th scope=\"col\">Min GPA 10Pct</th>
                          <th scope=\"col\">Mean GPA 10Pct</th>
                          <th scope=\"col\">Max GPA 10Pct</th>
                          <th scope=\"col\">Min GPA 90Pct</th>
                          <th scope=\"col\">Mean GPA 90Pct</th>
                          <th scope=\"col\">Max GPA 90Pct</th>
                          <th scope=\"col\">Total Vacancies</th>
                     </tr>";
                    
                $result = $stmt_agg->get_result();
                if($result->num_rows === 0){
                    echo "<tr>No Aggregates found</tr>";
                }
                else{
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr><td>".$row['cnt']."</td><td>".$row['MIN(gpa10pct)']."</td><td>".$row['AVGgpa10pct']."</td><td>".$row['MAX(gpa10pct)']."</td><td>".$row['MIN(gpa90pct)']."</td><td>".$row['AVGgpa90pct']."</td><td>".$row['MAX(gpa90pct)']."</td><td>".$row['SUM(vacancies)']."</td></tr>";
                    }
                }
                echo "</table>";
                $stmt_agg->close();
                    
            }
            else{
                $errormsg= "Error with command";
            }
            mysqli_close($conn);
            
    }
    function fetchresults2(){
        global $errorMsg, $dbhost,$dbaccount, $dbpw, $db;
        
        $conn = new mysqli($dbhost, $dbaccount, $dbpw, $db);
        
        if (isset($_POST['ccaname']) && isset($_POST['school2']) && isset($_POST['cca2']) && isset($_POST['location2'])) {

            $ccaname = "%".sanitize_input($_POST['ccaname'])."%";
            $school = sanitize_input($_POST['school2']);
            $cca_category = sanitize_input($_POST['cca2']);
            $location = sanitize_input($_POST['location2']);
            
                
            if($school == "All"){
                $school = "%";
            }
            if($cca_category == "All"){
                $cca_category = "%";
            }
            if($location == "All"){
                $location = "%";
            }
                
            
            //if all values are not null 
            if(!empty($_POST['ccaname']) && !empty($_POST['school2']) && !empty($_POST['cca2']) && !empty($_POST['location2'])){
                
                // $stmt = $conn->prepare("SELECT a.cca_id, a.cca_name, b.uni_name, d.location FROM uni_cca AS a INNER JOIN uni_list AS b ON a.uid = b.uid INNER JOIN uni_cca_categories AS c ON a.category_id = c.category_id INNER JOIN uni_location AS d on b.lid = d.lid WHERE a.category_id LIKE ? AND a.uid = b.uid AND d.lid LIKE ? AND a.cca_name LIKE ?;");
                $stmt = $conn->prepare("SELECT a.cca_id, a.cca_name, c.category_name, b.uni_name, d.location FROM uni_cca AS a INNER JOIN uni_list AS b ON a.uid = b.uid INNER JOIN uni_cca_categories AS c ON a.category_id = c.category_id INNER JOIN uni_location AS d on b.lid = d.lid WHERE a.category_id LIKE ? AND a.uid = b.uid AND b.uid LIKE ? AND a.cca_name LIKE ? AND d.lid LIKE ?;");
                $stmt->bind_param("ssss", $cca_category, $school, $ccaname, $location);
            }
                
            //if only cca name is null
            else if(empty($_POST['ccaname']) && !empty($_POST['school2']) && !empty($_POST['cca2']) && !empty($_POST['location2'])){
                
                // $stmt = $conn->prepare("SELECT a.cca_id, a.cca_name, b.uni_name, d.location FROM uni_cca AS a INNER JOIN uni_list AS b ON a.uid = b.uid INNER JOIN uni_cca_categories AS c ON a.category_id = c.category_id INNER JOIN uni_location AS d on b.lid = d.lid WHERE a.category_id LIKE ? AND a.uid = b.uid AND d.lid LIKE ? ;");
                $stmt = $conn->prepare("SELECT a.cca_id, a.cca_name, c.category_name, b.uni_name, d.location FROM uni_cca AS a INNER JOIN uni_list AS b ON a.uid = b.uid INNER JOIN uni_cca_categories AS c ON a.category_id = c.category_id INNER JOIN uni_location AS d on b.lid = d.lid WHERE a.category_id LIKE ? AND a.uid = b.uid AND b.uid LIKE ? AND d.lid LIKE ?;");
                $stmt->bind_param("sss", $cca_category, $school, $location);
            }   
            
            if ($stmt->execute()) {
                
                
                
                echo "<table class=\"table\">";
                echo "<tr>
                          <th scope=\"col\">CCA ID</th>
                          <th scope=\"col\">CCA Name</th>
                          <th scope=\"col\">CCA Category</th>
                          <th scope=\"col\">School</th>
                          <th scope=\"col\">Location</th>
                     </tr>";
                    
                $result = $stmt->get_result();
                if($result->num_rows === 0){
                    echo "<tr>No CCA found</tr>";
                }
                else{
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr><td>".$row['cca_id']."</td><td>".$row['cca_name']."</td><td>".$row['category_name']."</td><td>".$row['uni_name']."</td><td>".$row['location']."</td></tr>";
                    }
                }
                echo "</table>";
                $stmt->close();
                    
                }
                else{
                    $errormsg= "Error with command";
                }
                mysqli_close($conn);
        }
            
    }
?>
<!doctype html>
<html lang="en">
    <head>
        <?php include "header.php"; ?>
        <script>
        $(document).ready(function(){
            document.getElementById("filterbtn").style.display = "none";
            document.getElementById("filterbtn2").style.display = "none";
             
             
             //this block triggers the onclick for the filter
            $('#gpa').change(function() {
                document.getElementById("filterbtn").click();
            });
            $('#school').change(function() {
               document.getElementById("filterbtn").click();
            });
            $('#location').change(function() {
                document.getElementById("filterbtn").click();
            });
            $('#cca').change(function() {
                document.getElementById("filterbtn").click();
            });
            $('#sort').change(function() {
                document.getElementById("filterbtn").click();
            });
            
            // for the cca block
            $('#ccalocation').change(function() {
                document.getElementById("filterbtn2").click();
            });
            $('#cca2').change(function() {
                document.getElementById("filterbtn2").click();
            });
            $('#school2').change(function() {
                document.getElementById("filterbtn2").click();
            });
            $('#location2').change(function() {
                document.getElementById("filterbtn2").click();
            });
            
            
            
            <?php
                // bottom block is to allow data to presist
                if (isset($_POST['gpa'])) {
                    echo "$( \"#gpa\" ).val(\"".$_POST['gpa']."\" );";
                }
                if (isset($_POST['school'])) {
                   echo "$( \"#school\" ).val(\"".$_POST['school']."\" );";
                }
                if(isset($_POST['location'])){
                     echo "$( \"#location\" ).val(\"".$_POST['location']."\" );";
                }
                if(isset($_POST['cca'])){
                    
                    
                     echo "$( \"#cca\" ).val(\"".$_POST['cca']."\" );";
                    //echo "$( \"#cca\" ).val(\"All\" );";
                }
                if(isset($_POST['sort'])){
                    
                    
                     echo "$( \"#sort\" ).val(\"".$_POST['sort']."\" );";
                     
                    //echo "$( \"#cca\" ).val(\"All\" );";
                }
                
                // bottom block is to allow data to presist for cca only filter
                if(isset($_POST['ccaname'])){
                    
                    
                     echo "$( \"#ccaname\" ).val(\"".$_POST['ccaname']."\" );";
                     
                    //echo "$( \"#cca\" ).val(\"All\" );";
                }
                if(isset($_POST['school2'])){
                     echo "$( \"#school2\" ).val(\"".$_POST['school2']."\" );";
                     echo "showcca();";
                }
                if(isset($_POST['cca2'])){
                    
                    
                     echo "$( \"#cca2\" ).val(\"".$_POST['cca2']."\" );";
                    //echo "$( \"#cca\" ).val(\"All\" );";
                }
                if(isset($_POST['location2'])){
                    
                    
                    echo "$( \"#location2\" ).val(\"".$_POST['location2']."\" );";
                   //echo "$( \"#cca\" ).val(\"All\" );";
               }
                
            ?>
            
            
            
        });
         function showcca() {
            var x = document.getElementById("ccaextension");
            if (x.style.display === "none") {
            x.style.display = "block";
            } else {
            x.style.display = "none";
            }
            }
        
         
        </script>
    </head>
    <body class="bg-secondary">
        
        <?php include "navbar.php"; ?>
        <main>
            
            
            <!--<div class="container" >-->
                <div class="bg-light rounded mt-1 container">
                    <header>
                        <div class="container">
                            <div class="text-left">
                                <div class="mt-4 row col-md-12 text-center">
                                    <h2>Suggested Courses</h2>
                                </div>    
                            </div>
                        </div>
                    </header>
                    
                    <form role="form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                        <table class = "table">
                            <tr>
                                <th scope="col">GPA</th>
                                <th scope="col">School</th>
                                <th scope="col">Location </th>
                                <th scope="col">CCA Category</th>
                                <th scope="col">Sort By :</th>
                            </tr>

                            <tr>

                                <td><input type = "text" class="form-control" name = "gpa" id = "gpa"/></td>
                                
                                <td>
                                    <select class="form-control" name="school" id="school">
                                        <option value="All">All</option>
                                        <?php fetchschool();?>
                                    </select>
                                </td>
                                

                                <td>
                                    <select class="form-control" name="location" id="location">
                                        <option value="All">All</option>
                                        <?php fetchlocation(); ?>
                                    </select>
                                </td>

                                <td>
                                    <select class="form-control" name="cca" id="cca">
                                        <option value="All">All</option>
                                        <?php fetchcca_category() ?>
                                    </select>
                                </td>
                                <td>
                                    <select class="form-control" name="sort" id="sort">
                                        <option value="10asc">10th Percentile (ascending)</option>
                                        <option value="10desc">10th Percentile (descending)</option>
                                        <option value="90asc">90th Percentile (ascending)</option>
                                        <option value="90desc">90th Percentile (descending)</option>
                                        <option value="vasc">Vacancies (ascending)</option>
                                        <option value="vdesc">Vacancies (descending)</option>
                                        <!-- <option value="uni">University Names (alphabetical)</option> -->
                                    </select>
                                </td>

                            </tr>

                        </table>
                        <button type="submit" name="filterbtn" id="filterbtn"></button>
                    
                    <?php
                        if ($_SERVER["REQUEST_METHOD"] == "POST") {   
                            if (isset($_POST['gpa']) && isset($_POST['school']) && isset($_POST['location']) && isset($_POST['cca'])) {
                                
                                $conn = new mysqli($dbhost, $dbaccount, $dbpw, $db);
                                if ($conn->connect_error) {
                                    $errorMsg = "Database conenction error";
                                }
                                else{

                                    fetchresults();
                                    //echo"tried query";
                                }
                            }
                        }


                    ?>
                    </form>
                    <button class="btn" id="ccabtn" name="ccabtn"  onclick="showcca()">Search For CCA Instead</button>
                    <div id="ccaextension" name="ccaextension" style="display: none;">
                    <form role="form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                        <table class = "table">
                            <tr>
                                <th scope="col">CCA Name</th>
                                <th scope="col">CCA Category</th>
                                <th scope="col">School</th>
                                <th scope="col">Location</th>
                                <!--<th scope="col">Sort By :</th>-->
                            </tr>

                            <tr>

                                <td><input type = "text" class="form-control" name = "ccaname" id = "ccaname"/></td>

                                <td>
                                    <select class="form-control" name="cca2" id="cca2">
                                        <option value="All">All</option>
                                        <?php fetchcca_category() ?>
                                    </select>
                                </td>
                                
                                <td>
                                    <select class="form-control" name="school2" id="school2">
                                        <option value="All">All</option>
                                        <?php fetchschool(); ?>
                                    </select>
                                </td>

                                <td>
                                    <select class="form-control" name="location2" id="location2">
                                        <option value="All">All</option>
                                        <?php fetchlocation(); ?>
                                    </select>
                                </td>

                            </tr>

                        </table>
                        <button type="submit" name="filterbtn2" id="filterbtn2"></button>
                    
                    <?php
                        if ($_SERVER["REQUEST_METHOD"] == "POST") {   
                            if (isset($_POST['ccaname']) && isset($_POST['school2']) && isset($_POST['cca2']) && isset($_POST['location2'])) {
                                
                                $conn = new mysqli($dbhost, $dbaccount, $dbpw, $db);
                                if ($conn->connect_error) {
                                    $errorMsg = "Database conenction error";
                                }
                                else{

                                    fetchresults2();
                                    //echo"tried query";
                                }
                            }
                        }


                    ?>
                    </form>
                    </div>
                </div>
            
        <!--</div>-->
            
            
        </main>
    
    
  </body>
</html>