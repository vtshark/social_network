<?php

$title="Регистрация";




$error=[];
    if ($_POST['login'] == "")  {
        $error[]="Enter login";
    }

    if($_POST['email'] == "" && filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
         $error[]="Enter email";
    }

    if($_POST['password'] == "") {
        $error[]="Enter password";
    }




display("registration", compact('title', 'body'));