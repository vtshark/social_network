<?php
$error = array();
//error_reporting(-1);
$fl_log=0;
//если пользователь уже авторизирован
if (isset($_SESSION['iduser']))  {
        header('Location: /user/');
}
$title='Авторизация';
$user='';
if ( (isset($_POST['login']) || isset($_POST['password'])) && ($fl_log==0) ) {

    $login = $_POST['login'];
    $password = md5($_POST['password']);

    if  (($login == '') || ($password == '')) {
        $error[] = 'Введите имя пользователя и пароль!';
    } else {

        if (filter_var($login, FILTER_VALIDATE_EMAIL)) {
            $filter = 'email';
        } else {
            $filter = 'login';
        }

        $q = $db->query(" SELECT * FROM `users` WHERE `$filter` = '$login' AND `passwd` = '$password' ");
        $res = $q->fetch_assoc();
        if (!isset($res)) {
            $error[] = "Не верные имя пользователя или пароль!";
        } else {
            //$_SESSION['user']=$res['login'];
            $_SESSION['iduser']=$res['id'];
            $fl_log=1;
            header('Location: /news/');
            
        }
    }

    $user=$login;
}
display('login',compact('error','title','user'));





















