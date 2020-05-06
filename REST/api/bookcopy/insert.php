<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// include database and object files
include_once $_SERVER['DOCUMENT_ROOT'].'/PHP-REST/REST/database/db.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/PHP-REST/REST/api/objects/bookcopy.php';

// instantiate database and product object
$dbmgr = new LibraryDBManager();
$db = $dbmgr->getConnection();
  
// initialize object
$bookcopy = new BookCopy($db);

// get posted data
$raw_data = file_get_contents("php://input");
$data = json_decode($raw_data);

$response_code = 503;
$response_json = json_encode(array("result" => "something went wrong!"));
try {
    //expect POSTed vars: bookid and bookcopysku
    if (!(empty($data->bookid) || empty($data->bookcopysku))) {
        $bookcopy->BookID = urldecode($data->bookid);
        $bookcopy->SKU = urldecode($data->bookcopysku);

        // insert new book
        if (@$bookcopy->insert()){
            $response_code = 201;
            $response_json = json_encode(array("result" => "new book copy inserted"));
        } else {
            $response_code = 503;
            $response_json = json_encode(array("result" => $db->lastErrorMsg()));
        }
    } else {// tell the user data is incomplete
        $response_code = 400;
        $response_json = json_encode(array("result" => "unable to insert new book copy: data is incomplete"));
    }
} catch (Exception $e) {
    $response_code = 503;
    $response_json = json_encode(array("result" => $e->getMessage()));
} finally {
    echo $response_json;
    http_response_code($response_code);
}

$db->close();
?>