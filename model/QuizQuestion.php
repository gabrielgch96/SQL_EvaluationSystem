<?php

class QuizQuestion
{

    public static function insert($quiz_id, $question_id, $rank)
    {
        $params = array(
            ":quiz_id" => $quiz_id,
            ":question_id" => $question_id,
            ":rank" => $rank
        );
        $sql = "INSERT INTO sql_quiz_question(quiz_id, 
            question_id, rank) VALUES(
            :quiz_id, :question_id, :rank)";
        return DB::execute($sql, $params);
    }

    public static function delete($quiz_id, $question_id)
    {
        $params = array(
            ":quiz_id" => $quiz_id,
            ":question_id" => $question_id
        );
        $sql = "DELETE FROM sql_quiz_question
            WHERE quiz_id = :quiz_id
            AND  question_id = :question_id";

        return DB::execute($sql, $params);
    }

    public static function getQuizQuestionsFormat($quiz_id){
        $sql = "SELECT s.question_id, s.question_text, s.correct_answer, s.correct_result,
         t.label, (SELECT COUNT(*) AS TIMES FROM sql_quiz_question f JOIN sql_question q
        ON f.question_id=q.question_id WHERE q.question_id= s.question_id) AS quiz_occurrences 
            FROM sql_quiz_question q 
            JOIN sql_question s ON s.question_id=q.question_id
            JOIN theme t ON t.theme_id=s.theme_id 
            WHERE quiz_id = :quiz_id";
        $params = array(":quiz_id" => $quiz_id);

        return DB::getAll($sql, $params);
    }

    public static function getQuizQuestionsIDs($quiz_id){
        $sql = "SELECT question_id FROM sql_quiz_question 
            WHERE quiz_id = :quiz_id";
        $params = array(":quiz_id" => $quiz_id);

        return DB::getAll($sql, $params);
    }
}
