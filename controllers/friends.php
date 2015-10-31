<?php
$arr_user=checklogin($db);
$user=$arr_user['login'];
$iduser=$arr_user['id'];
$arrOut=array();
$title="Мои друзья"; 
//удаление из друзей
if (isset($_POST['idDelFriend'])) {
    //debug($_POST['idDelFriend']); 
        $strsql="DELETE FROM `friends` WHERE `idfriend` = ".$_POST['idDelFriend']." AND `iduser`=$iduser";
                $q = $db->query($strsql);
}
//Добавление в друзья
if (isset($_POST['idAddFriend'])) {
        $strsql="INSERT INTO `friends`(`iduser`,`idfriend`)
                VALUES ($iduser,".$_POST['idAddFriend'].")";
                $q = $db->query($strsql);
}
//формируем массив друзей
$str=" SELECT `friends`.`idfriend`, `users`.`login` 
FROM `friends` INNER JOIN `users` ON `users`.`id` = `friends`.`idfriend` 
WHERE `friends`.`iduser` = $iduser";

$q = $db->query($str);

$friends = array();
while($res = $q->fetch_assoc()) {
    $arrOut[] = $res;
    $friends[] = $res['idfriend'];
}

$friends = join(',', $friends);

// поиск пользователя
$find='';
$findUser='';
$arrFindUser=array();

if (isset($_GET['m'])) {
    $find=$_GET['m'];
    $findUser=$_POST['find_user'];
    if (($find=='find') && ($findUser!='')) {
        if (!empty($friends)) {
            $buf="AND `id` NOT IN ({$friends})";
        } else {
            $buf="";
        }
        
        $q = $db->query(" SELECT login, id FROM `users` WHERE `login` like '%$findUser%' $buf");
        while($res = $q->fetch_assoc()) {
                $arrFindUser[]= $res;
        }
    }
}
display('friends', compact('title','find', 'user','arrOut','arrFindUser','findUser'));
