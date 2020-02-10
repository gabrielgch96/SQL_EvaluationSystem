<?php
// Product view
// Data : $members
?>
<!DOCTYPE html>
<html>

<head>
   <title>Search results</title>
   <script src="static/jquery-3.4.1.min.js"></script>
   <script src="static/jquery-ui.min.js"></script>
   <style>
      table {
         border: 1px solid black;
      }
   </style>
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
      <h1>Questions</h1>
      <a href="createQuestion"><button>Create Question</button></a>
      <form id="filter" method="POST" action="../controllers/questions">
         <select name="db_name">
            <?php
            echo '<option value="all">All</option>';
            foreach ($dbs as $row) {
               $v = strcmp($db_name, $row["db_name"]) == 0 ? "selected" : "";
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
         <tbody id="justmovable">
            <?php
            for ($i = 0; $i < count($questions); $i++) {
               //foreach ($questions as $question) {
            ?>
               <tr class="item_row">
                  <td><a href="question-<?= $questions[$i]['question_id'] ?>"><?= $questions[$i]["question_id"] ?></a></td>
                  <td><?= $questions[$i]["question_text"] ?></td>
                  <td><?= $questions[$i]["label"] ?></td>
                  <td><?= $questions[$i]["quiz_occurrences"] ?></td>
               </tr>
               <tr>
                  <td colspan="4">
                     <div id="<?= $questions[$i]['question_id'] ?>" hidden>
                        Correct Answer: <?= $questions[$i]["correct_answer"] ?>
                        <hr>
                        Correct Result: <?= $questions[$i]["correct_result"] ?>
                     </div>
                  </td>
               </tr>
            <?php
            }
            ?>
         </tbody>
      </table>
      <script type="text/javascript">
         $(document).ready(function() {
            $("#justmovable").sortable();

            function updateOrder(data) {
               $.ajax({
                  url: ".php",
                  type: 'post',
                  data: {
                     position: data
                  },
                  success: function() {
                     alert('your change successfully saved');
                  }
               })
            }
            $(".item_row").hover(
               function() {
                  t = $(this).find("td:eq(0)").text();
                  console.log($('#' + t).text());
                  //$(this).append($("<span>"+t+"</span>"));
                  $('#' + t).show();
               },
               function() {
                  //$(this).find("span").last().remove();
                  $('#' + t).hide();
               }
            );
         });
      </script>
   <?php
   }
