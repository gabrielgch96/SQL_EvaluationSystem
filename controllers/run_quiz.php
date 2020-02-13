<?php
Session_start();
$_SESSION["page"] = $_SERVER["REQUEST_URI"];
if (!isset($_SESSION["user"])) {
    require_once("../views/loginMessage.php");
} else{
   switch ($_SERVER["REQUEST_METHOD"])
   {

     case "GET" :

        $id= $_GET["id"];

    require_once("../model/Quiz_DB.php");
    $dbs = Quiz_DB::getQuestionsForQuiz($id);

    require_once("../views/runQuiz_View.php");
     break;
     case "POST" :
        require_once("../model/Quiz_DB.php");
        echo(filter_input(INPUT_POST,"InsertedQuery"));
        
        
        if(isset($_POST["TEST_SQL"])){
            $quiz = filter_input(INPUT_POST, "correct_answer", FILTER_SANITIZE_STRING);
            $db_name = filter_input(INPUT_POST, "db_name", FILTER_SANITIZE_STRING);
            $results = testSQL($sql, $db_name);
            if($results["PASS"])
                $results["RESULT"] = formatToCSV($results["RESULT"]);
            echo json_encode(str_replace("\n","<br>",$results["RESULT"]),JSON_UNESCAPED_UNICODE);
        }

        if(isset($_POST["count"])){

            echo $_POST["count"];
            $queryResultArr = array();
            
            for ($x = 0; $x < $_POST["count"] ; $x++) {
                
                $dbs=Quiz_DB::checkQuery($_POST["Question".($x+1)]);
                array_push($queryResultArr, $dbs);
                    }
            

            

            
            require_once("../views/queryresults_view.php");
          //$_SESSION['varname'] = $var_value;

        }


   }}
   
 /** test user sql
    * @param $sql_test SELECT to run
    * @param $db_name target database 
    * @return associative_array pass and result
    */
function testSQL($sql_test, $db_name){
    require_once("../model/Quiz_DB.php");
    $results = Quiz_DB::testSQL($sql_test, $db_name);
    $ans = array("PASS" => is_array($results), 
        "RESULT"=> $results);
    return $ans;
}

/* formats data to store and display" */
function formatToCSV($results){
    $formatted = array();
    if(count($results) != 0){
        foreach($results as $row){
            $formatted[] = implode(";", array_values($row));
        }
    }
    return implode("\n", $formatted);
}
