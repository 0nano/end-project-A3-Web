ajaxRequest('GET', 'api/index.php/accidents', displayTab);

function displayTab(data) {
    for (let i = 0; i < data.length; i++) {
        let id = data[i].id_accident;
        let date = data[i].date;
        let age = data[i].age;
        let lat = data[i].latitude;
        let lng = data[i].longitude;
        let athmo = data[i].athmo_descr;
        let lum = data[i].lum_descr;
        let etat_surf = data[i].etat_surf_descr;
        let dispo_secu = data[i].dispo_secu_descr;
        
        let inner = '<tr><td><input type="radio" id="accident'+ id +'" name="accident" value="' + id + '"></td><td>' + date + '</td><td>' + age + '</td><td>' + lat + '</td><td>' + lng +
            '</td><td>' + athmo + '</td><td>' + lum + '</td><td>' + etat_surf + '</td><td>' + dispo_secu + '</td></tr>';
        document.getElementById('accidentsbody').innerHTML += inner;
    }

    let pred = document.getElementById('prediction');

    pred.addEventListener('click', function() {
        let radios = document.querySelectorAll('input[type=radio]');
        for (let i = 0; i < radios.length; i++) {
            if (radios[i].checked) {
                location.replace("./prediction-select.html?id=" + radios[i].value);
            }
        }
    });
}

