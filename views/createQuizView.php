<?php
//data: $dbs, $question, $themes
if (!isset($quiz)) {
    $quiz = array(
        "db_name" => $target_dbs[0]["db_name"],
        "title" => "",
        "is_public" => false
    );
}
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Create Quiz - EvaluationManager</title>
    <script src="static/jquery-3.4.1.min.js"></script>
</head>

<body>
    <?php require_once("header.php"); ?>
    <h4>Create Quiz</h4>
    <form id="quizForm" method="POST" action="../controllers/createQuiz">
        <label>Db Name: </label>
        <select form="quizForm" name="db_name" id="db_name">
            <?php
            foreach ($target_dbs as $row) {
                $v = strcmp($quiz["db_name"], $row["db_name"]) == 0 ? "selected" : "";
                echo '<option value="' . $row["db_name"] . '"' . $v . '>' . $row["db_name"] . '</option>';
            }
            ?>
        </select>
        <label>Title:</label>
        <input type="text" name="title" value="<?= $quiz["title"] ?>">
        <label>Public?</label>
        <input type="checkbox" name="is_public" <?= $quiz["is_public"]?"checked":"" ?>>
        </select>
        <input type="submit">
    </form>
    <div id="error"></div>
</body>

</html>