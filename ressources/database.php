<?php
    /**
     * PHP Version 8.2.7
     */

    /*init_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);*/

    require_once 'config.php';
    require_once 'library/exceptions.php';

    class Database {
        protected PDO $PDO;

        /**
         * Connection à la base de données
         */
        public function __construct(){
            # On détermine la base de données SQL à utiliser entre PostgreSQL et MySQL
            if(extension_loaded('pdo_pgsql')){
                $this->PDO = new PDO(
                    'pgsql:host=' . DB_SERVER . ';port=' . DB_PORT . ';dbname=' . DB_NAME,
                    DB_USER,
                    DB_PASSWORD
                );
            } else if(extension_loaded('pdo_mysql')){
                $this->PDO = new PDO(
                    'mysql:host=' . DB_SERVER . ';port=3306;dbname=' . DB_NAME,
                    DB_USER,
                    DB_PASSWORD
                );
            } else {
                throw new PDONotFind();
            }
        } 


    }

?>