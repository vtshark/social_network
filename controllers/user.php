<?php
$arr_user = checklogin($db);
$user = $arr_user['login'];
$iduser = $arr_user['id'];
$arr_out = array();
 
$idUserWall = '';
$logUserWall = '';

if (isset($_GET['id'])) {
    $idUserWall = $_GET['id'];
}
if ($idUserWall=='') {
 $idUserWall = $iduser;
 $logUserWall = $user;
} else {
    $q = $db->query(" SELECT `login` FROM `users` WHERE `id` = $idUserWall");
    $res = $q->fetch_assoc();
    $logUserWall = $res['login'];
    if ($logUserWall=='') {
        header('Location:/user/');
    }
}
$title = $logUserWall;
///сохранение новой записи на стене
if (isset($_POST['textnews'])) {
    $textnews = $db->real_escape_string($_POST['textnews']);
    $strsql = "INSERT INTO `news`(`id_user`,`id_autor`,`date`,`text`)
            VALUES ($idUserWall,$iduser,NOW(),'$textnews')";
    $q = $db->query($strsql);

}
///удаление записи со стены
if (isset($_POST['inddelnews'])) {
    if ($iduser!='') {
        $strsql = "DELETE FROM `news` WHERE `id` = ".$_POST['inddelnews'];
                $q = $db->query($strsql);
    }
}
/////////////////////////////////////
$q = $db->query(" SELECT `news`.`id`, `news`.`text`, `news`.`date`, `news`.`id_autor`, `users`.`login` as `autor` 
FROM `news` INNER JOIN `users` ON `users`.`id` = `news`.`id_autor` 
WHERE `id_user` = $idUserWall ORDER BY `news`.`id` DESC LIMIT 20");
$i=0;
$arr_out = array();
while($res = $q->fetch_assoc()) {
    $arr_out[] = $res;
}
display("user", compact('title', 'user','iduser','arr_out','idUserWall','logUserWall'));