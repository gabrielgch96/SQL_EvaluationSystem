<?php
/** controller for the creation of questions */
session_start();
$_SESSION["page"] = $_SERVER["REQUEST_URI"];
require_once("../model/SQLQuiz.php");
switch ($_SERVER["REQUEST_METHOD"]) {
    case "GET":
        $bs = array();
        require_once("../model/Quiz_DB.php");
        require_once("../model/Theme.php");
        $dbs = Quiz_DB::searchAll();
        $themes = Theme::getAll();
        require_once("../views/createQuestionView.php");
        break;
    case "POST":
        $data = array(
            $db_name = filter_input(INPUT_POST, "db_name", FILTER_SANITIZE_STRING),
            $question_text = filter_input(INPUT_POST, "question_text", FILTER_SANITIZE_STRING),
            $correct_answer = filter_input(INPUT_POST, "correct_answer", FILTER_SANITIZE_STRING),
            //$correct_result = filter_input(INPUT_POST, "correct_result", FILTER_SANITIZE_STRING),
            $is_public = filter_input(INPUT_POST, "is_public", FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE),
            $theme_id = filter_input(INPUT_POST, "theme_id", FILTER_VALIDATE_INT)
        );
        $test = testSQL($correct_answer, $db_name);
        if($test["PASS"]){
            //implode results
            //insert to database
            //redirect 
        }
        else{
            //return form with values
        }
        break;
    default:
        die("Method not implemented");
}

/** test user sql
    * @param $sql_test SELECT to run
    * @param $db_name target database 
    * @return associative_array pass and result
    */
function testSQL($sql_test, $db_name){
    require_once("../model/Quiz_DB.php");
    
    $results = Quiz_DB::testSQL($sql_test, $db_name);
    $ans = array("PASS" => is_array($results), 
        "RESULT"=> $results);
    return $ans;
}

?>