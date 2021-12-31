<?php
//make changes accordingly to your db
$sname= "localhost";
$uname= "root";
$password = "";
$db_name = "ict2103unidb";

$conn = mysqli_connect($sname, $uname, $password, $db_name);

if (!$conn) {
    echo "Connection failed!";
}

