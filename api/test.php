<?php
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    require_once '../ressources/config.php';
    require_once '../ressources/database.php';

    header('content-type: application/json; charset=utf-8');

    $db = new Database();
    $db->predictionCluster();
?>