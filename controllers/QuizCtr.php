<?php

/** Controller for quiz modification */
session_start();
require_once("../model/SQLQuiz.php");
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
        require_once("../model/SQLQuiz.php");
        require_once("../model/SQLQuestion.php");
        $questions = SQLQuestion::getWithDisplayFormat($quiz["db_name"]);
        // Recuperer le produit de id demande
        $quiz = SQLQuiz::get($quiz_id);
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
    if (($t = (SQLQuiz::update($quiz["quiz_id"], $quiz["title"], $quiz["is_public"])))) {
        $url = "./ListQuizzes.php";
        header("Location: $url");
    } else {
        $errors["UPDATE"] = "An error occurred during update";
        require_once("../views.quiz-" . $quiz["quiz_id"]);
    }
} else
    die("Method Not Implemented");
