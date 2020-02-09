<?php
session_start();
require_once("../model/SQLQuiz.php");
$quizzes = array();
$quizzes = SQLQuiz::search();
require_once("../views/quizzes_view.php");


?>