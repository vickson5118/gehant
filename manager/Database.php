<?php
namespace manager;

use Exception;
use PDO;
use utils\Constants;

class Database {
    private static $_instance;

    public static function getInstance() {
            if ( is_null(self::$_instance) ) {
                
                try {
                    
                    if($_SERVER['SERVER_NAME'] == "gehant.local"){
                        
                        self::$_instance = new PDO("mysql:host=" . Constants::MYSQL_HOST . ";dbname=" . Constants::DBNAME . ";charset=utf8", Constants::USERNAME, Constants::PASSWORD, array(
                            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
                        ));
                        
                    }else{
                        
                        self::$_instance = new PDO("mysql:host=" . Constants::MYSQL_HOST_ONLINE . ";dbname=" . Constants::DBNAME_ONLINE
                            . ";charset=utf8", Constants::USERNAME_ONLINE, Constants::PASSWORD_ONLINE, array(
                                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
                            ));
                        
                    } 
                    
                } catch ( Exception $e ) {
                    die("Erreur" . $e->getMessage());
                }
            }
        
        
        return self::$_instance;
    }
}

