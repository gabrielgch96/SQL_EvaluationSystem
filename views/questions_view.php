<?php
// Product view
// Data : $members
?>
<!DOCTYPE html>
<html>

<head>
   <link rel="stylesheet" type="text/css" href="static/evalutationSystem.css" />
   <title>Search results</title>
</head>

<body>
   <?php
   // Header shared by all pages
   require_once("header.php");
   if (count($questions) == 0) {
   ?>
      <h1>No questions found</h1>
      <a href="createQuestion"><button>Create Question</button></a>
      <p>Examples</p>
      <ul>
         <li><a href="<?= $_SERVER['PHP_SELF'] ?>?question_id=1"><?= $_SERVER['PHP_SELF'] ?>?question_id=1</a></li>
      </ul>
   <?php
   } else {
   ?>
      <h1>Results</h1>
      <a href="createQuestion"><button>Create Question</button></a>
      <form id="filter" method="POST" action="../controllers/questions">
         <select name="db_name">
            <?php
            foreach ($dbs as $row) {
               $v = strcmp($question["db_name"], $row["db_name"]) == 0 ? "selected" : "";
               echo '<option value="' . $row["db_name"] . '"' . $v . '>' . $row["db_name"] . '</option>';
            }
            ?>
         </select>
         <input type="submit" value="filter">
      </form>
      <table>
         <tr>
            <th>Question ID</th>
            <th>Question Text</th>
            <th>Theme</th>
            <th>Quiz Occurrences</th>
         </tr>
         <?php
         foreach ($questions as $question) {
         ?>
            <tr>
               <td><a href="question-<?= $question['question_id'] ?>"><?= $question["question_id"] ?></a></td>
               <td><?= $question["question_text"] ?></td>
               <td><?= $question["label"] ?></td>
               <td><?= $question["quiz_occurrences"] ?></td>
            </tr>
         <?php
         }
         ?>
      </table>
   <?php
   }
