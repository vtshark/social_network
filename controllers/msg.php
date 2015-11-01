<?php
$arr_user=checklogin($db);
$user=$arr_user['login'];
$iduser=$arr_user['id'];
$title = "Сообщения";
$idFriend='';
$logFriend='';
$arrOut = array();

// если выбран диалог, id собеседника
if (isset($_GET['idf'])) {
    $idFriend=$_GET['idf'];

    $q = $db->query(" SELECT `login` FROM `users` WHERE `id` = $idFriend");
    $res = $q->fetch_assoc();
    $logFriend=$res['login'];

    //добавление сообщения
    if (isset($_POST['newMsg'])) {
        if ($_POST['newMsg']!='') {
            //сохраение сообщения
            $strsql="INSERT INTO `msg` (`text`) VALUES ('".$_POST['newMsg']."')";
            $q = $db->query($strsql);

            //таблица связей
            $latest_id = $db->insert_id;

            $strsql="INSERT INTO `users_vs_messages` (`iduser`,`idmsg`,`data`,`idautor`,idfriend) 
                VALUES ($iduser,$latest_id,NOW(),$iduser,$idFriend)";
            $q = $db->query($strsql);

            $strsql="INSERT INTO `users_vs_messages` (`iduser`,`idmsg`,`data`,`idautor`,idfriend) 
                VALUES ($idFriend,$latest_id,NOW(),$iduser,$iduser)";
            $q = $db->query($strsql);
        }    
    }
    //выборка сообщений пользователя с другом
    //$arrOut=readMsg($db,$iduser,$idFriend);

} else {
// если диалог не выбран
    //, `users`.`login` as `friend`
    //INNER JOIN `users` ON `users`.`id`=`users_vs_messages`.`idautor`
    $q = $db->query("SELECT `users_vs_messages`.`idfriend`, `users`.`login` as `friend` 
        FROM `users_vs_messages` INNER JOIN `users` ON `users`.`id`=`users_vs_messages`.`idfriend` 
        WHERE `users_vs_messages`.`iduser` = $iduser 
        GROUP BY `users_vs_messages`.`idfriend`");
    while($res = $q->fetch_assoc()) {
        $arrOut[] = $res;
    }
    //var_dump($arrOut);
}   

display("msg", compact('title', 'user','iduser','arrOut','logFriend','idFriend'));  