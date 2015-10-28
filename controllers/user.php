<?php
$arr_user=checklogin($db);
$user=$arr_user['login'];
$arr_out=array();
$title=$user; 
    display("user", compact('title', 'user','arr_out'));
