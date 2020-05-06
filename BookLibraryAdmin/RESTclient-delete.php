<script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.20/css/jquery.dataTables.min.css"/>

<script src="./lib/RESTclient.js"></script>
<script>
function handleRESTAPIDeleteResponse(restAPIResponseJSON, obj, verb) {
    const datetimestamp = new Date(); 
    const libraryDataLabelText = `Library data last requested ${recurrent ? "RECURRENTLY" : "ONCE"} via API <b>${buildRESTEndpoint(obj, verb)}</b> at <strong>${datetimestamp.getFullYear()}/${datetimestamp.getMonth()+1}/${datetimestamp.getDate()} @ ${datetimestamp.getHours()}:${datetimestamp.getMinutes()}:${datetimestamp.getSeconds()}</strong>, RAW JSON Response:`;
    const libraryDeleteDataLabel = document.getElementById("libraryDeleteDataLabel");
    libraryDeleteDataLabel.innerHTML = libraryDataLabelText;
    const rawDeleteResponseJSONContainer = document.getElementById("rawDeleteResponseJSONContainer");
    rawDeleteResponseJSONContainer.innerHTML = jsonResponseToString(restAPIResponseJSON);

    const libraryDeleteDataTableLabel = document.getElementById("libraryDeleteDataTableLabel");
    libraryDeleteDataTableLabel.innerHTML = `<u>${obj}</u> Data Grid:`;
    const libraryDeleteDataTableContainer = document.getElementById("libraryDeleteDataTableContainer");
    libraryDeleteDataTableContainer.innerHTML = "<table id='libraryDeleteDataTable' class='display' style='width:100%''><tr><td>No Results</td></tr></table>";
    var tbl = $("#libraryDeleteDataTable")
    tbl.DataTable ({
        "data" : restAPIResponseJSON["records"],
        "columns" : restAPIResponseJSON["columns"]
    });
}

function handleRESTAPIDeleteError(xhr, obj, verb) {
    const datetimestamp = new Date(); 
    const libraryDataLabelText = `Library data last requested ${recurrent ? "RECURRENTLY" : "ONCE"} via API <b>${buildRESTEndpoint(obj, verb)}</b> at <strong>${datetimestamp.getFullYear()}/${datetimestamp.getMonth()+1}/${datetimestamp.getDate()} @ ${datetimestamp.getHours()}:${datetimestamp.getMinutes()}:${datetimestamp.getSeconds()}</strong>, RAW JSON Response:`;
    const libraryDeleteDataLabel = document.getElementById("libraryDeleteDataLabel");
    libraryDeleteDataLabel.innerHTML = libraryDataLabelText;
    const rawDeleteResponseJSONContainer = document.getElementById("rawDeleteResponseJSONContainer");
    rawDeleteResponseJSONContainer.innerHTML = 'Error ' + xhr.status +  (xhr.response != null ? ': ' + jsonResponseToString(xhr.response) : '');
}
</script>

<br>
<h2>DELETE APIs available:</h2>
<br>
<label>DELETE</label>
<select id="RESTAPIobj">
    <option value="borrower">Borrowers</option>
    <option value="librarian">Librarian</option>
    <option value="book">Books</option>
    <option value="bookcopy">Book Copies</option>
    <option value="checkoutledger">Checkout Ledger</option>
</select>
<button onClick="callRESTAPI(document.getElementById('RESTAPIobj').value, 'delete', null, handleRESTAPIDeleteResponse, handleRESTAPIDeleteError);">Execute</button>

<br><br>
<label id="libraryDeleteDataLabel" for="rawDeleteResponseJSONContainer"></label>
<div>
    <pre id="rawDeleteResponseJSONContainer"></pre>
</div>

<p><br><br>
<h2 id="libraryDeleteDataTableLabel"></h2>
<!-- DataTables: BEGIN -->
<div id="libraryDeleteDataTableContainer"></div>
<!-- DataTables: END -->