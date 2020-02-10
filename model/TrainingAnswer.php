<?php
require_once("../model/DB.php");
class TrainingAnswer
{
    public static function get($training_id, $question_id)
    {
        $db = DB::getConnection();
        $sql = "SELECT *
                FROM training
                WHERE training_id = :training_id
                AND question_id = :question_id";
        $data = array(
            ":training_id" => $training_id,
            ":question_id" => $question_id
        );
        $results = DB::getFirst($sql, $data);
        return $results;
    }

    public static function insert(
        $training_id,
        $question_id,
        $answer,
        $result,
        $given_at,
        $rank
    ) {

        $data = array(
            ":training_id" => $training_id,
            ":question_id" => $question_id,
            ":answer" => $answer,
            ":result" => $result,
            ":given_at" => $given_at,
            ":rank" => $rank
        );

        $sql = "INSERT INTO training_answer(training_id, question_id,
                answer, result, given_at, rank) VALUES(:training_id, :question_id,
                :answer, :result, :given_at, :rank)";

        $db = DB::getConnection();
        $stmt = $db->prepare($sql);
        foreach ($data as $key => $value) {
            $stmt->bindValue($key, $value);
        }
        $stmt->execute();
        $id = $db->lastInsertId();
        return $id;
    }

    public static function delete($training_id, $question_id)
    {
        $sql = "DELETE from training_answer 
                WHERE training_id = :training_id
                AND question_id = :question_id";
        $data = array(
            ":training_id" => $training_id,
            ":question_id" => $question_id
        );
        $results = DB::execute($sql, $data);
        return $results;
    }

    public static function update($training_id,$question_id,
        $answer,$result) 
    {
        $sql = "UPDATE training_answer 
        SET answer = :answer, result=:result
        WHERE training_id = :training_id
        AND question_id = :question_id";
        $data = array(
            ":training_id" => $training_id,
            ":question_id" => $question_id,
            ":anser" => $answer,
            ":result" => $result
        );
        $results = DB::execute($sql, $data);
        return $results;
    }

    public static function search($training_id = null, $question_id = null)
    {
        $params = array();
        if (!is_null($training_id))
            $params[":training_id"] = $training_id;
        if (!is_null($question_id))
            $params[":question_id"] = $question_id;

        $sql = "SELECT * FROM training_answer WHERE ";
        $params_sql = array();
        if ($params) {
            foreach ($params as $key => $val) {
                $params_sql[] = str_replace(":", "", $key) . "=" . $key;
            }
            $sql .= implode(' AND ', $params_sql);
        } else
            $sql .= "1";

        return DB::getAll($sql, $params);
    }
}
