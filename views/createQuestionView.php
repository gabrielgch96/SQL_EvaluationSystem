<?php
//data: $dbs
if(!isset($question)){
    $question = array(
        "db_name" => $dbs[0]["db_name"],
        "question_text" => "",
        "correct_answer" => "",
        "is_public" => false,
        "theme_id" => $themes[0]["theme_id"]
    );
}
?>
<!DOCTYPE html>
<html>
<head>
</head>
<body>
    <? require_once("header.php"); ?>
    <h4>Create Question</h4>
    <form id="questionForm" method="post" action="../controllers/CreateQuestionCtr.php">
        <label>Db Name: </label>
        <select form="questionForm" name="db_name">
        <?php
            foreach($dbs as $row){
                $v = strcmp($question["db_name"],$row["db_name"])==0? "selected":"";
                echo '<option value="'.$row["db_name"].'"'.$v.'>'.$row["db_name"].'</option>';
           }
        ?>
        </select>
        <label>Question text:</label>
        <input type="text" name="question_text" value="<?= $question["question_text"]?>">
        <label>Correct Answer:</label>
        <input type="text" name="correct_answer" value="<?= $question["correct_answer"]?>">
        <!--<label>Correct Result: </label>
        <input type=text name="correct_result">-->
        <label>Public?</label>
        <input type="checkbox" name="is_public" value="<?= $question["is_public"]?>">
        <label>Theme</label>
        <select form="questionForm" name="theme_id">
        <?php
            foreach($themes as $row){
                $v = $question["theme_id"]==$row["theme_id"]? "selected":"";
                echo '<option value="'.$row["theme_id"].'" '.$v.'>'.$row["label"].'</option>';
           }
        ?>
        </select>
        <input type="submit">
    </form>
</body>
</html>