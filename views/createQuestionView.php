<?php
//data: $dbs, $question, $themes
if (!isset($question)) {
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
    <meta charset="utf-8">
    <title>Create Question - EvaluationManager</title>
    <script src="static/jquery-3.4.1.min.js"></script>
</head>

<body>
    <?php require_once("header.php"); ?>
    <h4>Create Question</h4>
    <form id="questionForm" method="post" action="../controllers/createQuestion">
        <label>Db Name: </label>
        <select form="questionForm" name="db_name" id="db_name">
            <?php
            foreach ($dbs as $row) {
                $v = strcmp($question["db_name"], $row["db_name"]) == 0 ? "selected" : "";
                echo '<option value="' . $row["db_name"] . '"' . $v . '>' . $row["db_name"] . '</option>';
            }
            ?>
        </select>
        <label>Question text:</label>
        <input type="text" name="question_text" value="<?= $question["question_text"] ?>">
        <label>Correct Answer:</label>
        <input type="text" name="correct_answer" id="correct_answer" value="<?= $question["correct_answer"] ?>">
        <label>Public?</label>
        <input type="checkbox" name="is_public" <?= $question["is_public"]?"checked":"" ?>
        <label>Theme</label>
        <select form="questionForm" name="theme_id">
            <?php
            foreach ($themes as $row) {
                $v = $question["theme_id"] == $row["theme_id"] ? "selected" : "";
                echo '<option value="' . $row["theme_id"] . '" ' . $v . '>' . $row["label"] . '</option>';
            }
            ?>
        </select>
        <input type="submit">
    </form>
    <button id="testSQL">Test SQL</button>
    <br>
    <div id="message"></div>
    <?php if (isset($test["PASS"]) && !$test["PASS"]) {
        echo "<div id='errmsg'>" . $test["RESULT"] . "</div>";
    } ?>
    <script>
        $("#testSQL").click(function() {
            $.post("../controllers/createQuestion", {
                    "TEST_SQL": true,
                    "db_name": $("#db_name").val(),
                    "correct_answer": $("#correct_answer").val()
                }, "json")
                .done(function(data) {
                    //alert("Data Loaded: " + data);
                    $("#message").html(data);
                });
        });
    </script>
</body>

</html>