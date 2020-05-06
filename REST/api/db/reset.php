<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

// include database and object files
include_once $_SERVER['DOCUMENT_ROOT'].'/PHP-REST/REST/database/db.php';

// instantiate database
$dbmgr = new LibraryDBManager();

$response_code = 503;
$response_json = json_encode(array("result" => "something went wrong!"));
try {
    if ($dbmgr->resetDB()) {
        $response_code = 201;
        $response_json = json_encode(array("result" => "database successfully reset"));
    } 
} catch (Exception $e) {
    $response_code = 503;
    $response_json = json_encode(array("result" => $e->getMessage()));
} finally {
    echo $response_json;
    http_response_code($response_code);
}
?>