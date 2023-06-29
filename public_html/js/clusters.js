var lng = 25;
var lat = 45;

ajaxRequest('GET', 'api/index.php/clusters', displayClusters, `prediction&lat=${lat}&lng=${lng}`);

function displayClusters(data){
    console.log(data);
    var lat_cluster = data['clusters']['latitude du centroid'];
    var lng_cluster = data['clusters']['longitude du centroid'];

    var lat_acc = data['accident']['latitude de l\'accident'];
    var lng_acc = data['accident']['longitude de l\'accident'];
    console.log(lat_cluster);
    console.log(lng_cluster);
    console.log(lat_acc);
    console.log(lng_acc);

}