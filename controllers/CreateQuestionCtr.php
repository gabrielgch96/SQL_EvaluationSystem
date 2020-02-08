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
        echo "Got it";
        break;
    default:
        die("Method not implemented");
}

?>