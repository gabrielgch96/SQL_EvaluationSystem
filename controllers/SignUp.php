<?php
session_start();
require_once("../model/Person.php");
switch($_SERVER["REQUEST_METHOD"]){
    case "GET":
        require_once("../views/sign_up.php");
        break;
    case "POST":
        $person = array(
            "email" => filter_input(INPUT_POST, "email", FILTER_SANITIZE_STRING),
            "pwd" => filter_input(INPUT_POST, "pwd", FILTER_SANITIZE_STRING),
            "first_name" => filter_input(INPUT_POST, "first_name", FILTER_SANITIZE_STRING),
            "name" => filter_input(INPUT_POST, "name", FILTER_SANITIZE_STRING),
            "is_trainer" => filter_input(INPUT_POST, "is_public", FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE)
        );
        $result = Person::insert($person["email"], $person["pwd"], 
            $person["name"], $person["first_name"], $person["is_trainer"]);
        if($result){
            echo "<h4>Please check your email to confirm: validate-".$result."</h4>";
            $url = "./index.php";
                    header("Location: $url");
        }
        break;
    default:
        die("Method Not Implemented");
}
?>