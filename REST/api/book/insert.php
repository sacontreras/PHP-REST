<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// include database and object files
include_once $_SERVER['DOCUMENT_ROOT'].'/PHP-REST/REST/database/db.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/PHP-REST/REST/api/objects/book.php';

// instantiate database and product object
$dbmgr = new LibraryDBManager();
$db = $dbmgr->getConnection();
  
// initialize object
$book = new Book($db);

// get posted data
$raw_data = file_get_contents("php://input");
$data = json_decode($raw_data);

$response_code = 503;
$response_json = json_encode(array("result" => "something went wrong!"));
try {
    //expect POSTed vars: bookisbn, booktitle, bookedition, bookauthor, bookpublicationdate, and bookcost
    if (!(empty($data->bookisbn) ||
        empty($data->booktitle) ||
        empty($data->bookauthor) ||
        empty($data->bookpublicationdate) ||
        empty($data->bookcost))
    ) {
        $book->ISBN = urldecode($data->bookisbn);
        $book->Title = urldecode($data->booktitle);
        if (!empty($data->bookedition))
            $book->Edition = urldecode($data->bookedition);
        $book->Author = urldecode($data->bookauthor);
        $book->PublicationDate = urldecode($data->bookpublicationdate);
        $book->Cost = urldecode($data->bookcost);

        // insert new book
        if (@$book->insert()){
            $response_code = 201;
            $response_json = json_encode(array("result" => "new book inserted"));
        } else {
            $response_code = 503;
            $response_json = json_encode(array("result" => $db->lastErrorMsg()));
        }
    } else {// tell the user data is incomplete
        $response_code = 400;
        $response_json = json_encode(array("result" => "unable to insert new book: data is incomplete"));
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