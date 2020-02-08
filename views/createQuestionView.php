<?php
//data: $dbs
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
                echo '<option value="'.$row["db_name"].'">'.$row["db_name"].'</option>';
           }
        ?>
        </select>
        <label>Question text:</label>
        <input type="text" name="question_text">
        <label>Correct Answer:</label>
        <input type="text" name="correct_answer">
        <!--<label>Correct Result: </label>
        <input type=text name="correct_result">-->
        <label>Public?</label>
        <input type="checkbox" name="is_public">
        <label>Theme</label>
        <select form="questionForm" name="theme_id">
        <?php
            foreach($themes as $row){
                echo '<option value="'.$row["theme_id"].'">'.$row["label"].'</option>';
           }
        ?>
        </select>
        <input type="submit">
    </form>
</body>
</html>