<?php
//если пользователь уже авторизирован
if (isset($_SESSION['iduser']))  {
        header('Location: /user/');
}

$title="Регистрация";
$login=$email='';
$regfl=0;

if (isset($_POST['login']) ) {
    $error = array();    
    if ($_POST['login'] == "")  {
        $error[]="Введите имя пользователя!";
    } else {
        $login=$_POST['login'];
    }
    if (($_POST['email'] == "") || (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) ) {
         $error[]="Некорректный e-mail!";
    }
    $email=$db->real_escape_string($_POST['email']);
    $pass=$db->real_escape_string($_POST['password']);
    $pass1=$db->real_escape_string($_POST['password1']);
    $login=$db->real_escape_string($login);
    
    if (($pass == "") || ($pass1 == "")) {
        $error[]="Введите и подтвердите пароль!";
    } else {
        if ($pass != $pass1) {
            $error[]="Введенный и подтвержденный пароли не совпадают!";  
        } 
    }
    
    if  (!$error) {
            //если нет ошибок проверяем существует ли пользователь с таким логином
            $q = $db->query(" SELECT `login` FROM `users` WHERE `login` = '$login'");
            $res = $q->fetch_assoc();
            if (isset($res)) {
                $error[] = "Пользователь с таким логином уже существует!";
                
            } else {//Регистрация нового пользователя
                $strsql="INSERT INTO `users`(`login`, `passwd`, `email`)
                VALUES ('$login','".md5($pass)."','$email')";
                //exit($strsql);
                $q = $db->query($strsql);
                $regfl=1;
                $_SESSION['user']=$login;
                $q = $db->query(" SELECT `id` FROM `users` WHERE `login` = '$login'");
                $res = $q->fetch_assoc();
                $_SESSION['iduser']=$res['id'];
                header('Location: /user/');
            }        
    }
}
display("registration", compact('title','error','login','email','regfl'));