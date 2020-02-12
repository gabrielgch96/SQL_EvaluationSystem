<?php

/** Controller for quiz modification */
session_start();
require_once("../model/SQLQuiz.php");
require_once("../model/QuizQuestion.php");
$target_dbs = SQLQuiz::search();
if ($_SERVER["REQUEST_METHOD"] == "GET") {
    // the question
    $quiz_id = null;
    // Errors
    $errors = array();
    // Get id parameter
    $quiz_id = filter_input(INPUT_GET, "id", FILTER_VALIDATE_INT);
    if (
        $quiz_id === null // no value
        || $quiz_id == false
    ) { // not an integer
        $errors["id"] = "id parameter must be set and integer (eg: quiz-1)";
    } else {
        // Call the model
        require_once("../model/SQLQuestion.php");
        $quiz = SQLQuiz::get($quiz_id);
        $previousQuestions = QuizQuestion::getQuizQuestionsFormat($quiz["quiz_id"]);
        $questions = SQLQuestion::getWithDisplayFormat($quiz["db_name"]);
        $diff = array();
        //this is not optimized at all
        foreach($questions as $question){
            foreach($previousQuestions as $prev){
                if($question["question_id"] == $prev["question_id"])
                    continue 2;     
            }
            array_push($diff, $question);
        }
    }
    // Sent to the view
    require_once("../views/quiz_view.php");
} else if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $quiz = array(
        "quiz_id" => filter_input(INPUT_POST, "question_id", FILTER_SANITIZE_STRING),
        "db_name" => filter_input(INPUT_POST, "db_name", FILTER_SANITIZE_STRING),
        "title" => filter_input(INPUT_POST, "title", FILTER_SANITIZE_STRING),
        "is_public" => filter_input(INPUT_POST, "is_public", FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE),
        "author_id" => $_SESSION["user"]["person_id"]
    );
    $questions = json_decode($_POST["questions"]);
    for ($i = 0; $i < count($questions); $i++)
        $questions[$i] = (int) trim($questions[$i], "\"");

    $previousQuestions = QuizQuestion::getQuizQuestionsIDs($quiz["quiz_id"]);
    $inQuiz = array();
    foreach ($previousQuestions as $question) {
        if (!in_array($question["question_id"], $questions)) {
            QuizQuestion::delete($quiz["quiz_id"], $question["question_id"]);
        }
        array_push($inQuiz, $question["question_id"]);
    }
    $newQuestions = array_diff($questions, $inQuiz);
    foreach ($newQuestions as $new)
        QuizQuestion::insert($quiz["quiz_id"], $new, 0);

    SQLQuiz::update($quiz["quiz_id"], $quiz["title"], $quiz["is_public"]);
    $url = "./ListQuizzes.php";
    header("Location: $url");

} else
    die("Method Not Implemented");
