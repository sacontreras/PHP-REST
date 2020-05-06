<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

// include database and object files
include_once $_SERVER['DOCUMENT_ROOT'].'/DF/REST/database/db.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/DF/REST/api/objects/bookcopy.php';
  
// instantiate database and product object
$dbmgr = new LibraryDBManager();
$db = $dbmgr->getConnection();
  
// initialize object
$bookcopy = new BookCopy($db);

// get bookid if any
$bookid = @$_GET['bookid'];

$response_code = 503;
$response_json = json_encode(array("result" => "something went wrong!"));
try {
    // query book copies
    if (!empty($bookid))
        $result = $bookcopy->read($bookid);
    else
        $result = $bookcopy->read();

    // check if more than 0 record found
    if ($result->fetchArray(SQLITE3_ASSOC)) {
        $result->reset();

        // book copies array
        $bookcopies_arr = array();
        $bookcopies_arr["columns"] = array(
            array("title"=>"BookCopyID", "data"=>"BookCopyID"),
            array("title"=>"SKU", "data"=>"SKU"), 
            array("title"=>"ISBN", "data"=>"ISBN"), 
            array("title"=>"Title", "data"=>"Title"), 
            array("title"=>"Edition", "data"=>"Edition"), 
            array("title"=>"Author", "data"=>"Author"), 
            array("title"=>"PublicationDate", "data"=>"PublicationDate"), 
            array("title"=>"Cost", "data"=>"Cost")
        );
        $bookcopies_arr["records"] = array();
    
        // retrieve our table contents
        while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
            extract($row);
    
            $bookcopy_item = array(
                "BookCopyID" => $BookCopyID,
                "SKU" => $SKU,
                "ISBN" => $ISBN,
                "Title" => html_entity_decode($Title), //there may be an apostrophe, for example
                "Edition" => html_entity_decode($Edition),
                "Author" => $Author,
                "PublicationDate" => $PublicationDate,
                "Cost" => $Cost
            );
    
            array_push($bookcopies_arr["records"], $bookcopy_item);
        }
    
        $response_code = 200;
        $response_json = json_encode($bookcopies_arr);
    } else {
        $response_code = 404;
        $response_json = json_encode(array("result" => "No book copies found"));
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