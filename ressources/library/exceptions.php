<?php

    /**
     * This exception is thrown when the authentication failed.
     */
    class AuthenticationException extends Exception {}
    
    /**
     * This exception is thrown when the PDO doesn't work with PostgreSQL and MySQL. 
     */
    class PDONotFind extends Exception {}

    /**
     * This exception is thrown when the user is not found in the database.
     */
    class UserNotFound extends Exception {}

    /**
     * This exception is thrown when the user doesn't have an access token.
     */
    class UserAccessTokenNotFound extends Exception {}

    /**
     * This exception is thrown when there is a problem with the connection to the database.
     */
    class ConnectionException extends Exception {}

    /**
     * This exception is thrown when the python script does not work. 
     */
    class PythonScriptException extends Exception {}
?>