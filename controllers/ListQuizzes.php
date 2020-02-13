<?php
session_start();
if (!isset($_SESSION["user"])) {
    require_once("../views/loginMessage.php");
} else{
require_once("../model/SQLQuiz.php");
$quizzes = array();
$quizzes = SQLQuiz::search();
require_once("../views/quizzes_view.php");
}

?>