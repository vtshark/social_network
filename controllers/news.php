<?php            
$arr_user=checklogin($db);
$user=$arr_user['login'];
$iduser=$arr_user['id'];
///сохранение новой записи в новостях
if (isset($_POST['textnews'])) {
    if ($iduser!='') {
        $strsql="INSERT INTO `news`(`iduser`,`idautor`,`data`,`text`)
                VALUES ($iduser,$iduser,NOW(),'".$_POST['textnews']."')";
                $q = $db->query($strsql);
    }
}
///удаление новости 
if (isset($_POST['inddelnews'])) {
    if ($iduser!='') {
        $strsql="DELETE FROM `news` WHERE id = ".$_POST['inddelnews'];
                $q = $db->query($strsql);
    }
}
/////////////////////////////////////

$q = $db->query(" SELECT news.id, news.text, news.data, users.login  
FROM `news` INNER JOIN users ON users.id = news.idautor 
WHERE `iduser` = $iduser ORDER BY `data` DESC");
$i=0;
$arr_out = array();
while($res = $q->fetch_assoc()) {
    $arr_out[] = $res;
}
$title="Новости";

display("news", compact('title', 'user','arr_out'));            