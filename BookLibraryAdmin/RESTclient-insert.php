<script src="./lib/RESTclient.js"></script>
<script>
function buildDropDownOptions(restAPIResponseJSON, select_id, value_column, display_column) {
    if (restAPIResponseJSON != null) {
        const dom_select_elt = document.getElementById(select_id);
        records = restAPIResponseJSON["records"];
        s_options = `<option disabled selected value> -- select a ${display_column} -- </option>`;
        for (let key in records) {
            record = records[key];
            s_options += `<option value="${record[value_column]}">${record[display_column]}</option>`;
        }
        dom_select_elt.innerHTML = s_options;
    }
}

function handleRESTAPIBookLookup(restAPIResponseJSON, obj, verb) {
    buildDropDownOptions(restAPIResponseJSON, 'select_bookid', 'BookID', 'Title');
}

//empty for now... until it is needed
function handleRESTAPIBookLookupError(xhr, obj, verb) {}

function lookup_books() {
    callRESTAPI(
        'book', 
        'read', 
        null, 
        handleRESTAPIBookLookup,
        handleRESTAPIBookLookupError
    );
}

function handleRESTAPILibrarianLookup(restAPIResponseJSON, obj, verb) {
    buildDropDownOptions(restAPIResponseJSON, 'select_checkoutledgerlibrarianid', 'LibrarianID', 'Name');    
}

//empty for now... until it is needed
function handleRESTAPILibrarianLookupError(xhr, obj, verb) {}

function lookup_librarians() {
    callRESTAPI(
        'librarian', 
        'read', 
        null, 
        handleRESTAPILibrarianLookup,
        handleRESTAPILibrarianLookupError
    );
}

function handleRESTAPIBorrowerLookup(restAPIResponseJSON, obj, verb) {
    buildDropDownOptions(restAPIResponseJSON, 'select_librarycardid', 'LibraryCardID', 'Name');    
}

//empty for now... until it is needed
function handleRESTAPIBorrowerLookupError(xhr, obj, verb) {}

function lookup_borrowers() {
    callRESTAPI(
        'borrower', 
        'read', 
        null, 
        handleRESTAPIBorrowerLookup,
        handleRESTAPIBorrowerLookupError
    );
}

function handleRESTAPIBookCopyLookup(restAPIResponseJSON, obj, verb) {
    buildDropDownOptions(restAPIResponseJSON, 'select_bookcopyid', 'BookCopyID', 'SKU');
}

//empty for now... until it is needed
function handleRESTAPIBookCopyLookupError(xhr, obj, verb) {}

function lookup_SKUs_for_book(bookid) {
    callRESTAPI(
        'bookcopy', 
        'read', 
        `bookid=${bookid}`, 
        handleRESTAPIBookCopyLookup,
        handleRESTAPIBookCopyLookupError
    );
}

