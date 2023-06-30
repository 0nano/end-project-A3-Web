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

ajaxRequest('GET', 'api/index.php/clusters?prediction&id='+$_GET['id'], displayClusters);

function displayClusters(data){
    var lat_cluster = data['cluster']['latitude du centroid'];
    var lng_cluster = data['cluster']['longitude du centroid'];

    var lat_acc = data['accident']['latitude de l\'accident'];
    var lng_acc = data['accident']['longitude de l\'accident'];

 // dessine une carte de la france avec le point de l'accident et le point du cluster
    var map = L.map('myDivPredict').setView([46.85, 2.63], 6);
    L.tileLayer('https://{s}.tile.openstreetmap.fr/osmfr/{z}/{x}/{y}.png', {
        maxZoom: 20,
        minZoom: 5,
        attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors'
    }).addTo(map);

    var markerAcc = L.marker([lat_acc, lng_acc]).addTo(map);
    var markerCluster = L.marker([lat_cluster, lng_cluster]).addTo(map);

    markerAcc.bindTooltip("Accident").openTooltip();
    markerCluster.bindTooltip("Cluster").openTooltip();
    
    document.getElementById('myDivPredict').append(map);
}

