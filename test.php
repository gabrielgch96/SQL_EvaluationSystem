<?php
require_once("DB.php");
$dbs = DB::getAll("SELECT * FROM quiz_db");
foreach ($dbs as $db) {
    echo $db['db_name'].'</br>';
}