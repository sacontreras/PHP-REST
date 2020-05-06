<script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.20/css/jquery.dataTables.min.css"/>

<script src="./lib/RESTclient.js"></script>
<script>
function handleRESTAPIResetResponse(restAPIResponseJSON, obj, verb) {
    const datetimestamp = new Date(); 
    const libraryDataLabelText = `Library data last requested ${recurrent ? "RECURRENTLY" : "ONCE"} via API <b>${buildRESTEndpoint(obj, verb)}</b> at <strong>${datetimestamp.getFullYear()}/${datetimestamp.getMonth()+1}/${datetimestamp.getDate()} @ ${datetimestamp.getHours()}:${datetimestamp.getMinutes()}:${datetimestamp.getSeconds()}</strong>, RAW JSON Response:`;
    const libraryResetDataLabel = document.getElementById("libraryResetDataLabel");
    libraryResetDataLabel.innerHTML = libraryDataLabelText;
    const rawResetResponseJSONContainer = document.getElementById("rawResetResponseJSONContainer");
    rawResetResponseJSONContainer.innerHTML = jsonResponseToString(restAPIResponseJSON);
}

function handleRESTAPIResetError(xhr, obj, verb) {
    const datetimestamp = new Date(); 
    const libraryDataLabelText = `Library data last requested ${recurrent ? "RECURRENTLY" : "ONCE"} via API <b>${buildRESTEndpoint(obj, verb)}</b> at <strong>${datetimestamp.getFullYear()}/${datetimestamp.getMonth()+1}/${datetimestamp.getDate()} @ ${datetimestamp.getHours()}:${datetimestamp.getMinutes()}:${datetimestamp.getSeconds()}</strong>, RAW JSON Response:`;
    const libraryResetDataLabel = document.getElementById("libraryResetDataLabel");
    libraryResetDataLabel.innerHTML = libraryDataLabelText;
    const rawResetResponseJSONContainer = document.getElementById("rawResetResponseJSONContainer");
    rawResetResponseJSONContainer.innerHTML = 'Error ' + xhr.status +  (xhr.response != null ? ': ' + jsonResponseToString(xhr.response) : '');
}

function resetDatabase() {
    doit = confirm("Are you absolutely sure you want to reset the database?\n\nThis will remove records added since it was first initialized and reset it back to its default state, with only default data!");
    if (doit)
        callRESTAPI('db', 'reset', null, handleRESTAPIResetResponse, handleRESTAPIResetError);
}
</script>

<br>
<h2>RESET Database API:</h2>
<br>
<label>There is only one option:</label>
<button onClick="resetDatabase();">DO IT!</button>

<br><br>
<label id="libraryResetDataLabel" for="rawResetResponseJSONContainer"></label>
<div>
    <pre id="rawResetResponseJSONContainer"></pre>
</div>