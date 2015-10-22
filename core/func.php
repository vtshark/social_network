<?php

//Вспомогательные функции

function errorPage() {
    //Функция die аналогичная exit
    die("404");
}


//Ищет шаблон отображения и выводит его;
function display($template, $data = array()) {
    //Собираем общий путь для дальнейшего использования.
    //Когда один и тот же код используется более одного раза - стоит вынести его в функцию/переменную
    $fullPath = getTemplatePath($template);
    
    //Если файл не существует, ничего не отображаем, с включеным дебагом выводит сообщение об этом
    if( !file_exists($fullPath) ) {
        debug("Can't find template name ".$fullPath);
        return;
    }
    
    //Создает переменный с массива, документация http://php.net/manual/ru/function.extract.php
    extract($data);
    
    //Начинаем буфер, не обязательно, но может пригодится если мы захотим обработать получившийся шаблон
    ob_start();
    
    //Подключаем хедер
    include getTemplatePath("include/header");
    
    //Подключаем контроллер(модуль)
    include $fullPath;
    
    //Подключаем футер
    include getTemplatePath("include/footer");
    
    //Забираем в переменную весь буфер вывода и очищаем его
    $page = ob_get_clean();
    
    //Выводим клиенту наш буфер и завершаем работу скрипта
    exit($page);
}


//Сокращение для функции htmlspecialchars
function out($str) {
    return htmlspecialchars($str);
}


function getTemplatePath($template) {
    return TEMPLATE_PATH.$template.TEMPLATE_TYPE;
}


//Функция для вывода отладочной ифнормации
function debug($string) {
    if(DEBUG) {
        var_dump($string);
    }
}