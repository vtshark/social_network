<?php
$arr_user=checklogin($db);
$user=$arr_user['login'];
$iduser=$arr_user['id'];
$title = "Сообщения";
display("msg", compact('title', 'user','iduser'));  