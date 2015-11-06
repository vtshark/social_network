<?php
$arr_user = checklogin($db);
$user = $arr_user['login'];
$iduser = $arr_user['id'];
$title = "Сообщения";
$idFriend = '';
$logFriend = '';
$arrOut = array();

// если выбран диалог, id собеседника
if (isset($_GET['idf'])) {
    $idFriend = $db->real_escape_string($_GET['idf']);
    if ($iduser!=$idFriend) {
        $q = $db->query(" SELECT `login` FROM `users` WHERE `id` = $idFriend");
        $res = $q->fetch_assoc();
        $logFriend = $res['login'];
    }    
    if ($logFriend=="") {
            header('Location: /msg/');
    }

    //выборка сообщений пользователя с другом
    //$arrOut=readMsg($db,$iduser,$idFriend);

} else {
// если диалог не выбран
    $q = $db->query("SELECT `users_vs_messages`.`id_friend`, `users`.`login` as `friend` 
        FROM `users_vs_messages` INNER JOIN `users` ON `users`.`id`=`users_vs_messages`.`id_friend` 
        WHERE `users_vs_messages`.`id_user` = $iduser 
        GROUP BY `users_vs_messages`.`id_friend`");
    while($res = $q->fetch_assoc()) {
        $arrOut[] = $res;
    }
}   

display("msg", compact('title', 'user','iduser','arrOut','logFriend','idFriend'));  