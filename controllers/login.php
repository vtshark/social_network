<?php
$error = array();
$fl_log=0;
//если пользователь уже авторизирован
$login='';
if (isset($_SESSION['user']))  {
   if ($_SESSION['user']!='') {
        $login=$_SESSION['user']; 
        $fl_log=1;
   }
}

if ( (isset($_POST['login']) || isset($_POST['password'])) && ($fl_log==0) ) {

    $login = $_POST['login'];
    $password = $_POST['password'];

    if  (($login == '') || ($password == '')) {
        $error[] = 'Введите имя пользователя и пароль!';
    } else {

        if (filter_var($login, FILTER_VALIDATE_EMAIL)) {
            $filter = 'email';
        } else {
            $filter = 'login';
        }

        $q = $db->query(" SELECT * FROM `users` WHERE `$filter` = '$login' AND `passwd` = '$password' ");
        $Res = $q->fetch_assoc();
        if (!isset($Res)) {
            $error[] = "Не верные имя пользователя или пароль!";
        } else {
            $_SESSION['user']=$login;
            $fl_log=1;
        }
    }    
}

if ($fl_log==0) {
    $title='Авторизация';
    display('login',compact('error','title'));
} else {
    $title='Авторизация';
    $user=$login;
    display('login',compact('error','title','user'));
    //$title='Главная страница';
    //$user=$login;
    //$buf_path='../';
    //$mode='';
    //if (isset($_GET['m'])) {
        //$mode=$_GET['m'];
    //}
    //debug($_GET);
    //display('user',compact('title','user','buf_path','mode'));
}




















