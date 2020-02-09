<?php
session_start();
switch($_SERVER["REQUEST_METHOD"]){
    case "GET":
        require_once("../views/createQuizView.php");
        break;
    case "POST":
        break;
    default:
        die("Method Not Implemented");
}
?>