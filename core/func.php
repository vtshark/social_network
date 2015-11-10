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
    $arr_user = array();
    if (isset($_SESSION['iduser']))  {
        $iduser = $_SESSION['iduser'];
        //обновление времени  online у пользвателя
        $strsql = "UPDATE `users` SET `online`=NOW()  WHERE `id`=$iduser";
        $q = $db->query($strsql);    

        $q = $db->query(" SELECT `id`, `login`, `online` FROM `users` WHERE `id` = $iduser");
            $res = $q->fetch_assoc();
            $arr_user = $res;

    } else {
        header('Location: /login/');
    }
    return $arr_user;
}

//////////выборка сообщений пользователя с другом
function readMsg($db,$iduser,$idFriend,$prizn) {
    $arrOut = array();    
    //если первый запуск ф-ии после загрузки диалога, считываем последние 10 сообщений
    if ($prizn==0) {
        $q = $db->query(" SELECT `users_vs_messages`.*, `msg`.`text`, `users`.`login` as `autor` 
            FROM (`users_vs_messages` INNER JOIN `msg` ON `msg`.`id` = `users_vs_messages`.`id_msg`)
            INNER JOIN `users` ON `users`.id=`users_vs_messages`.`id_autor`
            WHERE `users_vs_messages`.`id_user` = $iduser AND `users_vs_messages`.`id_Friend`=$idFriend 
            AND `users_vs_messages`.`prizn_read`=true 
            ORDER BY id DESC LIMIT 10");
        while($res = $q->fetch_assoc()) {
            $arrOut[] = $res;
        }
        $arrOut=array_reverse($arrOut);
    }
    //при последующих вызовах функции считываем только новые сообщения
    $q = $db->query(" SELECT `users_vs_messages`.*, `msg`.`text`, `users`.`login` as `autor` 
        FROM (`users_vs_messages` INNER JOIN `msg` ON `msg`.`id` = `users_vs_messages`.`id_msg`)
        INNER JOIN `users` ON `users`.id=`users_vs_messages`.`id_autor`
        WHERE `users_vs_messages`.`id_user` = $iduser AND `users_vs_messages`.`id_Friend`=$idFriend 
        AND `users_vs_messages`.`prizn_read`=false 
        ORDER BY id");

    while($res = $q->fetch_assoc()) {
        $arrOut[] = $res;
    }

    //устанавливем признак просмотренного сообщения
    $strsql = "UPDATE `users_vs_messages` SET `prizn_read`=true  WHERE `id_user`=$iduser and 
    `prizn_read`=false";
    $q = $db->query($strsql);

    return $arrOut;
}
function getOnline($time) {
    $strOut='';
    if ($time!='') {
        $timeDiff=(int)((strtotime(NOW)-strtotime($time))/60);
        
        if ($timeDiff>5) {
            if ($timeDiff<30) {
                $strOut="<p>был в сети $timeDiff мин назад</p>";
            } else {
                $strOut="<p>не в сети</p>";
            }
            
        } else {
            $strOut="<p>в сети</p>";
        }

    } else {
        $strOut="<p>не в сети</p>";
    }
   return $strOut;
}

