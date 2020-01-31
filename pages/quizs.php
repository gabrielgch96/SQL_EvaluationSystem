<?php
session_start();
// Memorize the page to redirect to it if logging in
$_SESSION["page"] = $_SERVER["REQUEST_URI"];

/*
  Get members with 2 optional filters
 * $members and $errors are shared with model and view
 */
// Control the parameters
$quiz_id = filter_input(INPUT_GET, "quiz_id", FILTER_VALIDATE_INT);
$pageIndex = filter_input(INPUT_GET, "page");

$errors = array();
if ($quiz_id === false) {
   array_push($errors, "quiz_id must be integer");
}

$quizs = array();
/*if (count($errors) == 0) {
   require_once ("../model/SQLQuiz_model.php");
   $quizs = Quiz::getByFilter($quiz_id);
}*/
require_once("../view/quizs_view.php");
