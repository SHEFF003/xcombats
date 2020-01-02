<?php

namespace Core;
class Database {
	
	public static $connection = NULL;
	
	public static function connect() {
		if ( self::$connection == NULL ) {
			try {
				$connection = new \PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME, DB_USER, DB_PASS);
				$connection->exec('SET NAMES CP1251;');
				$connection->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
				self::$connection = $connection;
			} catch ( \PDOException $e ) {
				new GeneralException($e->getMessage(), 10001);
			}
		}
	}
	
	public static function query($q, $p = array(), $fetch_object = false, $fetch_all = false, $count = false) {	
		try {
			if ( self::$connection == NULL ) {
				throw new \PDOException('[Internal Error] Could not establish DB connection');
			}
			$stmt = self::$connection->prepare($q);
			if ( $count !== false ) {
				$stmt->execute($p);
				return $stmt->fetchColumn();
			} elseif ( $fetch_object !== false ) {
				$stmt->execute($p);
				return ($fetch_all !== false ? $stmt->fetchAll(\PDO::FETCH_ASSOC) : $stmt->fetch(\PDO::FETCH_ASSOC));
			} else {
				return $stmt->execute($p);
			}
		} catch ( \PDOException $e ) {
			new GeneralException($e->getMessage(), 10002);
		}
	}
	
	public static function lastID () {
		return self::$connection->lastInsertId();
	}
}
?>