let valid = document.getElementById('valid');

valid.addEventListener('click', function() {
    let descr_dispo_secu = document.getElementById('descr_dispo_secu').value;
    let descr_etat_surf = document.getElementById('descr_etat_surf').value;
    let descr_lum = document.getElementById('descr_lum').value;
    let descr_athmo = document.getElementById('descr_atmo').value;
    let longitude = document.getElementById('long').value;
    let latitude = document.getElementById('lat').value;
    let age = document.getElementById('age').value;
    let date = document.getElementById('date').value;
    let ville = document.getElementById('ville').value;

    let data = encodeURI('dispo_secu=' + descr_dispo_secu + '&etat_surf=' + descr_etat_surf + '&lum=' + descr_lum + '&athmo=' + descr_athmo + '&lng=' + longitude + '&lat=' + latitude + '&age=' + age + '&date=' + date + '&ville=' + ville);
    ajaxRequest('POST', 'api/index.php/ajout', console.log, data);
    window.location.replace("./index.html");
});