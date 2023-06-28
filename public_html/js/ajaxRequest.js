/**
 * This function is used to send an AJAX request.
 * 
 * @param {string} type 
 * @param {string} url 
 * @param {*} callback 
 * @param {*} data 
 */

function ajaxRequest(type, url, callback, data = null)
{
    let xhr;

    // Create XML HTTP request.
    xhr = new XMLHttpRequest();
    if (type == 'GET' && data != null)
        url += '?' + data;
    xhr.open(type, url);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

    // Add the onload function.
    xhr.onload = () =>
    {
        switch (xhr.status)
        {
            case 200:
            case 201:
                callback(JSON.parse(xhr.responseText));
                break;
            default:
                httpErrors(xhr.status);
        }
    };

    // Send XML HTTP request.
    xhr.send(data);
}

/**
 * Display an error message according to the error code.
 * 
 * @param {*} errorCode 
 */
function httpErrors(errorCode)
{
    let messages =
        {
            400: 'Requête incorrecte',
            401: 'Authentifiez vous',
            403: 'Accès refusé',
            404: 'Page non trouvée',
            500: 'Erreur interne du serveur',
            503: 'Service indisponible'
        };

    // Display error.
    if (errorCode in messages)
    {
        console.log(errorCode + ' : ' + messages[errorCode]);
    }
}