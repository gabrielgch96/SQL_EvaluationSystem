<?php
require_once("DB.php");
/** Access to the quiz_db table */
class Quiz_DB{

    public static function get($db_name) {
        $sql = "SELECT *
                FROM quiz_db
                WHERE db_name = :db_name";
        $data = array(":db_name"=>$db_name);
        $results = DB::getFirst($sql, $data);
        return $results;
    }

    public static function insert(){

    }

    public static function delete($db_name){
        $sql = "DELETE FROM quiz_db WHERE db_name=:db_name";
        $data = array(":db_name" => $db_name);
        $results = DB::execute($sql, $data);
        return $results;
    }

    public static function searchAll()
    {
        $sql = "SELECT * FROM quiz_db WHERE 1";
        $results = DB::getAll($sql, array());
        print_r($results);
        return $results;
    }

}
?>