function renderInsertForm(obj) {
    s_data_entry_html = "";
    s_btn_text = "Insert New ";
    f_lookup = null;
    switch (obj) {
        case 'borrower':
            s_data_entry_html = " \
                <label>Name:</label> \
                <input id='input_borrowername' type='text'></input> \
                <br> \
                <label>Address:</label> \
                <input id='input_borroweraddress' type='text'></input> \
                <br> \
                <label>Postal Code:</label> \
                <input id='input_borrowerpostalcode' type='text'></input> \
                <br> \
                <label>Phone Number:</label> \
                <input id='input_borrowerphonenumber' type='text'></input> \
            ";
            s_btn_text += "Borrower"; 
            break;
        case 'librarian':
            s_data_entry_html = " \
                <label>Name:</label> \
                <input id='input_librarianname' type='text'></input> \
                <br> \
                <label>Phone Number:</label> \
                <input id='input_librarianphonenumber' type='text'></input> \
            ";
            s_btn_text += "Librarian";
            break;
        case 'book':
            s_data_entry_html = " \
                <label>ISBN:</label> \
                <input id='input_bookisbn' type='text'></input> \
                <br> \
                <label>Title:</label> \
                <input id='input_booktitle' type='text'></input> \
                <br> \
                <label>Edition:</label> \
                <input id='input_bookedition' type='text'></input> \
                <br> \
                <label>Author:</label> \
                <input id='input_bookauthor' type='text'></input> \
                <br> \
                <label>Publication Date:</label> \
                <input id='input_bookpublicationdate' type='text'></input> \
                <br> \
                <label>Cost:</label> \
                <input id='input_bookcost' type='text'></input> \
            ";
            s_btn_text += "Book";
            break;
        case 'bookcopy':
            s_data_entry_html = " \
                <label>Book:</label> \
                <select id='select_bookid'> \
                </select>  \
                <br> \
                <label>SKU:</label> \
                <input id='input_bookcopysku' type='text'></input> \
            ";
            s_btn_text += "Book Copy";
            f_lookup = [lookup_books];
            break;
        default: //checkoutledger
            s_data_entry_html = " \
                <label>Librarian:</label> \
                <select id='select_checkoutledgerlibrarianid'> \
                </select>  \
                <br> \
                <label>Borrower:</label> \
                <select id='select_librarycardid'> \
                </select>  \
                <br> \
                <label>Book:</label> \
                <select id='select_bookid' onChange='lookup_SKUs_for_book(this.value);'> \
                </select>  \
                <br> \
                <label>SKU:</label> \
                <select id='select_bookcopyid'> \
                </select>  \
            ";
            s_btn_text += "Checkout Ledger Entry";
            var f_lookup = [lookup_librarians, lookup_borrowers, lookup_books];
            break;
    }

    const insertDataEntryContainer = document.getElementById("insertDataEntryContainer");
    insertDataEntryContainer.innerHTML = s_data_entry_html;
    const btnInsertData = document.getElementById("btnInsertData");
    btnInsertData.innerHTML = s_btn_text;
    const libraryInsertDataLabel = document.getElementById("libraryInsertDataLabel");
    libraryInsertDataLabel.innerHTML = "";
    const rawInsertResponseJSONContainer = document.getElementById("rawInsertResponseJSONContainer");
    rawInsertResponseJSONContainer.innerHTML = "";
    if (f_lookup != null)
        for (let f_i in f_lookup)
            f_lookup[f_i]();
}

function handleRESTAPIInsertResponse(restAPIResponseJSON, obj, verb) {
    const datetimestamp = new Date(); 
    const libraryDataLabelText = `Library ${obj} data last inserted ${recurrent ? "RECURRENTLY" : "ONCE"} via API <b>${buildRESTEndpoint(obj, verb)}</b> at <strong>${datetimestamp.getFullYear()}/${datetimestamp.getMonth()+1}/${datetimestamp.getDate()} @ ${datetimestamp.getHours()}:${datetimestamp.getMinutes()}:${datetimestamp.getSeconds()}</strong>, RAW JSON Response:`;
    const libraryInsertDataLabel = document.getElementById("libraryInsertDataLabel");
    libraryInsertDataLabel.innerHTML = libraryDataLabelText;
    const rawInsertResponseJSONContainer = document.getElementById("rawInsertResponseJSONContainer");
    rawInsertResponseJSONContainer.innerHTML = restAPIResponseJSON != null ? jsonResponseToString(restAPIResponseJSON) : "null response";
}

function handleRESTAPIInsertError(xhr, obj, verb) {
    const datetimestamp = new Date(); 
    const libraryDataLabelText = `Library ${obj} data last inserted ${recurrent ? "RECURRENTLY" : "ONCE"} via API <b>${buildRESTEndpoint(obj, verb)}</b> at <strong>${datetimestamp.getFullYear()}/${datetimestamp.getMonth()+1}/${datetimestamp.getDate()} @ ${datetimestamp.getHours()}:${datetimestamp.getMinutes()}:${datetimestamp.getSeconds()}</strong>, RAW JSON Response:`;
    const libraryInsertDataLabel = document.getElementById("libraryInsertDataLabel");
    libraryInsertDataLabel.innerHTML = libraryDataLabelText;
    const rawInsertResponseJSONContainer = document.getElementById("rawInsertResponseJSONContainer");
    rawInsertResponseJSONContainer.innerHTML = 'Error ' + xhr.status +  (xhr.response != null ? ': ' + jsonResponseToString(xhr.response) : '');
}

