<?php
$error = array();
//error_reporting(-1);
//если пользователь уже авторизирован
if (isset($_SESSION['iduser']))  {
    header('Location: /user/');
}
$title='Авторизация';
$user='';
if ( isset($_POST['login']) || isset($_POST['password']) ) {

    $login = $db->real_escape_string($_POST['login']);
    $password = md5($_POST['password']);

    if  (($login == '') || ($password == '')) {
        $error[] = 'Введите имя пользователя и пароль!';
    } else {
        $filter = 'login';
        if (filter_var($login, FILTER_VALIDATE_EMAIL)) {
            $filter = 'email';
        }

        $res = sql(" SELECT * FROM `users` WHERE `$filter` = '$login' AND `passwd` = '$password' ",1);
        if (is_null($res)) {
            $error[] = "Не верные имя пользователя или пароль!";
        } else {
            $_SESSION['iduser']=$res['id'];
            
            header('Location: /user/');
            exit();
        }
    }
    $user = $login;
}

display('login',compact('error','title','user'));





















