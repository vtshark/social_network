<?php
$arr_user=checklogin($db);
$user=$arr_user['login'];
$arr_out=array();
$title="Мои друзья"; 
display('friends', compact('title', 'user','arr_out'));
