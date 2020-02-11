<?php

?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <script src="static/jquery-3.4.1.min.js"></script>
    <title>Quiz-<?php echo isset($quiz) ? $quiz["quiz_id"] : ""; ?></title>
</head>
<body>
<?php require_once("header.php");
if (isset($errors["id"])) {
?>
    <h1><?= $errors["id"] ?></h1>
    <p>Usage example:
        <a href="quiz-1">quiz-1</a> (where 1 is the quiz id).
    </p>
    <?php
} else {
    if ($quiz == null) {
        die("<h1>Quiz not found</h1>");
    } else {
    ?>
        <h2>Create Quiz</h2>
        <form id="quizForm" method="POST" action="../controllers/quiz-<?= $quiz["quiz_id"] ?>">
            <label>ID: </label>
            <input type="text" name="question_id" value="<?= $quiz["quiz_id"] ?>" readonly>
            <label>Db Name: </label>
            <input type="text" id="db_name" name="db_name" value="<?= $quiz["db_name"] ?>" readonly>
            <label>Title:</label>
            <input type="text" name="title" value="<?= $quiz["title"] ?>">
            <label>Public?</label>
            <input type="checkbox" name="is_public" <?= $quiz["is_public"] ? "checked" : "" ?>>
            </select>
            <input type="submit" value="UPDATE">
        </form>
        <div id="error"></div>
        <h3>Quiz Questions</h3>

        <h3>Available Questions</h3>
<?php }
} require_once("../views/questions_view.php");?>
</body>
</html>