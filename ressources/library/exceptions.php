<?php

    /**
     * This exception is thrown when the authentication failed.
     */
    class AuthenticationException extends Exception {}
    
    /**
     * This exception is thrown when the PDO doesn't work with PostgreSQL and MySQL. 
     */
    class PDONotFind extends Exception {}
?>