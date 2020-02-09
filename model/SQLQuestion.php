<?php
require_once("../model/DB.php");

/** Access to the sql_question table.*/
class SQLQuestion {

    /** Get question data for id $question_id
    * @param int $question_id id of the question to be retrieved
    * @return associative_array table row
    */
   public static function get($question_id) {
        $db = DB::getConnection();
        $sql = "SELECT *
                FROM sql_question
                WHERE question_id = :question_id";
        $data = array(":question_id"=>$question_id);
        $results = DB::getFirst($sql, $data);
        return $results;
    }

    /** Insert question data 
    * @param string $db_name name of the db the question relates to
    * @param string $question_text question being made
    * @param string $correct_answer SQL solution code
    * @param string $correct_result correct query result
    * @param int $is_public visibility to public
    * @param int $theme_id relates to theme table id
    * @param int $author_id identifier from user
    * @return int id of inserted question
    */
    public static function insert($question){
        $sql = "INSERT INTO sql_question(
                db_name, question_text, correct_answer,
                correct_result, is_public, theme_id, 
                author_id, created_at) VALUES(
                :db_name, :question_text, :correct_answer,
                :correct_result, :is_public, :theme_id, 
                :author_id, :created_at)
            ";
        $data = array(":db_name"=>$question["db_name"],
            ":question_text"=>$question["question_text"],
            ":correct_answer"=>$question["correct_answer"],
            ":correct_result"=>$question["correct_result"],
            ":is_public"=>$question["is_public"],
            ":theme_id"=>$question["theme_id"],
            ":author_id"=>$question["author_id"],
            ":created_at"=>date("Y-m-d h:i:s"));

        $db = DB::getConnection();
        $stmt = $db->prepare($sql);
        foreach ($data as $key => $value) {
            $stmt->bindValue($key, $value); // No specific typing
          }
        $stmt->execute();
        $id = $db->lastInsertId();
        return $id;
    }

    /** Update question data 
    * @param string $question_text question being made
    * @param string $correct_answer SQL solution code
    * @param string $correct_result correct query result
    * @param int $is_public visibility to public
    * @param int $theme_id relates to theme table id
    * @param int $question_id unique identifier for question
    * @return int number of affected rows
    */
    public static function update($question_id, $question_text,
    $correct_answer, $correct_result, $is_public, $theme_id)
    {
        $sql = "UPDATE sql_question SET question_text = :question_text,
        correct_answer=:correct_answer,correct_result =:correct_result,
        is_public = :is_public, theme_id=:theme_id
        WHERE question_id = :question_id";
        $data = array(
        ":question_text"=>$question_text,
        ":correct_answer"=>$correct_answer,
        ":correct_result"=>$correct_result,
        ":is_public"=>$is_public,
        ":theme_id"=>$theme_id,
        ":question_id" => $question_id);

        $results = DB::execute($sql, $data);
        return $results;
    }

    /** Get question data for id $question_id
    * @param int $question_id id of the question to be retrieved
    * @return int number of rows affected
    */
    public static function delete($question_id){
        $sql = "DELETE from sql_question 
                WHERE question_id = :question_id";
        $data = array(":question_id"=> $question_id);
        $results = DB::execute($sql, $data);
        return $results;
    }

    public static function search($database = null, $only_private = false, 
        $theme_id = null){

        $params = array();
        if($only_private)
            $params[":is_public"] = $only_private;
        if($database)
            $params[":db_name"] = $database;
        if($theme_id)
            $params[":theme_id"] = $theme_id;

        $sql = "SELECT * FROM sql_question WHERE ";
        $params_sql = array();
        if($params){
            foreach($params as $key => $val ){
                    $params_sql[] = str_replace(":","",$key) ."=". $key;
            }
            $sql .= implode (' AND ', $params_sql);
        }
        else
            $sql .= "1";
        
        return DB::getAll($sql, $params);
    }

}

?>