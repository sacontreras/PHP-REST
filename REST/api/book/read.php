<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

// include database and object files
include_once $_SERVER['DOCUMENT_ROOT'].'/DF/REST/database/db.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/DF/REST/api/objects/book.php';
  
// instantiate database and product object
$dbmgr = new LibraryDBManager();
$db = $dbmgr->getConnection();
  
// initialize object
$book = new Book($db);
  
// query books
$result = $book->read();

// check if more than 0 record found
if ($result->fetchArray(SQLITE3_ASSOC)){
    $result->reset();

    // books array
    $books_arr = array();
    $books_arr["columns"] = array(
        array("title"=>"BookID", "data"=>"BookID"), 
        array("title"=>"ISBN", "data"=>"ISBN"), 
        array("title"=>"Title", "data"=>"Title"), 
        array("title"=>"Edition", "data"=>"Edition"), 
        array("title"=>"Author", "data"=>"Author"), 
        array("title"=>"PublicationDate", "data"=>"PublicationDate"), 
        array("title"=>"Cost", "data"=>"Cost")
    );
    $books_arr["records"] = array();
  
    // retrieve our table contents
    while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
        extract($row);
  
        $book_item = array(
            "BookID" => $BookID, 
            "ISBN" => $ISBN,
            "Title" => html_entity_decode($Title), //there may be an apostrophe, for example
            "Edition" => html_entity_decode($Edition),
            "Author" => $Author,
            "PublicationDate" => $PublicationDate,
            "Cost" => $Cost
        );
  
        array_push($books_arr["records"], $book_item);
    }
  
    // set response code - 200 OK
    http_response_code(200);
  
    // show products data in json format
    echo json_encode($books_arr);
} else {
  
    // set response code - 404 Not found
    http_response_code(404);
  
    // tell the user no products found
    echo json_encode(
        array("message" => "No books found.")
    );
}

$db->close();
?>