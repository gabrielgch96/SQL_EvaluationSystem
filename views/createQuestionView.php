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
        <select form="questionForm">
        <?php
            foreach($dbs as $row){
                echo '<option value="'.$row["db_name"].'">'.$row["db_name"].'</option>';
           }
        ?>
        </select>
        <label>Question text:</label>
        <input type="text">
        <label>Correct Answer:</label>
        <input type="text">
        <label>Correct Result: </label>
        <input type=text>
        <label>Public?</label>
        <input type="checkbox">
        <label>Theme</label>
        <select form="questionForm">
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