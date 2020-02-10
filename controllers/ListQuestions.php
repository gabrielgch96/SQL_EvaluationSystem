<?php
session_start();
require_once("../model/SQLQuestion.php");
require_once("../model/Quiz_DB.php");
$questions = array();
$dbs = array();
$dbs = Quiz_DB::searchAll();

switch($_SERVER["REQUEST_METHOD"]){
    case "GET":
        $questions = SQLQuestion::getWithDisplayFormat();
        require_once("../views/questions_view.php");
        break;
    case "POST":
        $db_name = filter_input(INPUT_POST, "db_name", FILTER_SANITIZE_STRING);
        $questions = SQLQuestion::getWithDisplayFormat($db_name);
        require_once("../views/questions_view.php");
        break;
    default:
        die("Method Not Implemented");
}
?>