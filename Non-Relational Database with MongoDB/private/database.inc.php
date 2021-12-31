<?php

require 'vendor/autoload.php';
$m = new MongoDB\Client("mongodb://localhost:27017");
//echo "connection to database succesfully<br>";

$uni_cca_collection = (new MongoDB\Client)->ICT2103UniDB->Uni_cca;
$uni_list_collection = (new MongoDB\Client)->ICT2103UniDB->Uni_list;
$uni_user_collection = (new MongoDB\Client)->ICT2103UniDB->Uni_user;
$uni_cca_category_collection = (new MongoDB\Client)->ICT2103UniDB->Uni_cca_categories;
$uni_location_collection =(new MongoDB\Client)->ICT2103UniDB->Uni_location;
$uni_vacancy_collection = (new MongoDB\Client)->ICT2103UniDB->Uni_vacancies;
$uni_course_collection = (new MongoDB\Client)->ICT2103UniDB->Uni_courses;

global $uni_cca_collection, $uni_list_collection, $uni_user_collection, $uni_cca_category_collection, $uni_location_collection,
        $uni_vacancy_collection, $uni_course_collection;

$filter = [];
$options = ['sort' => ['uid' => 1]];
$success = False;

//Index: Uni_CCA
$result = $uni_cca_collection->createIndex(['category_id' => 1]);

