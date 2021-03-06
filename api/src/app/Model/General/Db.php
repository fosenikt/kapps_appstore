<?php
namespace Kapps\Model\General;

use \mysqli;



/**
 * Singelton class for connection to MySQL
 * 
 * Singelton in OOP is highly debated, but it makes sence for a global connection for all classes,
 * to prevent multiple SQL connections to the database.
 * 
 * Resources:
 * - https://stackoverflow.com/questions/23071427/singleton-class-for-database-mysqli-phpversion-5-2-17
 * - https://phpenthusiast.com/blog/the-singleton-design-pattern-in-php
 * 
 */
class Db extends mysqli {

	private static $instance = null;

	private function __construct($host, $user, $password, $database, $port){ 
		parent::__construct($host, $user, $password, $database, $port);
	}

	public static function getInstance(){
		if (self::$instance == null){
			//error_log('Creating new database connection');
			@self::$instance = new self(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME, DB_PORT);
		}

		if (self::$instance->connect_errno != 0) {
			return array(
				'connect_errno' => self::$instance->connect_errno,
				'connect_error' => self::$instance->connect_error,
			);
		}

		else {
			mysqli_set_charset(self::$instance, "utf8");
			return self::$instance;
		}

		
	}


	public function memcache() {
        $cache = new \Memcached();
        $cache->addServer(MEMCACHED_HOST, MEMCACHED_PORT);

		return $cache;
	}
}