function executeRESTInsert() {
    const selRESTAPIobj = document.getElementById("selRESTAPIobj");
    json_params = {};
    switch (selRESTAPIobj.value) {
        case 'borrower':
            json_params = {
                "borrowername": encodeURI(document.getElementById("input_borrowername").value),
                "borroweraddress": encodeURI(document.getElementById("input_borroweraddress").value),
                "borrowerpostalcode": encodeURI(document.getElementById("input_borrowerpostalcode").value),
                "borrowerphonenumber": encodeURI(document.getElementById("input_borrowerphonenumber").value)
            };
            break;
        case 'librarian':
            json_params = {
                "librarianname": encodeURI(document.getElementById("input_librarianname").value),
                "librarianphonenumber": encodeURI(document.getElementById("input_librarianphonenumber").value)
            };
            break;
        case 'book':
            json_params = {
                "bookisbn": encodeURI(document.getElementById("input_bookisbn").value),
                "booktitle": encodeURI(document.getElementById("input_booktitle").value),
                "bookedition": encodeURI(document.getElementById("input_bookedition").value),
                "bookauthor": encodeURI(document.getElementById("input_bookauthor").value),
                "bookpublicationdate": encodeURI(document.getElementById("input_bookpublicationdate").value),
                "bookcost": encodeURI(document.getElementById("input_bookcost").value)
            };
            break;
        case 'bookcopy':
            json_params = {
                "bookid": encodeURI(document.getElementById("select_bookid").value),
                "bookcopysku": encodeURI(document.getElementById("input_bookcopysku").value)
            };
            break;
        default: //checkoutledger
            json_params = {
                "librarianid": encodeURI(document.getElementById("select_checkoutledgerlibrarianid").value),
                "checkoutdate": encodeURI(new Date().toISOString().slice(0,10)),
                "librarycardid": encodeURI(document.getElementById("select_librarycardid").value),
                "bookid": encodeURI(document.getElementById("select_bookid").value),
                "bookcopyid": encodeURI(document.getElementById("select_bookcopyid").value)
            };
            break;
    }
    callRESTAPI(
        selRESTAPIobj.value, 
        'insert', 
        jsonResponseToString(json_params), 
        handleRESTAPIInsertResponse,
        handleRESTAPIInsertError
    );
}
</script>

<br>
<h2>INSERT APIs available:</h2>
<br>
<label>INSERT</label>
<select id="selRESTAPIobj" onChange="renderInsertForm(this.value);">
    <option value="borrower">Borrower</option>
    <option value="librarian">Librarian</option>
    <option value="book">Book</option>
    <option value="bookcopy">Book Copy</option>
    <option value="checkoutledger">Checkout Ledger</option>
</select>
<br>
<br><br>
<div id="insertDataEntryContainer"></div>
<br><br>
<button id="btnInsertData" onClick="executeRESTInsert();"></button>

<br><br>
<label id="libraryInsertDataLabel" for="rawInsertResponseJSONContainer"></label>
<div>
    <pre id="rawInsertResponseJSONContainer"></pre>
</div>

<script>
$(document).ready(function(){
    const selRESTAPIobj = document.getElementById("selRESTAPIobj");
    selRESTAPIobj.value = "checkoutledger";
    if ("createEvent" in document) {
        var evt = document.createEvent("HTMLEvents");
        evt.initEvent("change", false, true);
        selRESTAPIobj.dispatchEvent(evt);
    }
    else {
        selRESTAPIobj.fireEvent("onchange");
    }
});
</script>