<?php
//если пользователь уже авторизирован
if (isset($_SESSION['iduser']))  {
        header('Location: /user/');
}

$title="Регистрация";
$login=$email='';

if (isset($_POST['login']) ) {
    $error = array();    
    if ($_POST['login'] == "")  {
        $error[]="Введите login!";
    } else {
        $login=$_POST['login'];
    }
    if (($_POST['email'] == "") || (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) ) {
         $error[]="Некорректный e-mail!";
    }
    if ($_POST['name'] == "")  {
        $error[]="Введите имя пользователя!";
    }
    if ($_POST['secondName'] == "")  {
        $error[]="Введите фамилию пользователя!";
    }

    $email=$db->real_escape_string($_POST['email']);
    $pass=$db->real_escape_string($_POST['password']);
    $pass1=$db->real_escape_string($_POST['password1']);
    $login=$db->real_escape_string($login);
    $name=$db->real_escape_string($_POST['name'] );    
    $secondName=$db->real_escape_string($_POST['secondName']);

    if (($pass == "") || ($pass1 == "")) {
        $error[]="Введите и подтвердите пароль!";
    } else {
        if ($pass != $pass1) {
            $error[]="Введенный и подтвержденный пароли не совпадают!";  
        } 
    }
    
    if  (!$error) {
            //если нет ошибок проверяем существует ли пользователь с таким логином
            $res = sql("SELECT `login` FROM `users` WHERE `login` = '$login'",1);

            if (isset($res)) {
                $error[] = "Пользователь с таким логином уже существует!";
                
            } else {//Регистрация нового пользователя
                $db->query("INSERT INTO `users` (`login`, `passwd`, `email`) 
                                        VALUES ('$login','".md5($pass)."','$email')");
                $latest_id = $db->insert_id;
                $_SESSION['iduser']=$latest_id;

                $db->query("INSERT INTO `profile` (`id_user`, `name`, `second_name`) 
                                        VALUES ($latest_id,'$name','$secondName')");

                header('Location: /user/');
            }        
    }
}
display("registration", compact('title','error','login','email','regfl','name','secondName'));