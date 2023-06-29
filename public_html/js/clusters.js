var lng = 3.6;
var lat = 45.05;

ajaxRequest('GET', 'api/index.php/clusters', displayClusters, `prediction&lat=${lat}&lng=${lng}`);

function displayClusters(data){
    console.log(data);
    var lat_cluster = data['cluster']['latitude du centroid'];
    var lng_cluster = data['cluster']['longitude du centroid'];

    var lat_acc = data['accident']['latitude de l\'accident'];
    var lng_acc = data['accident']['longitude de l\'accident'];
    console.log(lat_cluster);
    console.log(lng_cluster);
    console.log(lat_acc);
    console.log(lng_acc);

    var map = L.map('myDivPredict').setView([lat, lng], 6);
    L.tileLayer('https://{s}.tile.openstreetmap.fr/osmfr/{z}/{x}/{y}.png', {
        maxZoom: 20,
        minZoom: 5,
        attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors'
    }).addTo(map);

    var redIcon = L.icon({
        iconUrl: 'https://cdn.jsdelivr.net/npm/leaflet@1.7.1/dist/images/marker-icon-2x-red.png',
        iconSize: [25, 41],
        iconAnchor: [12, 41]
      });
      
      var marker = L.marker([lat_cluster, lng_cluster], { icon: redIcon }).addTo(map);
      var marker = L.marker([lat_acc, lng_acc]).addTo(map);
    document.getElementById('myDivPredict').append(map);
}

