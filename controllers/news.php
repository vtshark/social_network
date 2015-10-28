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

$q = $db->query(" SELECT news.*, users.login 
FROM `news` INNER JOIN users ON users.id = news.idautor 
WHERE `iduser` = $iduser ORDER BY `data` DESC");
$i=0;
while($res = $q->fetch_assoc()) {
    $i=$res['id'];
    $arr_out[$i]['text']=$res['text'];
    $arr_out[$i]['data']=$res['data'];
    $arr_out[$i]['autor']=$res['login'];
}
$title="Новости";

display("news", compact('title', 'user','arr_out'));            