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
        protected $PDO;

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


        /**
         * Gets the password hash of a user
         * 
         * @param string $mail
         * 
         * @return string if the password hash exists.
         */
        public function getUserPasswordHash(string $mail): string {
            $mail = strtolower($mail);

            $request = 'SELECT password FROM users WHERE mail = :mail';

            $query = $this->PDO->prepare($request);
            $query->bindParam(':mail', $mail);
            $query->execute();

            $result = $query->fetch(PDO::FETCH_OBJ);

            if(!$result){
                throw new UserNotFound();
            }

            return $result['password'];
        }

        /**
         * Verifies the User credentials
         * 
         * @param string $mail
         * @param string $password
         * 
         * @return bool true if the credentials are correct.
         */
        public function verifyUserCredentials(string $mail, string $password): bool {
            $mail = strtolower($mail);

            $request = 'SELECT password FROM users WHERE mail = :mail';

            $query = $this->PDO->prepare($request);
            $query->bindParam(':mail', $mail);
            $query->execute();

            $result = $query->fetch(PDO::FETCH_OBJ);

            if(!$result){
                throw new UserNotFound();
            }

            return password_verify($password, $result['password']);
        }

        /**
         * Verifies the user acces token
         * 
         * @param string $access_token
         * 
         * @return bool true if the access token is correct.
         */
        public function verifyUserAccessToken(string $access_token): bool {
            
        }
    }
?>