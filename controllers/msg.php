<?php
$arr_user = checklogin($db);
$user = $arr_user['login'];
$iduser = $arr_user['id'];
$title = "Сообщения";
$idFriend = '';
$logFriend = '';
$online = '';
$arrOut = array();
$avaArr = array();
// если выбран диалог, id собеседника
if (isset($_GET['idf'])) {
    $idFriend = $db->real_escape_string($_GET['idf']);
    if ($iduser!=$idFriend) {
        $q = $db->query(" SELECT `login`,`online` FROM `users` WHERE `id` = $idFriend");
        $res = $q->fetch_assoc();
        $logFriend = $res['login'];
        $online=getOnline($res['online']);
    }    
    if ($logFriend=="") {
            header('Location: /msg/');
    }

    //выборка сообщений пользователя с другом
    //$arrOut=readMsg($db,$iduser,$idFriend);

} else {
// если диалог не выбран
    $q = $db->query("SELECT `users_vs_messages`.`id_friend`, `users`.`login` as `friend`, `users`.`online` 
        FROM `users_vs_messages` INNER JOIN `users` ON `users`.`id`=`users_vs_messages`.`id_friend` 
        WHERE `users_vs_messages`.`id_user` = $iduser 
        GROUP BY `users_vs_messages`.`id_friend`");
    while($res = $q->fetch_assoc()) {
        $arrOut[] = $res;
        
        $filename='static/users/id/'.$res['id_friend'].'/ava.jpg';
        if (file_exists($filename)) {
                $avaArr[ $res['id_friend'] ] = '<img src=/'.$filename.'>';
            } else {
                $avaArr[ $res['id_friend'] ] = '';
            }
    }
}   

display("msg", compact('title', 'user','iduser','arrOut','logFriend','idFriend','online','avaArr'));  