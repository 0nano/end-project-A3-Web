<?php
    /**
     * PHP Version 8.2.7
     */
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    require_once '../ressources/config.php';
    require_once '../ressources/database.php';
    require_once LIBRARY_PATH . '/redirect.php';
    require_once LIBRARY_PATH . '/exceptions.php';

    $pathInfo = explode('/', trim($_SERVER['PATH_INFO'], '/\\'));

    header('content-type: application/json; charset=utf-8');

    $db = new Database();

    /**
     * Récupération des informations de connexion
     * pour l'accès à la base de données
     */
    function getAuthorizationToken(): ?string{
    
        $headers = getallheaders();
    
        $authorization = $headers['Authorization'];
    
        if (!isset($authorization)) {
            APIErrors::invalidHeader();
        }
    
        $authorization = explode(' ', trim($authorization), 2)[1];
    
        if (empty($authorization)) {
            APIErrors::invalidGrant();
        }
        return $authorization;
    }

    /**
     * Class APIErrors
     * contenant les erreurs possibles de l'API
     */
    class APIErrors{
    
        public static function invalidGrant()
        {
            http_response_code(400);
            die(json_encode(array(
                'error' => 'invalid_grant',
                'error_description' => 'The authorization code is invalid or expired.'
            )));
        }
    
        public static function invalidHeader()
        {
            http_response_code(400);
            die(json_encode(array(
                'error' => 'invalid_header',
                'error_description' => 'The request is missing the Authorization header or the Authorization header is invalid.'
            )));
        }
    
        public static function invalidRequest()
        {
            http_response_code(400);
            die(json_encode(array(
                'error' => 'invalid_request',
                'error_description' => 'The request is missing a parameter, uses an unsupported parameter, uses an invalid parameter or repeats a parameter.'
            )));
        }
    
        public static function invalidCredential()
        {
            http_response_code(400);
            die(json_encode(array(
                'error' => 'invalid_credential',
                'error_description' => 'The request has error(s) in the credentials gave.'
            )));
        }
    
        public static function internalError()
        {
            http_response_code(500);
            die();
        }
    }

    switch ($pathInfo[0] . $_SERVER['REQUEST_METHOD']) {
        case 'descr_athmo' . 'GET' :
            http_response_code(200);
            try {
                die(json_encode($db->getDescrAthmo()));
            } catch (Exception $e) {
                APIErrors::internalError();
            }

        case 'descr_lum' . 'GET' :
            http_response_code(200);
            try {
                die(json_encode($db->getDescrLum()));
            } catch (Exception $e) {
                APIErrors::internalError();
            }
        
        case 'descr_etat_surf' . 'GET' :
            http_response_code(200);
            try {
                die(json_encode($db->getDescrEtatSurf()));
            } catch (Exception $e) {
                APIErrors::internalError();
            }

        case 'descr_dispo_secu' . 'GET' :
            http_response_code(200);
            try {
                die(json_encode($db->getDescrDispoSecu()));
            } catch (Exception $e) {
                APIErrors::internalError();
            }

        case 'accidents' . 'GET' :
            http_response_code(200);
            if (isset($_GET["filtre"])) {
                $filtre = array();
                if (isset($_GET["athmo"])) {
                    $filtre["athmo"] = $_GET["athmo"];
                }
                if (isset($_GET["lum"])) {
                    $filtre["lum"] = $_GET["lum"];
                }
                if (isset($_GET["etat_surf"])) {
                    $filtre["etat_surf"] = $_GET["etat_surf"];
                }
                if (isset($_GET["dispo_secu"])) {
                    $filtre["dispo_secu"] = $_GET["dispo_secu"];
                }
                    
                if (isset($_GET["offset"])) {
                    try {
                        die(json_encode($db->getAllAccidentsWithFiltre($filtre, $_GET["offset"])));
                    } catch (Exception $e) {
                        APIErrors::internalError();
                    }
                } else {
                    try {
                        die(json_encode($db->getAllAccidentsWithFiltre($filtre)));
                    } catch (Exception $e) {
                        APIErrors::internalError();
                    }
                }
            } else {
                if (isset($_GET["offset"])) {
                    try {
                        die(json_encode($db->getAllAccidents($_GET["offset"])));
                    } catch (Exception $e) {
                        APIErrors::internalError();
                    }
                } else {
                    try {
                        die(json_encode($db->getAllAccidents()));
                    } catch (Exception $e) {
                        APIErrors::internalError();
                    }
                }
            }
        
        case 'test' . 'GET' :
            http_response_code(200);
            die(json_encode(array(
                'message' => 'API is working'
            ))); 
        
        default:
            http_response_code(404);
            die();
    }
?>