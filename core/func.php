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
function checklogin() {
    global $db;
    if (!isset($_SESSION['iduser'])) {
         header('Location: /login/');
         exit();
    }
    
    $userID = $_SESSION['iduser'];
    //обновление времени  online у пользвателя
    $db->query("UPDATE `users` SET `online`=NOW()  WHERE `id`= {$userID}");
    $userInfo = sql(" SELECT `id`, `login`, `online` FROM `users` WHERE `id` = {$userID}",1);
    
    return $userInfo;
}

//////////выборка сообщений пользователя с другом
function readMsg($db,$iduser,$idFriend,$prizn) {
    $arrOut = array(); 
    $arrOut1 = array();
    //если первый запуск ф-ии после загрузки диалога, считываем последние 10 сообщений
    //echo $prizn;
    if ($prizn==0) {
        $arrOut = sql("SELECT `users_vs_messages`.*, `msg`.`text`, `profile`.`name` as `autor`  
            FROM (`users_vs_messages` INNER JOIN `msg` ON `msg`.`id` = `users_vs_messages`.`id_msg`)  
            INNER JOIN `profile` ON `profile`.`id_user`=`users_vs_messages`.`id_autor`  
            WHERE `users_vs_messages`.`id_user` = {$iduser} AND `users_vs_messages`.`id_Friend`={$idFriend}  
            AND `users_vs_messages`.`prizn_read`=true  
            ORDER BY id DESC LIMIT 10",0);
        $arrOut=array_reverse($arrOut);
    }
    //при последующих вызовах функции считываем только новые сообщения
        $arrOut1=sql("SELECT `users_vs_messages`.*, `msg`.`text`, `profile`.`name` as `autor`  
        FROM (`users_vs_messages` INNER JOIN `msg` ON `msg`.`id` = `users_vs_messages`.`id_msg`)  
        INNER JOIN `profile` ON `profile`.`id_user`=`users_vs_messages`.`id_autor`  
        WHERE `users_vs_messages`.`id_user` = {$iduser} AND `users_vs_messages`.`id_Friend`={$idFriend}  
        AND `users_vs_messages`.`prizn_read`=false  
        ORDER BY id",0);
    $result = array_merge ($arrOut, $arrOut1);

    //устанавливем признак просмотренного сообщения
    $db->query("UPDATE `users_vs_messages` SET `prizn_read`=true  WHERE `id_user`={$iduser} and `prizn_read`=false and 
                `id_Friend`={$idFriend}");

    return $result;
}

function sql($query,$pr) {
    global $db; //Глобал во имя добра
    $q = $db->query($query);
    $out = [];
    
    while($res = $q->fetch_assoc()) {
        $out[] = $res;
    }
    //count($out)
    if($pr == 1) {
        return $out[0];
    }
    
    return $out;
}

function getOnline($time) {
    $strOut = '';
    $timeDiff = floor( (time()-strtotime($time))/60 );
    
    if ($time == '' || $timeDiff > 30) {
        return "<p>не в сети</p>";
    }
    
    if ($timeDiff>5) {
        $strOut = "<p>был в сети $timeDiff мин назад</p>";
    } else {
        $strOut = "<p>в сети</p>";
    }
        
   return $strOut;
}

function getAva($id) {
    $ava="";
    $filename="static/users/id/$id/ava.jpg";
    if (file_exists($filename)) {
        $ava = '<img src=/'.$filename.'>';
    }
    return $ava;
}
function getAvaAlbum($id,$idUserWall) {
    $ava="";
    $filename="static/users/id/{$idUserWall}/albums/{$id}/ava/ava.jpg";
    //debug($filename);
    if (file_exists($filename)) {
        $ava = '<img src=/'.$filename.'>';
    }
    return $ava;
}
function uploadFoto($uploadfile,$tmpfile,$w,$h) {
    $size=getimagesize($tmpfile);
    $koef=1;
    if ($size[0]>$size[1])  {
        $koef=$w/$size[0];
    } else {
        $koef=$h/$size[1];
    }
    $w=(int)$size[0]*$koef;
    $h=(int)$size[1]*$koef;
    $new=imagecreatetruecolor($w,$h);
    $im=imagecreatefromjpeg($tmpfile);
    imagecopyresampled($new,$im,0,0,0,0,$w,$h,$size[0],$size[1]);
    imagejpeg($new,$uploadfile);
}