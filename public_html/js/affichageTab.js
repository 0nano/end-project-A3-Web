ajaxRequest('GET', 'api/index.php/accidents', displayTab);

function displayTab(data) {
    document.getElementById('accidentsbody').innerHTML = '';
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

    let clust = document.getElementById('cluster');

    clust.addEventListener('click', function() {
        let radios = document.querySelectorAll('input[type=radio]');
        for (let i = 0; i < radios.length; i++) {
            if (radios[i].checked) {
                location.replace("./prediction.html?id=" + radios[i].value);
            }
        }
    });

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

let page = document.getElementById('page');
let athmo = document.getElementById('descr_atmo');
let lum = document.getElementById('descr_lum');
let etat_surf = document.getElementById('descr_etat_surf');
let dispo_secu = document.getElementById('descr_dispo_secu');

function displayIndex(data) {
    page.innerHTML = '';
    for (let i = 0; i < data+1; i++) {
        let inner = '<option value="' + i + '">' + (i+1) + '</option>';
        page.insertAdjacentHTML('afterbegin', inner);
    }
}

ajaxRequest('GET', 'api/index.php/accidents?length', displayIndex);

page.addEventListener('change', function() {
    let vallum = lum.value;
    if (vallum != '') {
        vallum = '&lum=' + vallum;
    }
    let valetat_surf = etat_surf.value;
    if (valetat_surf != '') {
        valetat_surf = '&etat_surf=' + valetat_surf;
    }
    let valdispo_secu = dispo_secu.value;
    if (valdispo_secu != '') {
        valdispo_secu = '&dispo_secu=' + valdispo_secu;
    }
    let valathmo = athmo.value;
    if (valathmo != '') {
        valathmo = '&athmo=' + valathmo;
    }
    if (lum.value == '' && etat_surf.value == '' && dispo_secu.value == '' && athmo.value == ''){
        let url = 'api/index.php/accidents?offset=' + page.value;
        ajaxRequest('GET', url, displayTab);
    }else{
        let value = page.value;
        let url = 'api/index.php/accidents?filtre&offset=' + value + vallum + valetat_surf + valathmo +valdispo_secu;
        ajaxRequest('GET', url, displayTab);
    }
});

athmo.addEventListener('change', function() {
    let vallum = lum.value;
    if (vallum != '') {
        vallum = '&lum=' + vallum;
    }
    let valetat_surf = etat_surf.value;
    if (valetat_surf != '') {
        valetat_surf = '&etat_surf=' + valetat_surf;
    }
    let valdispo_secu = dispo_secu.value;
    if (valdispo_secu != '') {
        valdispo_secu = '&dispo_secu=' + valdispo_secu;
    }
    if (lum.value == '' && etat_surf.value == '' && dispo_secu.value == '' && athmo.value == ''){
        let url = 'api/index.php/accidents';
        ajaxRequest('GET', url, displayTab);
        ajaxRequest('GET', url+'&length', displayIndex);
    }else{
        let value = athmo.value;
        let url = 'api/index.php/accidents?filtre&athmo=' + value + vallum + valetat_surf + valdispo_secu;
        ajaxRequest('GET', url, displayTab);
        ajaxRequest('GET', url + '&length', displayIndex);
    }
});

lum.addEventListener('change', function() {
    let valetat_surf = etat_surf.value;
    if (valetat_surf != '') {
        valetat_surf = '&etat_surf=' + valetat_surf;
    }
    let valdispo_secu = dispo_secu.value;
    if (valdispo_secu != '') {
        valdispo_secu = '&dispo_secu=' + valdispo_secu;
    }
    let valathmo = athmo.value;
    if (valathmo != '') {
        valathmo = '&athmo=' + valathmo;
    }
    if (lum.value == '' && etat_surf.value == '' && dispo_secu.value == '' && athmo.value == ''){
        let url = 'api/index.php/accidents';
        ajaxRequest('GET', url, displayTab);
        ajaxRequest('GET', url+'&length', displayIndex);
    }else{
        let value = lum.value;
        let url = 'api/index.php/accidents?filtre&lum=' + value + valetat_surf + valdispo_secu + valathmo;
        ajaxRequest('GET', url, displayTab);
        ajaxRequest('GET', url + '&length', displayIndex);
    }
});

etat_surf.addEventListener('change', function() {
    let vallum = lum.value;
    if (vallum != '') {
        vallum = '&lum=' + vallum;
    }
    let valdispo_secu = dispo_secu.value;
    if (valdispo_secu != '') {
        valdispo_secu = '&dispo_secu=' + valdispo_secu;
    }
    let valathmo = athmo.value;
    if (valathmo != '') {
        valathmo = '&athmo=' + valathmo;
    }
    if (lum.value == '' && etat_surf.value == '' && dispo_secu.value == '' && athmo.value == ''){
        let url = 'api/index.php/accidents';
        ajaxRequest('GET', url, displayTab);
        ajaxRequest('GET', url+'&length', displayIndex);
    }else{
        let value = etat_surf.value;
        let url = 'api/index.php/accidents?filtre&etat_surf=' + value + vallum + valdispo_secu + valathmo;
        ajaxRequest('GET', url, displayTab);
        ajaxRequest('GET', url + '&length', displayIndex);
    }
});

dispo_secu.addEventListener('change', function() {
    let vallum = lum.value;
    if (vallum != '') {
        vallum = '&lum=' + vallum;
    }
    let valetat_surf = etat_surf.value;
    if (valetat_surf != '') {
        valetat_surf = '&etat_surf=' + valetat_surf;
    }
    let valathmo = athmo.value;
    if (valathmo != '') {
        valathmo = '&athmo=' + valathmo;
    }
    if (lum.value == '' && etat_surf.value == '' && dispo_secu.value == '' && athmo.value == ''){
        let url = 'api/index.php/accidents';
        ajaxRequest('GET', url, displayTab);
        ajaxRequest('GET', url+'&length', displayIndex);
    }else{
        let value = dispo_secu.value;
        let url = 'api/index.php/accidents?filtre&dispo_secu=' + value + vallum + valetat_surf + valathmo;
        ajaxRequest('GET', url, displayTab);
        ajaxRequest('GET', url + '&length', displayIndex);
    }
});
