<?php
$arr_user = checklogin();
$iduser = $arr_user['id'];
$out=array();
////сообщения

$out["msg"] = "Сообщения";
$arrOut = sql("SELECT count(`id`) as `kol`  FROM `users_vs_messages` WHERE `id_user` = {$iduser} AND `prizn_read`=false",1);
if ($arrOut["kol"]!=0) {
    $out["msg"] .= "(<b>".$arrOut["kol"]."</b>)";
}
////входящие заявоки в друзья
$out["friends"] = "Друзья";
$str = "SELECT count(`id`) as kol FROM `friends_requests` WHERE `id_friend` = {$iduser} AND `confirmed` = false";
$friends_requests = sql($str,1);
if ($friends_requests["kol"]!=0) {
   $out["friends"] .= "(<b>".$friends_requests["kol"]."</b>)";
}
echo json_encode($out);
exit();