<?php
$arr_user = checklogin();
$iduser = $arr_user['id'];
$user = $arr_user['login'];
$arr_out = array();

$idUserWall = '';
$logUserWall = '';


include "userHead.php";


$title = $name." ".$secondName;
///сохранение новой записи на стене
if (isset($_POST['textnews'])) {
    $textnews = out($db->real_escape_string($_POST['textnews']));
    if (strlen ($textnews)<1000) {
        $strsql = "INSERT INTO `news`(`id_user`,`id_autor`,`date`,`text`) 
                VALUES ($idUserWall,$iduser,NOW(),'$textnews')";
        $db->query($strsql);
    }
}
///удаление записи со стены
if (isset($_POST['inddelnews'])) {
    if ($iduser!='') {
        $strsql = "DELETE FROM `news` WHERE `id` = ".$_POST['inddelnews'];
        $q = $db->query($strsql);
    }
}

///Чтение таблицы стены пользователя/////
if ($mode=="") {
    $q = $db->query(" SELECT `news`.`id`, `news`.`text`, `news`.`date`, `news`.`id_autor`, `profile`.`name` as `autor` 
    FROM `news` INNER JOIN `profile` ON `profile`.`id_user` = `news`.`id_autor` 
    WHERE `news`.`id_user` = $idUserWall ORDER BY `news`.`id` DESC LIMIT 20");
    $i=0;
    $arr_out = array();
    while($res = $q->fetch_assoc()) {
        $arr_out[] = $res;
    }
}

display("user", compact('title','iduser','arr_out','idUserWall','online','name','secondName'));