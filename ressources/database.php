<?php
    /**
     * PHP Version 8.2.7
     */

    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    require_once 'config.php';
    require_once 'library/exceptions.php';

    class Database {
        protected $PDO;
        protected $bd;

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
                $this->bd = 'pgsql';
            } else if(extension_loaded('pdo_mysql')){
                $this->PDO = new PDO(
                    'mysql:host=' . DB_SERVER . ';port=3306;dbname=' . DB_NAME . ';charset=utf8',
                    DB_USER,
                    DB_PASSWORD
                );
                $this->bd = 'mysql';
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
                $this->verifyUserCredentials($mail, $password);
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
                $this->verifyUserAccessToken($access_token);
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

            $result = $query->fetchAll(PDO::FETCH_ASSOC);

            if(!$result){
                throw new ConnectionException();
            }

            return $result;
        }

        /**
         * Gets all the luminosity descriptions
         * 
         * @return array of all the athmosphere description.
         * 
         * @throws ConnectionException if the array is empty.
         */
        public function getDescrLum(): array {
            $request = 'SELECT * FROM descr_lum';

            $query = $this->PDO->prepare($request);
            $query->execute();

            $result = $query->fetchAll(PDO::FETCH_ASSOC);

            if(!$result){
                throw new ConnectionException();
            }

            return $result;
        }

        /**
         * Gets all the surface descriptions
         * 
         * @return array of all the athmosphere description.
         * 
         * @throws ConnectionException if the array is empty.
         */
        public function getDescrEtatSurf(): array {
            $request = 'SELECT * FROM descr_etat_surf';

            $query = $this->PDO->prepare($request);
            $query->execute();

            $result = $query->fetchAll(PDO::FETCH_ASSOC);

            if(!$result){
                throw new ConnectionException();
            }

            return $result;
        }

        /**
         * Gets all the security descriptions
         * 
         * @return array of all the athmosphere description.
         * 
         * @throws ConnectionException if the array is empty.
         */
        public function getDescrDispoSecu(): array {
            $request = 'SELECT * FROM descr_dispo_secu';

            $query = $this->PDO->prepare($request);
            $query->execute();

            $result = $query->fetchAll(PDO::FETCH_ASSOC);

            if(!$result){
                throw new ConnectionException();
            }

            return $result;
        }

        /**
         * Gets all the accidents descriptions
         * left join by all the descriptions tables
         * 
         * @param int $offset the offset of the request to get the next 20 accidents.
         * 
         * @return array of all the accidents description.
         * 
         * @throws ConnectionException if the array is empty.
         */
        public function getAllAccidents(int $offset = 0): ?array {
            if ($this->bd == 'pgsql'){
                $request = 'SELECT id_accident , Num_Acc , date, age , id_code_insee , ville ,
                        latitude , longitude , descr_grav , department_number ,
                        department_name , region_number , descr_athmo , a.description "athmo_descr", 
                        descr_lum, l.description "lum_descr", descr_etat_surf , es.description "etat_surf_descr",
                        descr_dispo_secu , ds.description "dispo_secu_descr" FROM accident
                        LEFT JOIN descr_athmo a ON descr_athmo = id_athmo
                        LEFT JOIN descr_lum l ON descr_lum = id_lum
                        LEFT JOIN descr_etat_surf es ON descr_etat_surf = id_surf
                        LEFT JOIN descr_dispo_secu ds ON descr_dispo_secu = id_secu 
                        LIMIT 20 OFFSET :debut';
            }else {
                $request = 'SELECT id_accident , Num_Acc , date, age , id_code_insee , ville ,
                latitude , longitude , descr_grav , department_number ,
                department_name , region_number , descr_athmo , a.description "athmo_descr", 
                descr_lum, l.description "lum_descr", descr_etat_surf , es.description "etat_surf_descr",
                descr_dispo_secu , ds.description "dispo_secu_descr" FROM accident
                LEFT JOIN descr_athmo a ON descr_athmo = id_athmo
                LEFT JOIN descr_lum l ON descr_lum = id_lum
                LEFT JOIN descr_etat_surf es ON descr_etat_surf = id_surf
                LEFT JOIN descr_dispo_secu ds ON descr_dispo_secu = id_secu 
                GROUP BY id_accident LIMIT 20 OFFSET :debut';
            }

            $finalOffset = $offset * 20;

            $query = $this->PDO->prepare($request);
            $query->bindParam(':debut', $finalOffset, PDO::PARAM_INT);
            $query->execute();

            $result = $query->fetchAll(PDO::FETCH_ASSOC);

            if(!$result){
                throw new ConnectionException();
            }

            return $result;
        }

        /**
         * Gets all the accidents descriptions for map page
         * 
         * @param int $offset the offset of the request to get the next 10000 accidents.
         * 
         * @return array of all the accidents description.
         * 
         * @throws ConnectionException if the array is empty.
         */
        public function getAllAccidentsBig(int $offset = 0): ?array {
            if ($this->bd == 'pgsql'){
                $request = 'SELECT id_accident, ville , latitude , longitude FROM accident
                        LEFT JOIN descr_athmo a ON descr_athmo = id_athmo
                        LEFT JOIN descr_lum l ON descr_lum = id_lum
                        LEFT JOIN descr_etat_surf es ON descr_etat_surf = id_surf
                        LEFT JOIN descr_dispo_secu ds ON descr_dispo_secu = id_secu
                        LIMIT 80000 OFFSET :debut';
            }else {
                $request = 'SELECT id_accident, ville , latitude , longitude FROM accident
                LEFT JOIN descr_athmo a ON descr_athmo = id_athmo
                LEFT JOIN descr_lum l ON descr_lum = id_lum
                LEFT JOIN descr_etat_surf es ON descr_etat_surf = id_surf
                LEFT JOIN descr_dispo_secu ds ON descr_dispo_secu = id_secu 
                GROUP BY id_accident LIMIT 80000 OFFSET :debut';
            }

            $finalOffset = $offset * 80000;

            $query = $this->PDO->prepare($request);
            $query->bindParam(':debut', $finalOffset, PDO::PARAM_INT);
            $query->execute();

            $result = $query->fetchAll(PDO::FETCH_ASSOC);

            if(!$result){
                throw new ConnectionException();
            }

            return $result;
        }

        /**
         * Gets all the accidents descriptions
         * left join by all the descriptions tables
         * with a filtre on all descriptions tables
         * 
         * @param array $filtre
         * 
         * @param int $offset the offset of the request to get the next 20 accidents.
         * 
         * @return ?array of all the accidents description.
         * 
         * @throws ConnectionException if the array is empty.
         */
        public function getAllAccidentsWithFiltre(array $filtre, int $offset = 0): ?array {
            $conditins = '';
            
            if (isset($filtre['athmo']) && $filtre['athmo'] != '') {
                $conditins .= 'descr_athmo = :athmo';
            }
            if (isset($filtre['lum']) && $filtre['lum'] != '') {
                if ($conditins != '') {
                    $conditins .= ' AND ';
                }
                $conditins .= 'descr_lum = :lum';
            }
            if (isset($filtre['etat_surf']) && $filtre['etat_surf'] != '') {
                if ($conditins != '') {
                    $conditins .= ' AND ';
                }
                $conditins .= 'descr_etat_surf = :etat_surf';
            }
            if (isset($filtre['dispo_secu']) && $filtre['dispo_secu'] != '') {
                if ($conditins != '') {
                    $conditins .= ' AND ';
                }
                $conditins .= 'descr_dispo_secu = :dispo_secu';
            }

            if ($this->bd == 'pgsql'){
                $conditins .= ' LIMIT 20 OFFSET :debut';
            }else {
                $conditins .= 'group by id_accident LIMIT 20 OFFSET :debut';
            }

            $request = 'SELECT id_accident , Num_Acc , date, age , id_code_insee , ville ,
                        latitude , longitude , descr_grav , department_number ,
                        department_name , region_number , descr_athmo , a.description "athmo_descr", 
                        descr_lum, l.description "lum_descr", descr_etat_surf , es.description "etat_surf_descr",
                        descr_dispo_secu , ds.description "dispo_secu_descr" FROM accident
                        LEFT JOIN descr_athmo a ON descr_athmo = id_athmo
                        LEFT JOIN descr_lum l ON descr_lum = id_lum
                        LEFT JOIN descr_etat_surf es ON descr_etat_surf = id_surf
                        LEFT JOIN descr_dispo_secu ds ON descr_dispo_secu = id_secu';

            $finalOffset = $offset * 20;

            $query = $this->PDO->prepare($request. ' WHERE ' . $conditins);
            if (isset($filtre['athmo']) && $filtre['athmo'] != '') {
                $query->bindParam(':athmo', $filtre['athmo']);
            }
            if (isset($filtre['lum']) && $filtre['lum'] != '') {
                $query->bindParam(':lum', $filtre['lum']);
            }
            if (isset($filtre['etat_surf']) && $filtre['etat_surf'] != '') {
                $query->bindParam(':etat_surf', $filtre['etat_surf']);
            }
            if (isset($filtre['dispo_secu']) && $filtre['dispo_secu'] != '') {
                $query->bindParam(':dispo_secu', $filtre['dispo_secu']);
            }
            $query->bindParam(':debut', $finalOffset, PDO::PARAM_INT);
            $query->execute();

            $result = $query->fetchAll(PDO::FETCH_ASSOC);

            if(!$result){
                throw new ConnectionException();
            }

            return $result;
        }

        /**
         * Gets the length of getAllAccidents
         * 
         * @return int the length of getAllAccidents
         * 
         * @throws ConnectionException if the array is empty.
         */
        public function getAllAccidentsLength(): int {
            $request = 'SELECT COUNT(*) "n" FROM accident';

            $query = $this->PDO->prepare($request);
            $query->execute();

            $result = $query->fetch(PDO::FETCH_ASSOC);

            if(!$result){
                throw new ConnectionException();
            }

            return $result['n'];
        }

        /**
         * Gets the length of getAllAccidentsWithFiltre
         * 
         * @param array $filtre
         * 
         * @return int the length of getAllAccidentsWithFiltre
         * 
         * @throws ConnectionException if the array is empty.
         */
        public function getAllAccidentsWithFiltreLength(array $filtre): int {
            $conditins = '';
            
            if (isset($filtre['athmo']) && $filtre['athmo'] != '') {
                $conditins .= 'descr_athmo = :athmo';
            }
            if (isset($filtre['lum']) && $filtre['lum'] != '') {
                if ($conditins != '') {
                    $conditins .= ' AND ';
                }
                $conditins .= 'descr_lum = :lum';
            }
            if (isset($filtre['etat_surf']) && $filtre['etat_surf'] != '') {
                if ($conditins != '') {
                    $conditins .= ' AND ';
                }
                $conditins .= 'descr_etat_surf = :etat_surf';
            }
            if (isset($filtre['dispo_secu']) && $filtre['dispo_secu'] != '') {
                if ($conditins != '') {
                    $conditins .= ' AND ';
                }
                $conditins .= 'descr_dispo_secu = :dispo_secu';
            }

            $request = 'SELECT count(id_accident) "n" FROM accident
                        LEFT JOIN descr_athmo a ON descr_athmo = id_athmo
                        LEFT JOIN descr_lum l ON descr_lum = id_lum
                        LEFT JOIN descr_etat_surf es ON descr_etat_surf = id_surf
                        LEFT JOIN descr_dispo_secu ds ON descr_dispo_secu = id_secu';

            $query = $this->PDO->prepare($request. ' WHERE ' .$conditins);
            if (isset($filtre['athmo']) && $filtre['athmo'] != '') {
                $query->bindParam(':athmo', $filtre['athmo']);
            }
            if (isset($filtre['lum']) && $filtre['lum'] != '') {
                $query->bindParam(':lum', $filtre['lum']);
            }
            if (isset($filtre['etat_surf']) && $filtre['etat_surf'] != '') {
                $query->bindParam(':etat_surf', $filtre['etat_surf']);
            }
            if (isset($filtre['dispo_secu']) && $filtre['dispo_secu'] != '') {
                $query->bindParam(':dispo_secu', $filtre['dispo_secu']);
            }
            $query->execute();

            $result = $query->fetch(PDO::FETCH_ASSOC);

            if(!$result){
                throw new ConnectionException();
            }

            return $result['n'];
        }
    
        /**
         * Gets the cluster of a given latitude and longitude.
         * 
         * @param float $latitude
         * @param float $longitude
         * 
         * @return string the cluster of a given latitude and longitude.
         * 
         * @throws PythonScriptException if the python script is empty.
         */
        public function predictionCluster(float $latitude, float $longitude): string{
            if (!isset($latitude) || !isset($longitude)){
                throw new InvalidArgumentException();
            }

            exec("python3 ../scrpits/pred_cluster.py " . $latitude . " " . $longitude . " ../ressources/centroids.csv", $output);

            if (empty($output)) {
                throw new PythonScriptException();
            }
            
            $output = json_decode($output[0]);
            return json_encode($output);
        }      
        
        /**
         * Adds an accident to the database.
         * 
         * @param array $accident
         * 
         * @return bool true if the accident has been added, false otherwise.
         * 
         * @throws ConnectionException if the array is empty.
         */
        public function addAccident($accident): bool {

        }

        /**
         * 
         */
        public function getAllGravite(int $id): string {
            if (!isset($id)){
                throw new InvalidArgumentException();
            }

            $request = 'SELECT descr_athmo, descr_lum, descr_etat_surf, age, descr_dispo_secu FROM accident WHERE id_accident = :id';

            $query = $this->PDO->prepare($request);
            $query->bindParam(':id', $id);
            $query->execute();

            $result = $query->fetch(PDO::FETCH_ASSOC);

            // if(!$result){
            //     throw new ConnectionException();
            // }
            
            $output = [];
            
            exec("python3 ../scrpits/partie3.py " . $result['descr_athmo'] . "," . $result['descr_lum'] . "," . $result['descr_etat_surf'] . "," . $result['age'] . "," . $result['descr_dispo_secu'] . " RF", $rf_output, $bla);
            print_r($rf_output);
            $output['RF'] = $rf_output;

            exec("python3 ../scrpits/partie3.py " . $result['descr_athmo'] . "," . $result['descr_lum'] . "," . $result['descr_etat_surf'] . "," . $result['age'] . "," . $result['descr_dispo_secu'] . " SVM", $svm_output);
            $output['SVM'] = $svm_output;

            exec("python3 ../scrpits/partie3.py " . $result['descr_athmo'] . "," . $result['descr_lum'] . "," . $result['descr_etat_surf'] . "," . $result['age'] . "," . $result['descr_dispo_secu'] . " MLP", $mlp_output);
            $output['MLP'] = $mlp_output;

            return json_encode($output);
        }
    }
?>