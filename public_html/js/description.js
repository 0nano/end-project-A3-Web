var data;

ajaxRequest('GET', 'api/index.php/descr_athmo', function(response) {
  data = response;
  console.log(data);
  populateSelectOptions(data);
});

function populateSelectOptions(data) {
  var selectElement = document.getElementById('descr_atmo');

  // Parcourir chaque valeur du tableau "data" et créer une option pour chaque valeur
  for (var i = 0; i < data.length; i++) {
    var option = document.createElement('option');
    option.value = data[i]['id_athmo'];
    option.textContent = data[i]['description'];
    selectElement.appendChild(option);
  }
}




ajaxRequest('GET', 'api/index.php/descr_lum', function(response) {
    data = response;
    console.log(data);
    populateSelectOptions_lum(data);
  });
  
  function populateSelectOptions_lum(data) {
    var selectElement_lum = document.getElementById('descr_lum');

    // Parcourir chaque valeur du tableau "data" et créer une option pour chaque valeur
    for (var i = 0; i < data.length; i++) {
      var option = document.createElement('option');
      option.value = data[i]['id_lum'];
      option.textContent = data[i]['description'];
      selectElement_lum.appendChild(option);
    }
  }


  

ajaxRequest('GET', 'api/index.php/descr_etat_surf', function(response) {
    data = response;
    console.log(data);
    populateSelectOptions_etat_surf(data);
  });
  
  function populateSelectOptions_etat_surf(data) {
    var selectElement = document.getElementById('descr_etat_surf');

    // Parcourir chaque valeur du tableau "data" et créer une option pour chaque valeur
    for (var i = 0; i < data.length; i++) {
      var option = document.createElement('option');
      option.value = data[i]['id_surf'];
      option.textContent = data[i]['description'];
      selectElement.appendChild(option);
    }
  }


  

ajaxRequest('GET', 'api/index.php/descr_dispo_secu', function(response) {
    data = response;
    console.log(data);
    populateSelectOptions_descr_dispo_secu(data);
  });
  
  function populateSelectOptions_descr_dispo_secu(data) {
    var selectElement = document.getElementById('descr_dispo_secu');

    // Parcourir chaque valeur du tableau "data" et créer une option pour chaque valeur
    for (var i = 0; i < data.length; i++) {
      var option = document.createElement('option');
      option.value = data[i]['id_secu'];
      option.textContent = data[i]['description'];
      selectElement.appendChild(option);
    }
  }