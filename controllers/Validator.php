<?php
if ($_SERVER["REQUEST_METHOD"] == "GET") {
    // the question
    $id = null;
    // Errors
    $errors = array();

    // Get id parameter
    $id = filter_input(INPUT_GET, "id", FILTER_VALIDATE_INT);
    if (
        $id === null // no value
        || $id == false
    ) { // not an integer
        $errors["id"] = "id parameter must be set and integer (eg: question-1)";
    } else {
        // Call the model
        require_once("../model/Person.php");
        // Recuperer le produit de id demande
        $person = Person::get($id);
        if($person != null){
            Person::validate($person["person_id"]);
        }
    }
}
else   
    die("Method Not Implemented");