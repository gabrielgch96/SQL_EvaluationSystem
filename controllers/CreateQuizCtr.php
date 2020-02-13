<?php
session_start();
require_once("../model/SQLQuiz.php");
if (!isset($_SESSION["user"])) {
    require_once("../views/loginMessage.php");
} else{
$target_dbs = SQLQuiz::search();
switch($_SERVER["REQUEST_METHOD"]){
    case "GET":
        require_once("../views/createQuizView.php");
        break;
    case "POST":
        $quiz = array(
            "title" => filter_input(INPUT_POST, "title", FILTER_SANITIZE_STRING),
            "is_public" => filter_input(INPUT_POST, "is_public", FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE),
            "db_name" => filter_input(INPUT_POST, "db_name", FILTER_SANITIZE_STRING),
            "author_id" => $_SESSION["user"]["person_id"]
        );
        $result = SQLQuiz::insert($quiz);
        if($result){
            $url = "./quiz-$result";
                    header("Location: $url");
        }
        break;
    default:
        die("Method Not Implemented");
}}
?>