<?php
session_start();
require_once("../model/SQLQuestion.php");
$questions = array();
$questions = SQLQuestion::search();
require_once("../views/questions_view.php");



?>