var recurrent = false;
var updateFrequency = 5000;
var rootEndpoint = "http://localhost:8080/DF/REST/api";
    
function buildRESTEndpoint(obj, verb) {
    return `${rootEndpoint}/${obj}/${verb}.php`;
}

function jsonResponseToString(jsonObject) {
    // creating an empty object to store the JSON in key-value pairs
    let rawJson = {};
    for (let key in jsonObject){
        rawJson[key] = jsonObject[key];
    }
    // converting JSON into a string and adding line breaks to make it easier to read
    return JSON.stringify(rawJson, null, "\t")
}
    
function callRESTAPI(obj, verb, params, f_handler, err_handler) {
    const endpoint = buildRESTEndpoint(obj, verb);
    const xhr = new XMLHttpRequest();

    xhr.responseType = 'json';
    xhr.onload = function () {
        if (xhr.status >= 200 && xhr.status < 300) {
            console.log(`xhr.onload: xhr.response=${jsonResponseToString(xhr.response)}`);
            f_handler(xhr.response, obj, verb);
        } else {
            console.log(`xhr.onload error: status=${xhr.status}`);
            err_handler(xhr, obj, verb);
        }
    };

    console.log(`callRESTAPI: endpoint=${endpoint}, params=${params}`);

    switch (verb) {
        case 'insert':
            xhr.open('POST', endpoint, true);
            xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
            xhr.send(params);
            break;
        default:
            xhr.open('GET', endpoint + (params != null && params.length > 0 ? "?" + params : ""));
            xhr.send();
            break;
    }
}