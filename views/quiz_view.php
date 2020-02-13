<?php

?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <script src="static/jquery-3.4.1.min.js"></script>
    <script src="static/jquery-ui.min.js"></script>
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
            <h2>Update Quiz</h2>
            <?php if(isset($errors["UPDATE"])){ ?>
            <div id="errors"><?=$errors["UPDATE"]?></div>
            <?php } ?>
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
            <table id="quizQuestions">
                <thead>
                    <tr>
                        <th>Question ID</th>
                        <th>Question Text</th>
                        <th>Theme</th>
                        <th>Quiz Occurrences</th>
                    </tr>
                </thead>
                <tbody id="questionsQuizSortable">
                <?php
                    for ($i = 0; $i < count($previousQuestions); $i++) {
                        //foreach ($questions as $question) {
                    ?>
                        <tr class="item_row">
                            <td><a href="question-<?= $previousQuestions[$i]['question_id'] ?>"><?= $previousQuestions[$i]["question_id"] ?></a></td>
                            <td><?= $previousQuestions[$i]["question_text"] ?></td>
                            <td><?= $previousQuestions[$i]["label"] ?></td>
                            <td><?= $previousQuestions[$i]["quiz_occurrences"] ?></td>
                        </tr>

                    <?php
                    }
                    ?>
                </tbody>
            </table>
            <h3>Available Questions</h3>
            <table id="available">
                <thead>
                    <tr>
                        <th>Question ID</th>
                        <th>Question Text</th>
                        <th>Theme</th>
                        <th>Quiz Occurrences</th>
                    </tr>
                </thead>
                <tbody id="justmovable">
                    <?php
                    for ($i = 0; $i < count($diff); $i++) {
                        //foreach ($questions as $question) {
                    ?>
                        <tr class="item_row">
                            <td><a href="question-<?= $diff[$i]['question_id'] ?>"><?= $diff[$i]["question_id"] ?></a></td>
                            <td><?= $diff[$i]["question_text"] ?></td>
                            <td><?= $diff[$i]["label"] ?></td>
                            <td><?= $diff[$i]["quiz_occurrences"] ?></td>
                        </tr>

                    <?php
                    }
                    ?>
                </tbody>
            </table>
    <?php }
    } ?>
    <script>
        $(document).ready(function() {

            $("#available tbody").on("click", "tr", function() {
                var tr = $(this).closest("tr").remove().clone();
                $("#quizQuestions tbody").append(tr);
            });
            $('#quizQuestions tbody').on("click", "tr", function() {
                var tr = $(this).closest("tr").remove().clone();
                $("#available tbody").append(tr);
            });

            $("#justmovable").sortable();
            $("#questionsQuizSortable").sortable();

            $('#quizForm').submit(function(eventObj) {
                var items = [];
                $('#quizQuestions tbody tr td:nth-child(1)').each(function() {
                    items.push($(this).text());
                });
                //restrict array to unique items
                var items = $.unique(items);
                var question_ids = JSON.stringify(items);//{"questions":JSON.stringify(items)};
                console.log(question_ids);
                $(this).append("<input type='hidden' name='questions' value='"+question_ids+"' /> ");
                return true;
            });

        });
    </script>
</body>

</html>