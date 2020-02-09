<?php
require_once("DB.php");

class SQLQuiz{

    /** Get sql_quiz data for int $quiz_id
    * @param int $db_name name of the database to be retrieved
    * @return associative_array table row
    */
    public static function get($quiz_id) {
        $sql = "SELECT *
                FROM sql_quiz
                WHERE quiz_id = :quiz_id";
        $data = array(":quiz_id"=>$quiz_id);
        $results = DB::getFirst($sql, $data);
        return $results;
    }

    /** Insert into sql_quiz table
    * @param string $title quiz name
    * @param bool $is_public accessibility to users
    * @param int $author_id user id who creates the quiz
    * @param string $db_name target database
    * @return associative_array table row
    */
    public static function insert($title, $is_public,
        $author_id, $db_name){
        
        $params = array(
            ":title" => $title,
            ":is_public" => $is_public,
            ":author_id" => $author_id,
            ":db_name" => $db_name
        );
        $sql = "INSERT INTO sql_quiz(title, is_public,
            author_id, db_name) VALUES(
            :title, :is_public, :author_id,
            :db_name)";
        
        $results = DB::execute($sql, $params);
        return $results;
    }

    /** Delete quiz from sql_quiz
    * @param int $quiz_id identifier of quiz
    * @return associative_array table row
    */
    public static function delete($quiz_id){
        $sql = "DELETE FROM sql_quiz WHERE quiz_id=:quiz_id";
        $data = array(":quiz_id" => $quiz_id);
        $results = DB::execute($sql, $data);
        return $results;
    }

    /** SELECT quizes available in the main db
    * @param string $title quiz name
    * @param bool $is_public accessibility to users
    * @param int $author_id user id who creates the quiz
    * @param string $db_name target database
    * @return associative_array table row
    */
    public static function search($title=null, $only_private = false,
        $db_name = null, $author_id=null)
    {
        $params = array();
        if($only_private)
            $params[":is_public"] = $only_private;
        if($db_name)
            $params[":db_name"] = $db_name;
        if($title)
            $params[":title"] = $title;
        if($author_id)
            $params[":author_id"] = $author_id;

        $sql = "SELECT * FROM sql_quiz WHERE ";
        $params_sql = array();
        if($params){
            foreach($params as $key => $val ){
                    $params_sql[] = str_replace(":","",$key) ."=". $key;
            }
            $sql .= implode (' AND ', $params_sql);
        }
        else
            $sql .= "1";
                
        $results = DB::getAll($sql, array());
        return $results;
    }

}
