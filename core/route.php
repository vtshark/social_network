<?php
//Парсит строку запроса и подключает модуль если он существует

$module = ($_GET['act'] != "" ? $_GET['act'] : "main");

$fullPath = MODULE_PATH.$module.".php";

if( !file_exists($fullPath) ) {
    errorPage();   
}


include $fullPath; 
