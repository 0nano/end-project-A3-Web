// get GET parameters from URL
// https://stackoverflow.com/questions/12049620/how-to-get-get-variables-value-in-javascript

var $_GET = {};

if(document.location.toString().indexOf('?') !== -1) {
    var query = document.location
                   .toString()
                   // get the query string
                   .replace(/^.*?\?/, '')
                   // and remove any existing hash string (thanks, @vrijdenker)
                   .replace(/#.*$/, '')
                   .split('&');

    for(var i=0, l=query.length; i<l; i++) {
       var aux = decodeURIComponent(query[i]).split('=');
       $_GET[aux[0]] = aux[1];
    }
}

ajaxRequest('GET', 'api/index.php/gravite?all&id='+$_GET['id'], getAllGravite);

function getAllGravite(data) {
    let pred1 = document.getElementById('predict1');
    let pred2 = document.getElementById('predict2');
    let pred3 = document.getElementById('predict3');
    let pred4 = document.getElementById('predict4');

    let valeur_reelle = ['indemne', 'blessé léger', 'blessé hospitalisé', 'tue'];

    pred1.innerHTML = valeur_reelle[data.KNN.gravite];
    pred2.innerHTML = valeur_reelle[data.SVM.gravitee];
    pred3.innerHTML = valeur_reelle[data.RF.gravitee];
    pred4.innerHTML = valeur_reelle[data.MLP.gravitee];
}
