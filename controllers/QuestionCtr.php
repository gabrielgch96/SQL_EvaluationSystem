<?php
session_start();
require_once("../model/Theme.php");
$themes = Theme::getAll();
if ($_SERVER["REQUEST_METHOD"] == "GET") {
    // the question
    $question_id = null;
    // Errors
    $errors = array();

    // Get id parameter
    $question_id = filter_input(INPUT_GET, "id", FILTER_VALIDATE_INT);
    if (
        $question_id === null // no value
        || $question_id == false
    ) { // not an integer
        $errors["id"] = "id parameter must be set and integer (eg: question-1)";
    } else {
        // Call the model
        require_once("../model/SQLQuestion.php");
        // Recuperer le produit de id demande
        $question = SQLQuestion::get($question_id);
    }
    // Sent to the view
    require_once("../views/question_view.php");
} else if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    if (isset($_POST["TEST_SQL"])) {
        $sql = filter_input(INPUT_POST, "correct_answer", FILTER_SANITIZE_STRING);
        $db_name = filter_input(INPUT_POST, "db_name", FILTER_SANITIZE_STRING);
        $results = testSQL($sql, $db_name);
        if ($results["PASS"])
            $results["RESULT"] = formatToCSV($results["RESULT"]);
        echo json_encode(str_replace("\n", "<br>", $results["RESULT"]), JSON_UNESCAPED_UNICODE);
    } else {
        $question = array(
            "question_id" => filter_input(INPUT_POST, "question_id", FILTER_SANITIZE_STRING),
            "db_name" => filter_input(INPUT_POST, "db_name", FILTER_SANITIZE_STRING),
            "question_text" => filter_input(INPUT_POST, "question_text", FILTER_SANITIZE_STRING),
            "correct_answer" => filter_input(INPUT_POST, "correct_answer", FILTER_SANITIZE_STRING),
            "is_public" => filter_input(INPUT_POST, "is_public", FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE),
            "theme_id" => filter_input(INPUT_POST, "theme_id", FILTER_VALIDATE_INT),
            "author_id" => $_SESSION["user"]["person_id"]
        );
        $test = testSQL($question["correct_answer"], $question["db_name"]);
        if (!$test["PASS"]) {
            require_once("../views/question-".$question_id);
        } else {
            require_once("../model/SQLQuestion.php");
            $question["correct_result"] = formatToCSV($test["RESULT"]);
            //insert to database
            if (($res = SQLQuestion::update($question["question_id"],
                $question["question_text"], $question["correct_answer"],
                $question["correct_result"],$question["is_public"],
                $question["theme_id"]))) {
                $url = "./ListQuestions.php";
                header("Location: $url");
            } else {
                $test["PASS"] = false;
                $test["RESULT"] = "An error occurred during update";
                require_once("../views.question-".$question["question_id"]);
            }
        }
    }
} else {
    die("Method Not Implemented");
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
    
    /* formats data to store and display" */
    function formatToCSV($results){
        $formatted = array();
        if(count($results) != 0){
            foreach($results as $row){
                $formatted[] = implode(";", array_values($row));
            }
        }
        return implode("\n", $formatted);
    }
