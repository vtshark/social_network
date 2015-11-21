<?php
$arr_user = checklogin();
$user = $arr_user['login'];
$iduser = $arr_user['id'];
$title = "Сообщения";
$idFriend = '';
$logFriend = '';
$onlineFriend = '';
$arrOut = array();

include "userHead.php";

// если выбран диалог, id собеседника
if (isset($request[1])) {
    $idFriend = $db->real_escape_string($request[1]);
    if ($iduser!=$idFriend) {
        $res = sql("SELECT `users`.`online`, `profile`.`name` 
                FROM `users` INNER JOIN `profile` ON `profile`.`id_user` = `users`.`id` 
                WHERE `users`.`id` = $idFriend",1);
        $logFriend = $res['name'];
        $onlineFriend=getOnline($res['online']);
    }    
    if ($logFriend=="") {
            header('Location: /msg/');
    }

} else {
// если диалог не выбран
    $arrOut=sql("SELECT `users_vs_messages`.`id_friend`, `profile`.`name` as `friend`, `users`.`online` 
                FROM (`users_vs_messages` INNER JOIN `users` ON `users`.`id`=`users_vs_messages`.`id_friend`) 
                    INNER JOIN `profile` ON `profile`.`id_user` = `users_vs_messages`.`id_friend` 
                WHERE `users_vs_messages`.`id_user` = $iduser 
                GROUP BY `users_vs_messages`.`id_friend`",0);
    $arrOut1=sql("SELECT count(`id`) as `kol`,`id_friend`  FROM `users_vs_messages` 
                WHERE `id_user` = {$iduser} AND `prizn_read`=false GROUP BY `id_friend`",0);
    $kolNewMsg=array();
    foreach ($arrOut1 as $v) {
        $kolNewMsg[$v['id_friend']]=$v['kol'];
    }
    //debug($kolNewMsg);
    
}   
$idUserWall=$iduser;
display("msg", compact('title', 'user','iduser','arrOut','logFriend','idFriend','onlineFriend',
                        'name','secondName','online','idUserWall','kolNewMsg'));  