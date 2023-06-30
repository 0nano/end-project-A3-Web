ajaxRequest('GET', 'api/index.php/accidents?big', total)

function total(data){
  
	let data_lon = data.map(row => row['longitude']);
	let data_lat = data.map(row => row['latitude']);
	let data_ville = data.map(row => row['ville']);

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
			style: "open-street-map",
			center: { lat: 47 , lon: 2.5},
			zoom: 5
		},
		margin: { r: 0, t: 0, b: 0, l: 0 }
	};

	Plotly.newPlot("myDiv", data, layout);
}

