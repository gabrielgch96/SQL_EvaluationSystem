<?php
require_once("DB.php");
/** Access to the quiz_db table */
class Quiz_DB{

    /** Get database data for string $db_name
    * @param int $db_name name of the database to be retrieved
    * @return associative_array table row
    */
    public static function get($db_name) {
        $sql = "SELECT *
                FROM quiz_db
                WHERE db_name = :db_name";
        $data = array(":db_name"=>$db_name);
        $results = DB::getFirst($sql, $data);
        return $results;
    }

    /** Insert into quiz_db table
    * @param string $db_name database name
    * @param string $diagram_path path of db schema image
    * @param string $creation_script_path path where to find script
    * @param string $description short description of the db
    * @return associative_array table row
    */
    public static function insert($db_name, $diagram_path=null,
        $creation_script_path=null, $description=null){
        
        $params = array(
            ":db_name" => $db_name,
            ":diagram_path" => $diagram_path,
            ":creation_script_path" => $creation_script_path,
            ":description" => $description
        );
        $sql = "INSERT INTO quiz_db(db_name, diagram_path,
            creation_script_path, description) VALUES(
            :db_name, :diagram_path, :creation_script_path,
            :description)";
        
        $results = DB::execute($sql, $params);
        return $results;
    }

    /** Delete database from quiz_db
    * @param string $db_name database to be deleted
    * @return associative_array table row
    */
    public static function delete($db_name){
        $sql = "DELETE FROM quiz_db WHERE db_name=:db_name";
        $data = array(":db_name" => $db_name);
        $results = DB::execute($sql, $data);
        return $results;
    }

    /** Get all databases available in the main db
    * @return associative_array table row
    */
    public static function searchAll()
    {
        $sql = "SELECT * FROM quiz_db WHERE 1";
        $results = DB::getAll($sql, array());
        print_r($results);
        return $results;
    }

}
?>