<?php
require_once("DB.php");

/** Access to the person table.
 * Put here the methods like getBySomeCriteriaSEarch */
class Person {

   /** Get member data for id $person_id
    * @param int $person_id id of the person to be retrieved
    * @return associative_array table row
    */
   public static function get($person_id) {
      $db = DB::getConnection();
      $sql = "SELECT *
              FROM person
              WHERE person_id = :person_id";
      $stmt = $db->prepare($sql);
      $stmt->bindValue(":person_id", $person_id);
      $ok = $stmt->execute();
      return $stmt->fetch(PDO::FETCH_ASSOC);
   }

   public static function getByLoginPassword($email, $password) {
      $db = DB::getConnection();
      // We should use an encoded password, like PASSWORD(password)
      // in the WHERE clause
      $sql = "SELECT *
            FROM person
            WHERE email = :email AND pwd = :password";
      $stmt = $db->prepare($sql);
      $stmt->bindValue(":email", $email);
      $stmt->bindValue(":pwd", $password);
      $ok = $stmt->execute();
      return $stmt->fetch(PDO::FETCH_ASSOC);
   }

   /* METHOD IS UNTESTED */
   public static function insert($email, $pwd, $name, $first_name, $is_trainer) {
    $db = DB::getConnection();
    $sql = "INSERT INTO person(email,
                pwd, name, first_name, token
                created_at, validated_at, is_trainer)
            VALUES(:email, :pwd, :name, :first_name, 
            null, :created_at, null, :is_trainer)";
    $stmt = $db->prepare($sql);
    $stmt->bindValue(":email", $email);
    $stmt->bindValue(":pwd", $pwd);
    $stmt->bindValue(":name", $name);
    $stmt->bindValue(":first_name", $first_name);
    $stmt->bindValue(":person_id", date("Y-m-d h:i:s"));
    $stmt->bindValue(":is_trainer", $is_trainer);
    $ok = $stmt->execute();
    return $db->lastInsertId();
 }

}

?>