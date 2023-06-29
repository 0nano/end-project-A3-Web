for (i=0; i<4000; i++){
    ajaxRequest('GET', 'api/index.php/accidents?offset='+i, total)
}
//ajaxRequest('GET', 'api/index.php/accidents?offset=1', blabla)
//mettre le resultat de la requete dans une variable
var data_lon = [];
var data_lat=[];
var data_ville=[];

function total(data){
        data_full = data_full.concat(data);
  
let data_lon = data_full.map(row => row['longitude']);
let data_lat = data_full.map(row => row['latitude']);
let data_ville = data_full.map(row => row['ville']);


var data = [
	{
		type: "scattermapbox",
        Text: data_ville,
		lon: data_lon,
		lat: data_lat,
		marker: { color: '#FFE194', size: 10 }
	}
];

var layout = {
	dragmode: "zoom",
	mapbox: {
		style: "white-bg",
		layers: [
			{
				sourcetype: "raster",
				source: ["https://basemap.nationalmap.gov/arcgis/rest/services/USGSImageryOnly/MapServer/tile/{z}/{y}/{x}"],
				below: "traces"
			}
		],
		center: { lat: 47 , lon: 2.5},
		zoom: 5
	},
	margin: { r: 0, t: 0, b: 0, l: 0 }
};

Plotly.newPlot("myDiv", data, layout);
}
