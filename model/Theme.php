<?php
require_once("DB.php");

/** Access to the theme table.*/
class Theme{

    public static function getById($theme_id){
        $db = DB::getConnection();
        $sql = "SELECT * FROM theme
            WHERE theme_id = :theme_id";
        $stmt = $db->prepare($sql);
        $stmt->bindValue(":theme_id", $theme_id);
        $ok = $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function insert($label){
        $db = DB::getConnection();
        $sql = "INSERT INTO theme(label)
            VALUES(:label)";
        $stmt = $db->prepare($sql);
        $stmt->bindValue(":label", $label);
        $ok = $stmt->execute();
        return $db->lastInsertId();
    }

    public static function delete($theme_id){

    }

    public static function getAll(){
        $sql = "SELECT * FROM theme WHERE 1=1";
        $res = DB::getAll($sql, null);
        
    }

    public static function search($label = null){

        if($label == null){

        }
        else{

        }
    }

}

?>