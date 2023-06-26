<?php

    /**
     * PHP version 8.2.7
     */

    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    // Paramètres de connexion à la base de données
    const DB_SERVER = '127.0.0.1';
    const DB_PORT = '5432';
    const DB_NAME = 'web_accident';
    const DB_USER = 'postgres';
    const DB_PASSWORD = '';

    // Constante pour l'access token
    const ACCESS_TOKEN_NAME = 'web_accident_session';

    /*
        Définition du chemin vers le dossier library
        ex. require_once(LIBRARY_PATH . "exceptions.php")
    */
    defined("LIBRARY_PATH")
        or define("LIBRARY_PATH", realpath(dirname(__FILE__) . '/library'));

?>