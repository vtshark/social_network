<?php
$arr_user = checklogin();
$iduser = $arr_user['id'];
$arrOut1=sql("SELECT count(`id`) as `kol`  FROM `users_vs_messages` WHERE `id_user` = {$iduser} AND `prizn_read`=false",1);
echo "Сообщения";
if ($arrOut1["kol"]!=0) {
    echo "<b>(".$arrOut1["kol"].")</b>";
}
//debug($arrOut1);