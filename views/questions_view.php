<?php
// Product view
// Data : $members
?>
<!DOCTYPE html>
<html>
   <head>
      <link rel="stylesheet" type="text/css" href="static/evalutationSystem.css"/>
      <title>Search results</title>
   </head>
   <body>
      <?php
      // Header shared by all pages
      require_once("header.php");
      if (count($questions) == 0) {
         ?>
         <h1>No question found</h1>
         <p>Examples</p>
         <ul>
            <li><a href="<?= $_SERVER['PHP_SELF'] ?>?question_id=1><?= $_SERVER['PHP_SELF'] ?>?question_id=1</a></li>
         </ul>
         <?php
      } else {
         ?>
         <h1>Results</h1>
         <table>
            <tr>
               <th>Question ID</th>
               <th>Question Text</th>
               <th>Correct answer</th>
               <th>Correct result</th>
               <th>Is public</th>
               <th>Theme ID</th>
               <th>Author ID</th>
               <th>Created date</th>
            </tr>
            <?php
            foreach ($question as $questions) {
               ?>
               <tr>
                  <td><a href="member-<?= $question['question_id'] ?>"><?= $question["question_id"] ?></a></td>
                  <td><?= $question["question_text"] ?></td>
                  <td><?= $question["correct_answer"] ?></td>
                  <td><?= $question["is_public"] ?></td>
                  <td><?= $question["theme_id"] ?></td>
                  <td><?= $question["author_id"] ?></td>
                  <td><?= $question["create_at"] ?></td>
               </tr>
               <?php
            }
            ?>
         </table>
         <?php
      }
      