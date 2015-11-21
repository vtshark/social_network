<?php 
if (isset($_SESSION['iduser']))  {
    header('Location: /user/');
}

$title = "Авторизация";
display("main", compact('title', 'user'));  

