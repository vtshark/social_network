<?php
$title="Регистрация";
$login=$email='';
$regfl=0;

//если пользователь уже авторизирован
if (isset($_SESSION['user']))  {
   if ($_SESSION['user']!='') {
        $regfl=1;
        $login=$_SESSION['user'];
   }
}

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
    $email=$_POST['email'];
    $pass=$_POST['password'];
    $pass1=$_POST['password1'];
    
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
                VALUES ('$login','$pass','$email')                    ";
                $q = $db->query($strsql);
                $regfl=1;
                $_SESSION['user']=$login;
                $q = $db->query(" SELECT `id` FROM `users` WHERE `login` = '$login'");
                $res = $q->fetch_assoc();
                $_SESSION['iduser']=$res['id'];
            }        
    }
}
display("registration", compact('title','error','login','email','regfl'));