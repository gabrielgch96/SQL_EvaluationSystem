<?php
require_once("DB.php");

/** Access to the person table.
 * Put here the methods like getBySomeCriteriaSEarch */
class Person
{

   /** Get member data for id $person_id
    * @param int $person_id id of the person to be retrieved
    * @return associative_array table row
    */
   public static function get($person_id)
   {
      $db = DB::getConnection();
      $sql = "SELECT *
              FROM person
              WHERE person_id = :person_id";
      $stmt = $db->prepare($sql);
      $stmt->bindValue(":person_id", $person_id);
      $ok = $stmt->execute();
      return $stmt->fetch(PDO::FETCH_ASSOC);
   }

   public static function getByLoginPassword($email, $password)
   {
      $db = DB::getConnection();
      // We should use an encoded password, like PASSWORD(password)
      // in the WHERE clause
      $sql = "SELECT *
            FROM person
            WHERE email = :email AND pwd = :pwd";
      $stmt = $db->prepare($sql);
      $stmt->bindValue(":email", $email);
      $stmt->bindValue(":pwd", $password);
      $ok = $stmt->execute();
      return $stmt->fetch(PDO::FETCH_ASSOC);
   }

   /* METHOD IS UNTESTED */
   public static function insert($email, $pwd, $name, $first_name, $is_trainer)
   {
      $db = DB::getConnection();
      $sql = "INSERT INTO person(email,
                pwd, name, first_name,
                created_at, is_trainer)
            VALUES(:email, :pwd, :name, :first_name, 
             :created_at, :is_trainer)";
      $stmt = $db->prepare($sql);
      $stmt->bindValue(":email", $email);
      $stmt->bindValue(":pwd", $pwd);
      $stmt->bindValue(":name", $name);
      $stmt->bindValue(":first_name", $first_name);
      $stmt->bindValue(":created_at", date("Y-m-d h:i:s"));
      $stmt->bindValue(":is_trainer", $is_trainer);
      $ok = $stmt->execute();
      return $db->lastInsertId();
   }

   public static function validate($id)
   {
      $sql = "UPDATE person SET validated_at= :val
         WHERE person_id=:person_id";
      $params = array(":person_id" => $id,
      ":val"=> date("Y-m-d h:i:s") );
      DB::execute($sql, $params);
   }
}
