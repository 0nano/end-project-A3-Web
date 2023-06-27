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
         * 
         * @throws PDONotFind si le PDO ne fonctionne pas avec PostgreSQL et MySQL.
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
         * 
         * @throws UserNotFound if the user is not found in the database.
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
         * 
         * @throws AuthenticationException if the authentication failed.
         */
        public function verifyUserCredentials(string $mail, string $password): bool {
            $mail = strtolower($mail);

            $request = 'SELECT password FROM users WHERE mail = :mail';

            $query = $this->PDO->prepare($request);
            $query->bindParam(':mail', $mail);
            $query->execute();

            $result = $query->fetch(PDO::FETCH_OBJ);

            if(!$result){
                throw new AuthenticationException();
            }

            return password_verify($password, $result['password']);
        }

        /**
         * Verifies the user acces token
         * 
         * @param string $access_token
         * 
         * @return bool true if the access token is correct.
         * 
         * @throws UserNotFound if the user is not found in the database.
         */
        public function verifyUserAccessToken(string $access_token): bool {
            $request = 'SELECT * FROM users WHERE access_token = :access_token';

            $query = $this->PDO->prepare($request);
            $query->bindParam(':access_token', $access_token);
            $query->execute();

            $result = $query->fetch(PDO::FETCH_OBJ);

            if(!$result){
                throw new UserNotFound();
            }

            return true;
        }

        /**
         * Connects the user by returning its unique id if
         * the credentials are correct.
         * 
         * @param string $mail
         * @param string $password
         * @param int $session_duration (optional) The lifetime of the session in seconds.
         * 
         * @throws AuthenticationException if the credentials are incorrect.
         * @throws ConnectionException if the database is not updated.
         */
        public function connectUser(string $mail, string $password, int $session_duration = 0): bool {
            
            // We verify the credentials
            try {
                $this->verifyUserCredentials($mail, $password);
            } catch (Exception $e) {
                throw $e;
            }

            // Make the mail lowercase
            $mail = strtolower($mail);

            // Generate the access token
            $access_token = hash('sha256', $mail . $password . microtime(true));

            // Set session hash as access token in the database
            $request = 'UPDATE users SET access_token = :access_token WHERE mail = :mail';

            $query = $this->PDO->prepare($request);
            $query->bindParam(':access_token', $access_token);
            $query->bindParam(':mail', $mail);
            $result = $query->execute();

            if(!$result){
                throw new ConnectionException();
            }

            if($session_duration > 0){
                $session_duration = time() + $session_duration;
            }

            // Set the session cookie
            return setcookie(
                ACCESS_TOKEN_NAME,
                $access_token,
                $session_duration,
                "/"
            );
        }

        /**
         * Create an access token if the credentials are valid
         * the functionnality is the same as connectUser but
         * this function is related to AJAX REST API.
         * 
         * @param string $mail
         * @param string $password
         * 
         * @return string the access token.
         * 
         * @throws AuthenticationException if the credentials are incorrect.
         * @throws ConnectionException if the database is not updated.
         */
        public function getUserAccessToken(string $mail, string $password): string {
            try {
                this->verifyUserCredentials($mail, $password);
            } catch (Exception $e) {
                throw $e;
            }

            $mail = strtolower($mail);

            $access_token = hash('sha256', $mail . $password . microtime(true));

            // Set session hash as access token in the database
            $request = 'UPDATE users SET access_token = :access_token WHERE mail = :mail';

            $query = $this->PDO->prepare($request);
            $query->bindParam(':access_token', $access_token);
            $query->bindParam(':mail', $mail);
            $result = $query->execute();

            if(!$result){
                throw new ConnectionException();
            }

            return $access_token;
        }

        /**
         * Disconnects the user by deleting its access token
         * 
         * @param string $access_token
         * 
         * @return bool true if the user is disconnected.
         * 
         * @throws UserNotFound if the access token is incorrect.
         * @throws ConnectionException if the access token is correct but the database is not updated.
         */
        public function disconnectUser($access_token): bool {
            try {
                this->verifyUserAccessToken($access_token);
            } catch (Exception $e) {
                throw $e;
            }

            // Remove the access token from the database
            $request = 'UPDATE users SET access_token = NULL WHERE access_token = :access_token';

            $query = $this->PDO->prepare($request);
            $query->bindParam(':access_token', $access_token);
            $result = $query->execute();

            if(!$result){
                throw new ConnectionException();
            }

            unset($_COOKIE[ACCESS_TOKEN_NAME]); 
            setcookie(ACCESS_TOKEN_NAME, '', -1, '/');
            return $success;
        }

        /**
         * Gets all the athmosphere descriptions
         * 
         * @return array of all the athmosphere description.
         * 
         * @throws ConnectionException if the array is empty.
         */
        public function getDescrAthmo(): array {
            $request = 'SELECT * FROM descr_athmo';

            $query = $this->PDO->prepare($request);
            $query->execute();

            $result = $query->fetchAll(PDO::FETCH_OBJ);

            if(!$result){
                throw new ConnectionException();
            }

            return $result;
        }

        /**
         * Gets all the athmosphere descriptions
         * 
         * @return array of all the athmosphere description.
         * 
         * @throws ConnectionException if the array is empty.
         */
        public function getDescrLum(): array {
            $request = 'SELECT * FROM descr_lum';

            $query = $this->PDO->prepare($request);
            $query->execute();

            $result = $query->fetchAll(PDO::FETCH_OBJ);

            if(!$result){
                throw new ConnectionException();
            }

            return $result;
        }

        /**
         * Gets all the athmosphere descriptions
         * 
         * @return array of all the athmosphere description.
         * 
         * @throws ConnectionException if the array is empty.
         */
        public function getDescrEtatSurf(): array {
            $request = 'SELECT * FROM descr_etat_surf';

            $query = $this->PDO->prepare($request);
            $query->execute();

            $result = $query->fetchAll(PDO::FETCH_OBJ);

            if(!$result){
                throw new ConnectionException();
            }

            return $result;
        }

        /**
         * Gets all the athmosphere descriptions
         * 
         * @return array of all the athmosphere description.
         * 
         * @throws ConnectionException if the array is empty.
         */
        public function getDescrDispoSecu(): array {
            $request = 'SELECT * FROM descr_dispo_secu';

            $query = $this->PDO->prepare($request);
            $query->execute();

            $result = $query->fetchAll(PDO::FETCH_OBJ);

            if(!$result){
                throw new ConnectionException();
            }

            return $result;
        }

        
    }
?>