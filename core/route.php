<?php

//Проверяем что у нас в параметре 'act', если пусто значит пользователь открыл корень сайта / и надо вывести модуль /main
$module = ($_GET['act'] != "" ? $_GET['act'] : "main");
//debug($module);
//Собираем в переменную полный путь к подключаемому модулю
$fullPath = CONTROLLER_PATH.$module.".php";
//debug($fullPath);
//Если файл не существует вызываем функцию errorPage
if( !file_exists($fullPath) ) {
    errorPage();   
}

//Подключаем сам модуль со всей логикой страницы
include $fullPath; 