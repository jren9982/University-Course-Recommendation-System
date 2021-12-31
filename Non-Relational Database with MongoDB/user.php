<?php
    
    global $errorMsg, $dbhost,$dbaccount, $dbpw, $db;
    
    //set the db connection info here
    include "private/database.inc.php";
    
    
    //Helper function that checks input for malicious or unwanted content.
    function sanitize_input($data) {
        
       $data = trim($data);
       $data = stripslashes($data);
       $data = htmlspecialchars($data);
       return $data;
       
    }
    
    function fetchlocation(){
        global $uni_location_collection;
        $rows = $uni_location_collection -> find();
        foreach($rows as $row) {
            echo "<option value=\"".$row["lid"]."\">".$row["location"]."</option>";
        };
    }
    function fetchschool(){
        global $uni_list_collection;
        $rows = $uni_list_collection -> find();
        foreach($rows as $row) {
            echo "<option value=\"".$row["uid"]."\">".$row["uni_name"]."</option>";
        };
    }
    function fetchcca(){
        global $uni_cca_collection;
        $rows = $uni_cca_collection -> find();
        foreach($rows as $row) {
            echo "<option value=\"".$row["cca_id"]."\">".$row["cca_name"]."</option>";
        };
    }
    function fetchcca_category(){
        global $uni_cca_category_collection;
        $rows = $uni_cca_category_collection -> find();
        foreach($rows as $row) {
            echo "<option value=\"".$row["category_id"]."\">".$row["category_name"]."</option>";
        };
    }
    function fetchresults(){
        $condition_gpa = sanitize_input($_POST['gpa']);
        $condition_location = sanitize_input($_POST['location']);
        $condition_school = sanitize_input($_POST['school']);
        $condition_ccaCat = sanitize_input($_POST['cca']);
        $sort = $_POST['sort'];
        
        // echo $condition_gpa.$condition_school.$condition_location.$condition_ccaCat.$sort;
        global $uni_location_collection, $uni_list_collection, $uni_cca_collection, $uni_cca_category_collection;
        global $uni_vacancy_collection, $uni_course_collection;
        $uni_location_JSON = $uni_location_collection -> find() ->toArray();
        $uni_list_JSON = $uni_list_collection -> find() ->toArray();
        $cca_list_JSON = $uni_cca_collection -> find() ->toArray();
        $cca_category_JSON = $uni_cca_category_collection -> find() ->toArray();
        $uni_vacancy_JSON = $uni_vacancy_collection -> find() ->toArray();
        $uni_courses_JSON = $uni_course_collection ->find() ->toArray();
        // $dist_catid = $uni_cca_collection ->distinct('category_id', ['uid' => 2]);

        //error prevention: check dropdown fields have values & sort dropdown has a value to aggregate.
        // if (!(isset($_POST['gpa']) && isset($_POST['location']) && isset($_POST['cca']) && isset($_POST['sort']))) return;

        //init mergedJSON and start filtering it based on dropdown conditions.
        $mergedJSON = $uni_courses_JSON;
        foreach($mergedJSON as $courseObj){
            //inner join with vancancies table
            foreach($uni_vacancy_JSON as $vacancyObj){
                if($courseObj['course_id'] == $vacancyObj['course_id']){
                    $courseObj['vacancies'] = $vacancyObj['vacancies'];
                    continue;
                }
            }
            //inner join with location table
            foreach($uni_location_JSON as $locationObj){
                if($courseObj['uid'] == $locationObj['uid']){
                    $courseObj['location'] = $locationObj['location'];
                    $courseObj['lid'] = $locationObj['lid'];
                    continue;
                }
            }
            // inner join with school name table(Uni_list)
            foreach($uni_list_JSON as $schoolObj){
                if($courseObj['uid'] == $schoolObj['uid']){
                    $courseObj['uni_name'] = $schoolObj['uni_name'];
                    continue;
                }
            }
            // join with cca table to get array of categories
            foreach($cca_list_JSON as $ccaObj){
                if($courseObj['uid'] == $ccaObj['uid']){
                    //store array of cca categories
                    $courseObj['catIds'] = $uni_cca_collection ->distinct('category_id', ['uid' => $courseObj['uid']]);
                    // var_dump($courseObj['catIds']);
                    continue;
                }
            }
        }
        // init a temp json to filter it with dropdown options.
        $filteredJSON = $mergedJSON;
        foreach($filteredJSON as $key => $obj){
            if($condition_school !== "All"){

                if($obj['uid'] != $condition_school){
                    unset($filteredJSON[$key]);
                }
            }
            if($condition_location !== "All"){
                if($obj['lid'] != $condition_location){
                    unset($filteredJSON[$key]);
                }
            }
            if($condition_gpa !== ""){
                if(($condition_gpa < $obj['gpa10pct'])){
                    unset($filteredJSON[$key]);
                }
            }
            if($condition_ccaCat !== "All"){
                //1 uni has multiple cca categories 
                //if row's university does not have Category in dropdown,
                if(!in_array($condition_ccaCat, $obj['catIds'])){
                    unset($filteredJSON[$key]);
                }
            }
        }
        switch($sort) {
            case "10asc":
                usort($filteredJSON, function ($item1, $item2) {
                    return $item1['gpa10pct'] <=> $item2['gpa10pct'];
                });
                break;
            case "10desc":
                usort($filteredJSON, function ($item1, $item2) {
                    return $item2['gpa10pct'] <=> $item1['gpa10pct'];
                });
                break;
            case "90asc":
                usort($filteredJSON, function ($item1, $item2) {
                    return $item1['gpa90pct'] <=> $item2['gpa90pct'];
                });
                break;
            case "90desc":
                usort($filteredJSON, function ($item1, $item2) {
                    return $item2['gpa90pct'] <=> $item1['gpa90pct'];
                });
                break;
            case "vasc":
                usort($filteredJSON, function ($item1, $item2) {
                    return $item1['vacancies'] <=> $item2['vacancies'];
                });
                break;
            case "vdesc":
                usort($filteredJSON, function ($item1, $item2) {
                    return $item2['vacancies'] <=> $item1['vacancies'];
                });
                break;
            default:
        }
        //generate table
        echo "<table class=\"table\">";
        echo "<tr>
            <th scope=\"col\">Course ID</th>
            <th scope=\"col\">Course Name</th>
            <th scope=\"col\">School</th>
            <th scope=\"col\">10th Percentile GPA</th>
            <th scope=\"col\">90th Percentile GPA</th>
            <th scope=\"col\">vacancies</th>
             </tr>";
        foreach($filteredJSON as $obj){
            echo "<tr><td>".$obj['course_id']."</td><td>".$obj['course_name']."</td><td>".$obj['uni_name']."</td><td>".$obj['gpa10pct']."</td><td>".$obj['gpa90pct']."</td><td>".$obj['vacancies']."</td></tr>";
        }
        echo "</table>";
        //   AGGREGATION SECTION //

        //create collection and store filtered result table
        $db = (new MongoDB\Client)->ICT2103UniDB;
        $db->dropCollection('tempResult',[]);
        $db->createCollection('tempResult', []);
        $tempCollection = (new MongoDB\Client)->ICT2103UniDB->tempResult;
        $insertManyResult = $tempCollection->insertMany($filteredJSON);
        $totalRows = $tempCollection ->count();
        // Aggregation for #gpa10pct
        $result = $tempCollection->aggregate(
            [
                ['$match' =>['gpa10pct'=> [ '$not'=> [ '$eq'=> 'NUL' ] ]]],
                [ '$sort' => [ 'gpa10pct' => 1 ] ],
            ],
        ) ->toArray();
        $minGpa10 = $result[0]['gpa10pct'];

        $result = $tempCollection ->aggregate(
            [
                ['$match' =>['gpa10pct'=> [ '$not'=> [ '$eq'=> 'NUL' ] ]]],
                ['$group'=>['_id'=>null,'avg' => ['$avg' =>['$toDouble'=>'$gpa10pct']]]]
            ]
        ) ->toArray();
        $meanGpa10;
        foreach($result as $obj){
            $meanGpa10 = $obj['avg'];
        }

        $result = $tempCollection->aggregate(
            [
                ['$match' =>['gpa10pct'=> [ '$not'=> [ '$eq'=> 'NUL' ] ]]],
                [ '$sort' => [ 'gpa10pct' => -1 ] ],
            ],
        ) ->toArray();
        $maxGpa10 = $result[0]['gpa10pct'];
        //end of min/mean/max Aggregation #gpa10pct
         // Aggregation for #gpa 90%
         $result = $tempCollection->aggregate(
            [
                ['$match' =>['gpa90pct'=> [ '$not'=> [ '$eq'=> 'NUL' ] ]]],
                [ '$sort' => [ 'gpa90pct' => 1 ] ],
            ],
        ) ->toArray();
        $minGpa90 = $result[0]['gpa90pct'];

        $result = $tempCollection ->aggregate(
            [
                ['$match' =>['gpa90pct'=> [ '$not'=> [ '$eq'=> 'NUL' ] ]]],
                ['$group'=>['_id'=>null,'avg' => ['$avg' =>['$toDouble'=>'$gpa90pct']]]]
            ]
        ) ->toArray();
        $meanGpa90;
        foreach($result as $obj){
            $meanGpa90 = $obj['avg'];
        }

        $result = $tempCollection->aggregate(
            [
                ['$match' =>['gpa90pct'=> [ '$not'=> [ '$eq'=> 'NUL' ] ]]],
                [ '$sort' => [ 'gpa90pct' => -1 ] ],
            ],
        ) ->toArray();
        $maxGpa90 = $result[0]['gpa90pct'];
        //end of min/mean/max Aggregation #gpa90%
        //aggregate $sum Vacancies column
        $result = $tempCollection ->aggregate(
            [
                ['$match' =>['vacancies'=> [ '$not'=> [ '$eq'=> 'NUL' ] ]]],
                ['$group'=>['_id'=>null,'totalVacancy' => ['$sum' =>['$toDouble'=>'$vacancies']]]]
            ]
        ) ->toArray();
        foreach($result as $obj){$totalVacancy = $obj['totalVacancy'];}



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

                echo "<tr><td>".
                     $totalRows."</td><td>".
                     $minGpa10."</td><td>".
                     $meanGpa10."</td><td>".
                     $maxGpa10."</td><td>".
                     $minGpa90."</td><td>".
                     $meanGpa90."</td><td>".
                     $maxGpa90."</td><td>".
                     $totalVacancy.
                     "</td></tr>";
                    
                
        echo "</table>";


        
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
                                    fetchresults();
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