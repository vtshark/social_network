<?php 
session_start();
require "config.php";
require "db.php";
require "func.php";
$iduser=$_SESSION['iduser'];
$idFriend=$_GET['idfriend'];
//добавление сообщения
if (isset($_GET['newMsg'])) {
        if ($_GET['newMsg']!='') {
            //сохраение сообщения
            $textmsg = out($db->real_escape_string($_GET['newMsg']));
            $strsql = "INSERT INTO `msg` (`text`) VALUES ('$textmsg')";
            $q = $db->query($strsql);

            //таблица связей
            $latest_id = $db->insert_id;

            $strsql = "INSERT INTO `users_vs_messages` (`id_user`,`id_msg`,`date`,`id_autor`,id_friend,prizn_read) 
                VALUES ($iduser,$latest_id,NOW(),$iduser,$idFriend,false)";
            $q = $db->query($strsql);

            $strsql = "INSERT INTO `users_vs_messages` (`id_user`,`id_msg`,`date`,`id_autor`,id_friend,prizn_read) 
                VALUES ($idFriend,$latest_id,NOW(),$iduser,$iduser,false)";
            $q = $db->query($strsql);
        }    
    }