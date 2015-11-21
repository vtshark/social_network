<?php 
$iduser=$_SESSION['iduser'];
checklogin();
if (isset($_POST['idfriend'])) {
  $idFriend=$_POST['idfriend'];
}
if (isset($_POST['newMsg'])) {
  $textmsg=out($db->real_escape_string($_POST['newMsg']));
}
//
//добавление сообщения
if ( ($idFriend!='') && ($textmsg!='') ) {
    //сохраение сообщения
    $strsql = "INSERT INTO `msg` (`text`) VALUES ('$textmsg')";
    $db->query($strsql);

    //таблица связей
    $latest_id = $db->insert_id;

    $strsql = "INSERT INTO `users_vs_messages` (`id_user`,`id_msg`,`date`,`id_autor`,id_friend,prizn_read) 
        VALUES ($iduser,$latest_id,NOW(),$iduser,$idFriend,false)";
    $db->query($strsql);

    $strsql = "INSERT INTO `users_vs_messages` (`id_user`,`id_msg`,`date`,`id_autor`,id_friend,prizn_read) 
        VALUES ($idFriend,$latest_id,NOW(),$iduser,$iduser,false)";
    $db->query($strsql);

}

exit();