var id = 1;

ajaxRequest('GET', 'api/index.php/gravite', getAllGravite, `prediction-select&id=${id}`);

function getAllGravite(data) {
    console.log(data);
}