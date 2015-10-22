<?php

//127.0.0.1 == localhost
$db = mysqli_connect("localhost", DB_LOGIN, DB_PASSWORD, DB_NAME);

if(!$db) {
    exit("Не удалось подсоедениться к БД ".$db->connect_error);
}