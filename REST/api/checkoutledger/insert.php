<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// include database and object files
include_once $_SERVER['DOCUMENT_ROOT'].'/PHP-REST/REST/database/db.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/PHP-REST/REST/api/objects/checkoutledger.php';

// instantiate database and product object
$dbmgr = new LibraryDBManager();
$db = $dbmgr->getConnection();
  
// initialize object
$checkoutledger = new CheckoutLedger($db);

// get posted data
$raw_data = file_get_contents("php://input");
$data = json_decode($raw_data);

$response_code = 503;
$response_json = json_encode(array("result" => "something went wrong!"));
try {
    //expect POSTed vars: librarianid, checkoutdate, librarycardid, bookid, and bookcopyid
    if (!(
        empty($data->librarianid) || 
        empty($data->checkoutdate) ||
        empty($data->librarycardid) ||
        empty($data->bookid) ||
        empty($data->bookcopyid)
    )) {
        $checkoutledger->LibrarianID = urldecode($data->librarianid);
        $checkoutledger->CheckoutDate = urldecode($data->checkoutdate);
        $checkoutledger->DueDate = @strtotime('+2 weeks', date('Y-m-d', strtotime($data->checkoutdate)));
        $checkoutledger->LibraryCardID = urldecode($data->librarycardid);
        $checkoutledger->BookCopyID = urldecode($data->bookcopyid);

        // insert new book
        if (@$checkoutledger->insert()){
            $response_code = 201;
            $response_json = json_encode(array("result" => "new checkout ledger entry inserted"));
        } else {
            $response_code = 503;
            $response_json = json_encode(array("result" => $db->lastErrorMsg()));
        }
    } else {// tell the user data is incomplete
        $response_code = 400;
        $response_json = json_encode(array("result" => "unable to insert new checkout ledger entry: data is incomplete"));
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