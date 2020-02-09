<?php

/* Connect or disconnect a user.
 * The user interface is minimal.
 */
session_start();
if (filter_input(INPUT_SERVER, "REQUEST_METHOD") != "POST") {
   $message = "Method not implemented";
   require_once("../views/message.php");
   die();
}

$action = filter_input(INPUT_GET, "action");
if ($action == "disconnect") {
   $_SESSION = array();
   session_destroy();
   header("Location: ./");
} else {
   $login = filter_input(INPUT_POST, "login", FILTER_VALIDATE_EMAIL);
   $password = filter_input(INPUT_POST, "password", FILTER_SANITIZE_STRING);
   if (empty($login) || empty($password)) {
      $errors["login_form"] = "fields must be filled";
      require_once("../views/index_view.php");
   } else {
      require_once "../model/Person.php";
      $user = Person::getByLoginPassword($login, $password);
      if ($user != null) {
         // Store the user in the session
         $_SESSION["user"] = $user;
         // Redirect to home page or previous page, if defined
         $url = (isset($_SESSION["page"])) ? $_SESSION["page"] : "./";
         header("Location: $url");
      } else {
         $errors["login_form"] = "Invalid password or user unknow";
         require_once("../views/index_view.php");
      }
   }
}