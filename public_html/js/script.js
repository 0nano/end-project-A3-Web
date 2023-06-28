


//FONCTION POUR L'AFFICHAGE DE LA CARTE

d3.csv('https://raw.githubusercontent.com/plotly/datasets/master/2015_06_30_precipitation.csv', function(err, rows){
      function unpack(rows, key) {
          return rows.map(function(row) { return row[key]; });
		}

var data = [{
        type: 'scattermapbox', text: unpack(rows, 'Globvalue'),
        lon: unpack(rows, 'Lon'), lat: unpack(rows, 'Lat'),
        marker: {color: 'fuchsia', size: 4}
    }];

var layout = {
	dragmode: 'zoom',
	mapbox: {
		style: 'white-bg',
		layers: [
			{
            "below": 'traces',
            "sourcetype": "raster",
            "source": [
                "https://basemap.nationalmap.gov/arcgis/rest/services/USGSImageryOnly/MapServer/tile/{z}/{y}/{x}"
            ]
        },
			{
             sourcetype: "raster",
			 source: ["https://geo.weather.gc.ca/geomet/?SERVICE=WMS&VERSION=1.3.0&REQUEST=GetMap&BBOX={bbox-epsg-3857}&CRS=EPSG:3857&WIDTH=1000&HEIGHT=1000&LAYERS=RADAR_1KM_RDBR&TILED=true&FORMAT=image/png"]}],
		below: 'traces',
		center: {lat: 47, lon: 3}, zoom: 5},
	margin: {r:0, t: 0, b: 0, l: 0}, 
	showlegend: false};

Plotly.newPlot('myDiv', data, layout);
  });



  


//FONCTION POUR L'AFFICHAGE DE LA CARTE

d3.csv('https://raw.githubusercontent.com/plotly/datasets/master/2015_06_30_precipitation.csv', function(err, rows){
    function unpack(rows, key) {
        return rows.map(function(row) { return row[key]; });
      }

var data = [{
      type: 'scattermapbox', text: unpack(rows, 'Globvalue'),
      lon: unpack(rows, 'Lon'), lat: unpack(rows, 'Lat'),
      marker: {color: 'fuchsia', size: 4}
  }];

var layout = {
  dragmode: 'zoom',
  mapbox: {
      style: 'white-bg',
      layers: [
          {
          "below": 'traces',
          "sourcetype": "raster",
          "source": [
              "https://basemap.nationalmap.gov/arcgis/rest/services/USGSImageryOnly/MapServer/tile/{z}/{y}/{x}"
          ]
      },
          {
           sourcetype: "raster",
           source: ["https://geo.weather.gc.ca/geomet/?SERVICE=WMS&VERSION=1.3.0&REQUEST=GetMap&BBOX={bbox-epsg-3857}&CRS=EPSG:3857&WIDTH=1000&HEIGHT=1000&LAYERS=RADAR_1KM_RDBR&TILED=true&FORMAT=image/png"]}],
      below: 'traces',
      center: {lat: 47, lon: 3}, zoom: 5},
  margin: {r:0, t: 0, b: 0, l: 0}, 
  showlegend: false};

Plotly.newPlot('myDivPredict', data, layout);
});