<script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.20/css/jquery.dataTables.min.css"/>

<script src="./lib/RESTclient.js"></script>
<script>
function handleRESTAPIReadResponse(restAPIResponseJSON, obj, verb) {
    const datetimestamp = new Date(); 
    const libraryDataLabelText = `Library data last requested ${recurrent ? "RECURRENTLY" : "ONCE"} via API <b>${buildRESTEndpoint(obj, verb)}</b> at <strong>${datetimestamp.getFullYear()}/${datetimestamp.getMonth()+1}/${datetimestamp.getDate()} @ ${datetimestamp.getHours()}:${datetimestamp.getMinutes()}:${datetimestamp.getSeconds()}</strong>, RAW JSON Response:`;
    const libraryReadDataLabel = document.getElementById("libraryReadDataLabel");
    libraryReadDataLabel.innerHTML = libraryDataLabelText;
    const rawReadResponseJSONContainer = document.getElementById("rawReadResponseJSONContainer");
    rawReadResponseJSONContainer.innerHTML = jsonResponseToString(restAPIResponseJSON);

    const libraryReadDataTableLabel = document.getElementById("libraryReadDataTableLabel");
    libraryReadDataTableLabel.innerHTML = `<u>${obj}</u> Data Grid:`;
    const libraryReadDataTableContainer = document.getElementById("libraryReadDataTableContainer");
    libraryReadDataTableContainer.innerHTML = "<table id='libraryReadDataTable' class='display' style='width:100%''><tr><td>No Results</td></tr></table>";
    var tbl = $("#libraryReadDataTable")
    tbl.DataTable ({
        "data" : restAPIResponseJSON["records"],
        "columns" : restAPIResponseJSON["columns"]
    });
}

function handleRESTAPIReadError(xhr, obj, verb) {
    const datetimestamp = new Date(); 
    const libraryDataLabelText = `Library data last requested ${recurrent ? "RECURRENTLY" : "ONCE"} via API <b>${buildRESTEndpoint(obj, verb)}</b> at <strong>${datetimestamp.getFullYear()}/${datetimestamp.getMonth()+1}/${datetimestamp.getDate()} @ ${datetimestamp.getHours()}:${datetimestamp.getMinutes()}:${datetimestamp.getSeconds()}</strong>, RAW JSON Response:`;
    const libraryReadDataLabel = document.getElementById("libraryReadDataLabel");
    libraryReadDataLabel.innerHTML = libraryDataLabelText;
    const rawReadResponseJSONContainer = document.getElementById("rawReadResponseJSONContainer");
    rawReadResponseJSONContainer.innerHTML = 'Error ' + xhr.status +  (xhr.response != null ? ': ' + jsonResponseToString(xhr.response) : '');
}
</script>

<br>
<h2>READ APIs available:</h2>
<br>
<label>GET</label>
<select id="RESTAPIobj">
    <option value="borrower">Borrowers</option>
    <option value="librarian">Librarian</option>
    <option value="book">Books</option>
    <option value="bookcopy">Book Copies</option>
    <option value="checkoutledger">Checkout Ledger</option>
</select>
<button onClick="callRESTAPI(document.getElementById('RESTAPIobj').value, 'read', null, handleRESTAPIReadResponse, handleRESTAPIReadError);">Execute</button>

<br><br>
<label id="libraryReadDataLabel" for="rawReadResponseJSONContainer"></label>
<div>
    <pre id="rawReadResponseJSONContainer"></pre>
</div>

<p><br><br>
<h2 id="libraryReadDataTableLabel"></h2>
<!-- DataTables: BEGIN -->
<div id="libraryReadDataTableContainer"></div>
<!-- DataTables: END -->