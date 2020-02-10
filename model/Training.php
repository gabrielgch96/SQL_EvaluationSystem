<?php
require_once("../model/DB.php");
class Training{

    public static function get($training_id){
        $db = DB::getConnection();
        $sql = "SELECT *
                FROM training
                WHERE training_id = :training_id";
        $data = array(":training_id"=>$training_id);
        $results = DB::getFirst($sql, $data);
        return $results;
    }

    public static function insert($started_at, $trainee_id,
        $quiz_id, $theme_id=null){

            $data = array(
                ":started_at"=> $started_at,
                ":trainee_id" => $trainee_id,
                ":quiz_id" => $trainee_id,
                ":theme_id" => $theme_id
            );

            $sql = "INSERT INTO training(started_at, trainee_id,
                quiz_id, theme_id) VALUES(:started_at, :trainee_id,
                :quiz_id, :theme_id)";
        
            $db = DB::getConnection();
            $stmt = $db->prepare($sql);
            foreach ($data as $key => $value) {
                $stmt->bindValue($key, $value); 
              }
            $stmt->execute();
            $id = $db->lastInsertId();
            return $id;

    }

    public static function delete($training_id){
        $sql = "DELETE from training 
                WHERE training_id = :training_id";
        $data = array(":training_id"=> $training_id);
        $results = DB::execute($sql, $data);
        return $results;
    }

    public static function update($training_id, $ended_at){
        $sql = "UPDATE training SET ended_at = :ended_at
        WHERE training_id = :training_id";
        $data = array(
        ":training_id"=>$training_id,
        ":ended_at" => $ended_at);
        $results = DB::execute($sql, $data);
        return $results;
    }

    public static function search(){

    }

}
?>