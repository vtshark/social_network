<?php

$login = $_POST['login'];
$password = $_POST['password'];
$error = array();

if($_POST['login'] == '' && $_POST['password' == '']) {
     $error[] = 'Вы не ввели пароль и логин';
}



debug($_POST);



if (filter_var($login, FILTER_VALIDATE_EMAIL)) {
    $filter = 'email';
} else {
    $filter = 'login';
    

$q = $db->query(" SELECT * FROM `users` WHERE `$filter` = '$login' AND `passwd` = '$password' ");
$Res = $q->fetch_assoc();
if (!isset($Res)) {
    echo " нет пользователя с именем ".$GLOBALSlogin;
}







display('login',compact('error'));




























