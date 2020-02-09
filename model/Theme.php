<?php
require_once("DB.php");

/** Access to the theme table.*/
class Theme{

    /** Get theme data for id $theme_id
    * @param int $theme_id id of the theme to be retrieved
    * @return associative_array table row
    */
    public static function getById($theme_id){
        $db = DB::getConnection();
        $sql = "SELECT * FROM theme
            WHERE theme_id = :theme_id";
        $stmt = $db->prepare($sql);
        $stmt->bindValue(":theme_id", $theme_id);
        $ok = $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /** INSERT a new theme
    * @param string $label name of the theme
    * @return int # of affected rows
    */
    public static function insert($label){
        $db = DB::getConnection();
        $sql = "INSERT INTO theme(label)
            VALUES(:label)";
        $stmt = $db->prepare($sql);
        $stmt->bindValue(":label", $label);
        $ok = $stmt->execute();
        return $db->lastInsertId();
    }

    /** delete a theme given id $theme_id
    * @param int $theme_id id of theme
    * @return int # of affected rows
    */
    public static function delete($theme_id){
        $params = array(":theme_id" => $theme_id);
        $sql = "DELETE FROM theme WHERE theme_id = :theme_id";
        $results = DB::execute($sql, $params);
        return $results;
    }

    /** Get all themes from database
    * @return associative_array table rows
    */
    public static function getAll(){
        $sql = "SELECT * FROM theme WHERE 1=1";
        $results = DB::getAll($sql, array());
        return $results;
    }

    /** SELECT with param $label to search
    * @param string $label name of the theme
    * @return associative_array table row
    */
    public static function search($label){
        $sql = "SELECT * FROM theme WHERE
            label LIKE :label";
        $params = array(":label" => "%".$label."%");
        $results = DB::getAll($sql, $params);
        return $results;
    }

}

?>