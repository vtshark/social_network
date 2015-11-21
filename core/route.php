<?php

//Проверяем что у нас в параметре 'act', если пусто значит пользователь открыл корень сайта / и надо вывести модуль /main
//$module = ($_GET['act'] != "" ? $_GET['act'] : "main");

//debug($_SERVER['REQUEST_URI']);
//echo "<br>";

$request=explode("/",$_SERVER['REQUEST_URI']);
array_splice($request,0,1);
$request=array_filter($request);
//debug($request);
$module=$request[0];

//Собираем в переменную полный путь к подключаемому модулю
if ($module=='') $module='user';
//debug($module);
//echo "<br>";
$fullPath = CONTROLLER_PATH.$module.".php";
//echo "<br>";
//debug($fullPath);
//echo "<br>";

//Если файл не существует вызываем функцию errorPage
if( !file_exists($fullPath) ) {
    errorPage();   
}

//Подключаем сам модуль со всей логикой страницы
include $fullPath; 