<?php

?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <script src="static/jquery-3.4.1.min.js"></script>
    <title>Question-<?php echo isset($question) ? $question["question_id"] : ""; ?></title>
</head>

<body>
    <?php
    require_once("header.php");
    if (isset($errors["id"])) {
    ?>
        <h1><?= $errors["id"] ?></h1>
        <p>Usage example:
            <a href="question-1">question-1</a> (where 1 is the question id).
        </p>
        <?php
    } else {
        if ($question == null) {
            die("<h1>Question not found</h1>");
        } else {
        ?>

            <h2>Question <?= $question["question_id"] ?></h2>
            <form id="questionForm" method="post" action="../controllers/question-<?= $question["question_id"] ?>">
                <label>ID: </label>
                <input type="text" name="question_id" value="<?= $question["question_id"] ?>" readonly>
                <label>Db Name: </label>
                <input type="text" id="db_name" name="db_name" value="<?= $question["db_name"] ?>" readonly>
                <label>Question text:</label>
                <input type="text" name="question_text" value="<?= $question["question_text"] ?>">
                <label>Correct Answer:</label>
                <input type="text" name="correct_answer" id="correct_answer" value="<?= $question["correct_answer"] ?>">
                <label>Public?</label>
                <input type="checkbox" name="is_public" <?= $question["is_public"] ? "checked" : "" ?>>
                <label>Theme</label>
                <select form="questionForm" name="theme_id">
                    <?php
                    foreach ($themes as $row) {
                        $v = $question["theme_id"] == $row["theme_id"] ? "selected" : "";
                        echo '<option value="' . $row["theme_id"] . '" ' . $v . '>' . $row["label"] . '</option>';
                    }
                    ?>
                </select>
                <input type="submit" value="UPDATE">
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
    <?php }
    } ?>
</body>

</html>