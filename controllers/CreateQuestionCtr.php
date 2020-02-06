<?php
/** controller for the creation of questions */
session_start();
$_SESSION["page"] = $_SERVER["REQUEST_URI"];
require_once("../model/SQLQuiz.php");
switch ($_SERVER["REQUEST_METHOD"]) {
    case "GET":
        $bs = array();
        require_once("../model/Quiz_DB.php");
        $bs = Quiz_DB::searchAll();
        echo'<select form="questionForm">';
            foreach($bs as $row){
                //echo '<option value="'.$row["db_name"].'>'.$row["db_name"].'</option>';
                //echo '<option value="volvo">Volvo</option>';
           }
        echo "</select>";
        require_once("../views/createQuestionView.php");
        break;
    case "POST":
        do_post();
        break;
    default:
        die("Method not implemented");
}

?>