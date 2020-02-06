<?php
//data: $bs
?>
<!DOCTYPE html>
<html>
<head>
</head>
<body>
    <? require_once("header.php"); ?>
    <h4>Create Question</h4>
    <form id="questionForm">
        <label>Db Name: </label>
        <select form="questionForm">
        <?php
            foreach($bs as $row){
                echo '<option value="'.$row["db_name"].'>'.$row["db_name"].'</option>';
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
        
        <input type="submit">
    </form>
</body>
</html>