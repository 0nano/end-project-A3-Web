console.log("cluster");
var lng = 25;
var lat = 45;

ajaxRequest('GET', 'index.html/api/index.php/clusters', displayClusters, `prediction&lat=${lat}&lng=${lng}`);

function displayClusters(clusters){
    console.log(clusters);
}