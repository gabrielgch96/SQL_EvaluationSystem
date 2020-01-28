<?php
/*
 * Represent a MySQL database access. Get a connection, result of a SELECT query,
 * and executes INSERT, UPDATE and DELETE statement, with parameters.
 * You must extend it in a class which defines your db name, and constants
 * for the trigger raised exceptions.
 * <p>For instance:<pre>
 * class DB extends MySqlDB {
 *   const DB_NAME = "auction";
 *   const BID_AFTER_DEADLINE = 3001;
 * }</pre>
 * <p>Usage:<pre>
 * $rows = DB::getAll("SELECT * FROM person WHERE name LIKE :name", 
 *    [":name" => $name]);<br/>
 * $row = DB::getFirst("SELECT * FROM person WHERE email=:email AND pwd=:pwd",
 *    [":email" => $email, ":pwd" => $pwd]);
 * $affectedNb = DB::execute("DELETE FROM person WHERE person_id=:person_id",
 *    [":person_id" => $personId]);</pre>
 * All these functions and procedures may raise a PDOStatement, which must 
 * be catched in the controller.
 */

class MySqlDB {
  /** Default db name. Override it in your MyDB class */
  const DB_NAME = null;
  
  /** User connecting to the DB. By default, null */
  const USER = null;
  
  /** Password of the default user. By default, null */
  const PASSWORD = null;
  
  // Prefedined MySql integrity exceptions
  const DUPLICATE_ENTRY = 1062;
  const ROW_IS_REFERENCED = 1451;
  const REFERENCED_ROW_NOT_FOUND = 1452;
  // DB name not set
  const DB_NAME_NOT_SET = 9999;

  
  /** Get a connection to a target DB named $dbName, or to the 
   * default db defined in DB or its heir.
   * The connextion is in UTF-8.
   */
  public static function getConnection($dbName = null) {
    $finalName = (get_called_class())::getFixedDbName($dbName);
    // DB configuration
    $dsn = "mysql:host=localhost;dbname=$finalName";
    $user = (get_called_class())::USER;
    $password = (get_called_class())::PASSWORD;
    // Get a DB connection with PDO library
    $bdd = new PDO($dsn, $user, $password, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
    // Set communication in utf-8
    //$bdd->exec("SET character_set_client = 'utf8'");
    // Throw the SQL exceptions
    $bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    return $bdd;
  }

  
  /** Result of a SELECT query as a list of associative arrays; The query
   * would preferably be parameterized. The $params argument will be used to
   * bind the parameters (like :id) to their value.
   * Eg: getAll("SELECT * FROM person WHERE name LIKE :name",
   * [":name" => $name]);
   * 
   * @param type $sql SELECT query to submit
   * @param type $params associative array of parameters (eg: [":id" => $id, ":name" => $name]) 
   * @param type $dbName
   * @return type
   */
  public static function getAll($sql, $params, $dbName = null) {
    $name = (get_called_class())::getFixedDbName($dbName);
    $db = (get_called_class())::getConnection($name);
    $stmt = $db->prepare($sql);
    foreach ($params as $key => $value) {
      $stmt->bindValue($key, $value); // No specific typing
    }
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

  
  /** Result of a SELECT query as an associative array; The query
   * would preferably be parameterized. The $params argument will be used to
   * bind the parameters (like :id) to their value.
   * Eg: getFirst("SELECT * FROM person WHERE email=:email AND pwd=:pwd",
   * [":email" => $email, ":pwd" => $pwd]);
   * 
   * @param type $sql SELECT query to submit
   * @param type $params associative array of parameters (eg: [":id" => $id, ":name" => $name]) 
   * @param type $dbName default DB::DB_NAME
   * @return type
   */
  public static function getFirst($sql, $params, $dbName = null) {
    $name = (get_called_class())::getFixedDbName($dbName);
    $db = (get_called_class())::getConnection($name);
    $stmt = $db->prepare($sql);
    foreach ($params as $key => $value) {
      $stmt->bindValue($key, $value); // No specific typing
    }
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
  }

  
  /** Execute an INSERT, UPDATE or DELETE. The query
   * would preferably be parameterized. The $params argument will be used to
   * bind the parameters (like :id) to their value.
   * Eg: execute("DELETE FROM person WHERE name LIKE :name",
   * [":name" => $name]);
   * 
   * @param type $sql SELECT query to submit
   * @param type $params associative array of parameters (eg: [":id" => $id, ":name" => $name]) 
   * @param type $db
   * @return type number of affected rows
   */
  public static function execute($sql, $params, $dbName = null) {
    $name = (get_called_class())::getFixedDbName($dbName);
    $db = (get_called_class())::getConnection($name);
    $stmt = $db->prepare($sql);
    foreach ($params as $key => $value) {
      $stmt->bindValue($key, $value); // No specific typing
    }
    $stmt->execute();
    return $stmt->rowCount();
  }
  

  /** Returns $dbName fixed: if null, set it to the static value DB_NAME, to
   * be defined in DB heirs as the default db name used in the application.
   * 
   * @param type $dbName
   * @throws PDOException
   */
  protected static function getFixedDbName($dbName) {
    $result = $dbName;
    if ($result == null) {
      $result = (get_called_class())::DB_NAME;
      if ($result == null) {
        throw new PDOException("db name not set", MySqlDB::DB_NAME_NOT_SET);
      }
    }
    return $result;
  }
}
