<?php
// Product view
// Data : $products
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
      if (count($quizs) == 0) {
         ?>
         <h1>No quiz found</h1>
         <p>Examples</p>
         <ul>
            <li><a href="<?= $_SERVER['PHP_SELF'] ?>?category_id=1&max_current_price=100&deadline=2018"><?= $_SERVER['PHP_SELF'] ?>?category_id=1&max_current_price=100&deadline=2018</a></li>
         </ul>
         <?php
      } else {
         ?>
         <h1>Quiz List Results</h1>
        
         <?php
      }
      