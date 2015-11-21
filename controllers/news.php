<?php            
$arr_user = checklogin();
$user = $arr_user['login'];
$iduser = $arr_user['id'];

$title="Новости";

display("news", compact('title', 'user'));