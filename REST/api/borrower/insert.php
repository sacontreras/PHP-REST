<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// include database and object files
include_once $_SERVER['DOCUMENT_ROOT'].'/DF/REST/database/db.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/DF/REST/api/objects/borrower.php';

// instantiate database and product object
$dbmgr = new LibraryDBManager();
$db = $dbmgr->getConnection();
  
// initialize object
$borrower = new Borrower($db);

// get posted data
$raw_data = file_get_contents("php://input");
$data = json_decode($raw_data);

$response_code = 503;
$response_json = json_encode(array("result" => "something went wrong!"));
try {
    //expect POSTed vars: borrowername, borroweraddress, borrowerpostalcode, and borrowerphonenumber
    if (!(empty($data->borrowername) ||
        empty($data->borroweraddress) ||
        empty($data->borrowerpostalcode) ||
        empty($data->borrowerphonenumber))
    ) {
        $borrower->Name = urldecode($data->borrowername);
        $borrower->Address = urldecode($data->borroweraddress);
        $borrower->PostalCode = urldecode($data->borrowerpostalcode);
        $borrower->PhoneNumber = urldecode($data->borrowerphonenumber);

        // insert new borrower
        if (@$borrower->insert()) {
            $response_code = 201;
            $response_json = json_encode(array("result" => "new borrower inserted"));
        } else {
            $response_code = 503;
            $response_json = json_encode(array("result" => $db->lastErrorMsg()));
        }
    } else {
        $response_code = 400;
        $response_json = json_encode(array("result" => "unable to insert new borrower: data is incomplete"));
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