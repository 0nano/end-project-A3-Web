var data_full=[];
//faire une boucle pour récupérer toutes les données allant jusque 30 

for (i=0; i<10; i++){
    ajaxRequest('GET', 'api/index.php/accidents?offset='+i, total)
}
//ajaxRequest('GET', 'api/index.php/accidents?offset=1', blabla)
//mettre le resultat de la requete dans une variable

function total(data){
        data_full = data_full.concat(data);
  
let data_lon = data_full.map(row => row['longitude']);
let data_lat = data_full.map(row => row['latitude']);
let data_ville = data_full.map(row => row['ville']);

}


var data_full=[];
//faire une boucle pour récupérer toutes les données allant jusque 30 

for (i=0; i<10; i++){
    ajaxRequest('GET', 'api/index.php/accidents?offset='+i, total)
}
//ajaxRequest('GET', 'api/index.php/accidents?offset=1', blabla)
//mettre le resultat de la requete dans une variable

function total(data){
data_full = data_full.concat(data);
  
let data_lon = data_full.map(row => row['longitude']);
let data_lat = data_full.map(row => row['latitude']);
let data_ville = data_full.map(row => row['ville']);

console.log(data_lon);
console.log(data_lat);
console.log(data_ville);
}