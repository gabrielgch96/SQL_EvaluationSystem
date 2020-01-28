<?php
require_once("DB.php");

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
    $stmt = $db->prepare($sql);
    $stmt->bindValue(":question_id", $question_id);
    $ok = $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
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
    public static function insert($db_name, $question_text,
        $correct_answer, $correct_result, $is_public, 
        $theme_id, $author_id){
        $db = DB::getConnection();
        $sql = "INSERT INTO sql_question(
                db_name, question_text, correct_answer,
                correct_result, is_public, theme_id, 
                author_id, created_at) VALUES(
                :db_name, :question_text, :correct_answer,
                :correct_result, :is_public, :theme_id, 
                :author_id, :created_at)
            ";
        $stmt = $db->prepare($sql);
        $stmt->bindValue(":db_name", $db_name);
        $stmt->bindValue(":question_text", $question_text);
        $stmt->bindValue(":correct_answer", $correct_answer);
        $stmt->bindValue(":correct_result", $correct_result);
        $stmt->bindValue(":is_public", $is_public);
        $stmt->bindValue(":theme_id", $theme_id);
        $stmt->bindValue(":author_id", $author_id);
        $stmt->bindValue(":created_at", date("Y-m-d h:i:s"));

        $ok = $stmt->execute();
        return $db->lastInsertId();
    }

}

?>