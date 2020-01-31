<?php
session_start();
// Memorize the page to redirect to it if logging in
$_SESSION["page"] = $_SERVER["REQUEST_URI"];

/*
  Get questions with 3 optional filters
 * $questions and $errors are shared with model and view
 */
// Control the parameters
$question_id = filter_input(INPUT_GET, "question_id", FILTER_VALIDATE_INT);
$db_name = filter_input(INPUT_GET, "db_name_id");
$question_text = filter_input(INPUT_GET, "question_text");
//correct_anwser
//correct_result
//is_public
//theme_id
//author_id
//create_date
$deadline = filter_input(INPUT_GET, "deadline");
$pageIndex = filter_input(INPUT_GET, "page");

$errors = array();
if ($question_id === false) {
   array_push($errors, "category_id must be integer");
}

if ($pageIndex === false) {
   array_push($errors, "page must be a positive integer");
} else if ($pageIndex == null) {
   $pageIndex = 1; // By default
}
$questions = array();
if (count($errors) == 0) {
   require_once ("../model/question_model.php");
   $questions = SQLQuestion::getByFilter($question_id, $pageIndex);
}
require_once("../view/products_view.php");
