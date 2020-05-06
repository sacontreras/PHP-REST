<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

// include database and object files
include_once $_SERVER['DOCUMENT_ROOT'].'/PHP-REST/REST/database/db.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/PHP-REST/REST/api/objects/librarian.php';
  
// instantiate database and product object
$dbmgr = new LibraryDBManager();
$db = $dbmgr->getConnection();
  
// initialize object
$librarian = new Librarian($db);
  
// query librarians
$result = $librarian->read();

// check if more than 0 record found
if ($result->fetchArray(SQLITE3_ASSOC)){
    $result->reset();

    // librarians array
    $librarians_arr = array();
    $librarians_arr["columns"] = array(
        array("title"=>"LibrarianID", "data"=>"LibrarianID"), 
        array("title"=>"Name", "data"=>"Name"), 
        array("title"=>"PhoneNumber", "data"=>"PhoneNumber")
    );
    $librarians_arr["records"] = array();
  
    // retrieve our table contents
    while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
        extract($row);
  
        $librarian_item = array(
            "LibrarianID" => $LibrarianID,
            "Name" => $Name,
            "PhoneNumber" => $PhoneNumber
        );
  
        array_push($librarians_arr["records"], $librarian_item);
    }
  
    // set response code - 200 OK
    http_response_code(200);
  
    // show librarianbs data in json format
    echo json_encode($librarians_arr);
} else {
    // set response code - 404 Not found
    http_response_code(404);
  
    // tell the user no librarians found
    echo json_encode(
        array("message" => "No librarians found.")
    );
}

$db->close();
?>