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

//проверка авторизации, возврат id и login
function checklogin($db) {
    $arr_user=array();
    if (isset($_SESSION['iduser']))  {
    $iduser=$_SESSION['iduser'];
    $q = $db->query(" SELECT `id`, `login` FROM `users` WHERE `id` = $iduser");
        $res = $q->fetch_assoc();
        $arr_user=$res;
    } else {
        header('Location: /login/');
    }
    return $arr_user;
}

//////////выборка сообщений пользователя с другом
function readMsg($db,$iduser,$idFriend) {
    $q = $db->query(" SELECT `users_vs_messages`.*, `msg`.`text`, users.login as autor  
        FROM (`users_vs_messages` INNER JOIN `msg` ON `msg`.`id` = `users_vs_messages`.`idmsg`)
        INNER JOIN `users` ON `users`.id=`users_vs_messages`.`idautor`
        WHERE `users_vs_messages`.`iduser` = $iduser AND `users_vs_messages`.`idFriend`=$idFriend 
        ORDER BY `users_vs_messages`.`data` DESC");
    $arrOut = array();
    while($res = $q->fetch_assoc()) {
        $arrOut[] = $res;
    }
    return $arrOut;
}