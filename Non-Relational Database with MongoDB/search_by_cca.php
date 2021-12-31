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

    function fetchcca_category2(){
        
        global $errorMsg, $dbhost,$dbaccount, $dbpw, $db;
        
        $conn = new mysqli($dbhost, $dbaccount, $dbpw, $db);
        
        // important to maintain alphabetical order to conform to GROUP BY ordering for cca categories in fetchresults2();
        $result = $conn->query("SELECT * FROM uni_cca_categories ORDER BY category_name DESC;");  

        if($result->num_rows> 0){
            
            for ($row_no = ($result->num_rows) -1 ; $row_no >= 0 ; $row_no--) {

            $result->data_seek($row_no);
            $row = $result->fetch_assoc();    
            echo "<th scope=\"col\">".$row['category_name']."</th>";
            }
        }
    }
    function fetchresults2(){
        //filters
        $condition_ccaname = sanitize_input($_POST['ccaname']);
        $condition_location = sanitize_input($_POST['location2']);
        $condition_school = sanitize_input($_POST['school2']);
        $condition_cca_category_id = sanitize_input($_POST['cca2']);
        // echo $condition_ccaname.$condition_location.$condition_cca_category_id;
        //merge & filter tables(Json objects)
        global $uni_location_collection, $uni_list_collection, $uni_cca_collection, $uni_cca_category_collection;
        global $uni_vacancy_collection, $uni_course_collection;
        $uni_location_JSON = $uni_location_collection -> find() ->toArray();
        $uni_list_JSON = $uni_list_collection -> find() ->toArray();
        $cca_list_JSON = $uni_cca_collection -> find() ->toArray();
        $cca_category_JSON = $uni_cca_category_collection -> find() ->toArray();
        $uni_vacancy_JSON = $uni_vacancy_collection -> find() ->toArray();
        $uni_courses_JSON = $uni_course_collection ->find() ->toArray();

        //init mergedJSON and start filtering it based on dropdown conditions.
        $mergedJSON = $cca_list_JSON;
        foreach($mergedJSON as $ccaObj){
            //inner join with cca categories table
            foreach($cca_category_JSON as $catObj){
                if($ccaObj['category_id'] == $catObj['category_id']){
                    $ccaObj['category_name'] = $catObj['category_name'];
                    continue;
                }
            }
            //inner join with location table
            foreach($uni_location_JSON as $locationObj){
                if($ccaObj['uid'] == $locationObj['uid']){
                    $ccaObj['location'] = $locationObj['location'];
                    $ccaObj['lid'] = $locationObj['lid'];
                    continue;
                }
            }
             // inner join with school name table(Uni_list)
             foreach($uni_list_JSON as $schoolObj){
                if($ccaObj['uid'] == $schoolObj['uid']){
                    $ccaObj['uni_name'] = $schoolObj['uni_name'];
                    continue;
                }
            }
        }

        // init a temp json to filter it with dropdown options.
        $filteredJSON = $mergedJSON;
        foreach($filteredJSON as $key => $obj){
            if($condition_location !== "All"){
                if($obj['lid'] != $condition_location){
                    unset($filteredJSON[$key]);
                }
            }
            if($condition_ccaname !== ""){
                if(!str_contains(strtolower($obj['cca_name']),strtolower($condition_ccaname))){
                    unset($filteredJSON[$key]);
                }
                
            }
            if($condition_cca_category_id !== "All"){
                if($obj['category_id'] != $condition_cca_category_id ){
                    unset($filteredJSON[$key]);
                }
            }
        }
        //generate table
        echo "<table class=\"table\">";
        echo "<tr>
              <th scope=\"col\">CCA ID</th>
              <th scope=\"col\">CCA Name</th>
              <th scope=\"col\">CCA Category</th>
              <th scope=\"col\">School</th>
              <th scope=\"col\">Location</th>
              </tr>";
        
        //remove duplicate by key:cca_names objs
        // $taken = array();
        // foreach($filteredJSON as $key => $item) {
        //     if(!in_array($item['cca_name'], $taken)) {
        //          $taken[] = $item['cca_name'];
        //     } 
        //     else {
        //        unset($filteredJSON[$key]);
        //     }
        // }
        //form result table
        foreach($filteredJSON as $obj){
            echo "<tr><td>".$obj['cca_id']."</td><td>".$obj['cca_name']."</td><td>".$obj['category_name']."</td><td>".$obj['uni_name']."</td><td>".$obj['location']."</td></tr>";
        }
        echo "</table>";
         //   AGGREGATION SECTION //

        //create collection and store filtered result table
        $db = (new MongoDB\Client)->ICT2103UniDB;
        $db->dropCollection('tempResultCCA',[]);
        $db->createCollection('tempResultCCA', []);
        $tempCollection = (new MongoDB\Client)->ICT2103UniDB->tempResultCCA;
        foreach($filteredJSON as $obj){
            $insertmany = $tempCollection -> insertOne($obj);
        }
        $totalRows = $tempCollection ->count();
        

        //reusable function to get total category counts
        function getCategoryCount($category,$tempCollection){
            $totalCount = $tempCollection ->count(
                ["category_name"=>$category]
            );
            return $totalCount;
        }
        $totalArts = getCategoryCount("Arts & Culture",$tempCollection);
        $totalIClub = getCategoryCount("Interest Clubs",$tempCollection);
        $totalReligion = getCategoryCount("Religion",$tempCollection);
        $totalSports = getCategoryCount("Sports",$tempCollection);

        echo "<table class=\"table\">";
                echo "<tr>
                          <th scope=\"col\">Total Count</th>
                          <th scope=\"col\">Arts & Culture</th>
                          <th scope=\"col\">Interest Clubs</th>
                          <th scope=\"col\">Religion</th>
                          <th scope=\"col\">Sports</th>
                     </tr>";

                echo "<tr><td>".
                     $totalRows."</td><td>".
                     $totalArts."</td><td>".
                     $totalIClub."</td><td>".
                     $totalReligion."</td><td>".
                     $totalSports.
                     "</td></tr>";
                    
                
        echo "</table>";


        
    
    }
    function fetchresults2_old(){
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

                $stmt_agg = $conn->prepare("SELECT subquery.cca_category as category_name, COUNT(*) as cnt FROM (SELECT a.cca_id AS cca_id, a.cca_name AS cca_name, c.category_name AS cca_category, b.uni_name AS uni_name, d.location AS uni_location FROM uni_cca AS a INNER JOIN uni_list AS b ON a.uid = b.uid INNER JOIN uni_cca_categories AS c ON a.category_id = c.category_id INNER JOIN uni_location AS d on b.lid = d.lid WHERE a.category_id LIKE ? AND a.uid = b.uid AND b.uid LIKE ? AND a.cca_name LIKE ? AND d.lid LIKE ?) AS subquery GROUP BY subquery.cca_category");
                $stmt_agg->bind_param("ssss", $cca_category, $school, $ccaname, $location);
            }
                
            //if only cca name is null
            else if(empty($_POST['ccaname']) && !empty($_POST['school2']) && !empty($_POST['cca2']) && !empty($_POST['location2'])){
                
                // $stmt = $conn->prepare("SELECT a.cca_id, a.cca_name, b.uni_name, d.location FROM uni_cca AS a INNER JOIN uni_list AS b ON a.uid = b.uid INNER JOIN uni_cca_categories AS c ON a.category_id = c.category_id INNER JOIN uni_location AS d on b.lid = d.lid WHERE a.category_id LIKE ? AND a.uid = b.uid AND d.lid LIKE ? ;");
                $stmt = $conn->prepare("SELECT a.cca_id, a.cca_name, c.category_name, b.uni_name, d.location FROM uni_cca AS a INNER JOIN uni_list AS b ON a.uid = b.uid INNER JOIN uni_cca_categories AS c ON a.category_id = c.category_id INNER JOIN uni_location AS d on b.lid = d.lid WHERE a.category_id LIKE ? AND a.uid = b.uid AND b.uid LIKE ? AND d.lid LIKE ?;");
                $stmt->bind_param("sss", $cca_category, $school, $location);

                // remember to drop zerotable and cca_agg

                $conn->query("DROP TABLE IF EXISTS zero_table");
                $conn->query("CREATE TABLE zero_table (category_name varchar(255), cnt int)");
                $conn->query("INSERT INTO zero_table (category_name) SELECT category_name FROM uni_cca_categories");
                $conn->query("UPDATE zero_table SET cnt = 0");

                $conn->query("DROP TABLE IF EXISTS cca_agg");
                $stmt_agg = $conn->prepare("CREATE TABLE cca_agg AS (SELECT subquery.cca_category as category_name, COUNT(*) as cnt FROM (SELECT a.cca_id AS cca_id, a.cca_name AS cca_name, c.category_name AS cca_category, b.uni_name AS uni_name, d.location AS uni_location FROM uni_cca AS a INNER JOIN uni_list AS b ON a.uid = b.uid INNER JOIN uni_cca_categories AS c ON a.category_id = c.category_id INNER JOIN uni_location AS d on b.lid = d.lid WHERE a.category_id LIKE ? AND a.uid = b.uid AND b.uid LIKE ? AND d.lid LIKE ?) AS subquery GROUP BY subquery.cca_category)");
                $stmt_agg->bind_param("sss", $cca_category, $school, $location);
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

            if ($stmt_agg->execute()) {
                echo "<table class=\"table\">";
                echo "<tr> <th scope=\"col\">Total Count</th>";
                fetchcca_category2(); // populates title row with cca categories in DB
                echo "</tr>";
                $result = $conn->query("SELECT category_name, cnt FROM (SELECT category_name, cnt FROM cca_agg UNION ALL SELECT category_name, cnt FROM zero_table) AS s GROUP BY s.category_name");
                $result_sum = $conn->query("SELECT SUM(subquery.cnt) FROM (SELECT category_name, cnt FROM (SELECT category_name, cnt FROM cca_agg UNION ALL SELECT category_name, cnt FROM zero_table) AS s GROUP BY s.category_name) AS subquery");
                if($result->num_rows === 0 | $result_sum->num_rows === 0){
                    echo "<tr>No CCA found</tr>";
                }
                else{
                    echo "<tr>";
                    $row_sum = $result_sum->fetch_assoc();
                    echo "<td>".$row_sum['SUM(subquery.cnt)']."</td>";
                    while ($row = $result->fetch_assoc()) {
                        echo "<td>".$row['cnt']."</td>";
                    }
                    echo "</tr>";
                }
                echo "</table>";
                $stmt_agg->close();      
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
            document.getElementById("filterbtn2").style.display = "none";
            
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
                
                // bottom block is to allow data to presist for cca only filter
                if(isset($_POST['ccaname'])){
                    
                    
                     echo "$( \"#ccaname\" ).val(\"".$_POST['ccaname']."\" );";
                     
                    //echo "$( \"#cca\" ).val(\"All\" );";
                }
                if(isset($_POST['school2'])){
                     echo "$( \"#school2\" ).val(\"".$_POST['school2']."\" );";
                    //  echo "showcca();";
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
                                    <h2>Suggested CCAs</h2>
                                </div>    
                            </div>
                        </div>
                    </header>
                    
                    <!-- <button class="btn" id="ccabtn" name="ccabtn"  onclick="showcca()">Search For CCA Instead</button> -->
                    <!-- <div id="ccaextension" name="ccaextension" style="display: none;"> -->
                    <div id="ccaextension" name="ccaextension">
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
                                    fetchresults2();
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