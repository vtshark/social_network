<?php
$arr_user = checklogin($db);
$user = $arr_user['login'];
$iduser = $arr_user['id'];
$arrOut = array();
$avaArr = array();
$title = "Мои друзья"; 

//добавление в друзья
if (isset($_GET['addf'])) {
    $idAddFriend=$db->real_escape_string($_GET['addf']);

    //проверка get запроса, есть ли у пользователя в базе запрос с таким id друга
    $str=" SELECT `friends_requests`.`id_user` 
    FROM `friends_requests` 
    WHERE `friends_requests`.`id_friend` = $iduser AND `friends_requests`.`id_user` =$idAddFriend  
    AND `friends_requests`.`confirmed` = false";
    $q = $db->query($str);
    $res = $q->fetch_assoc();

    if ($res) {
        //таблица друзей
        $strsql = "INSERT INTO `friends`(`iduser`,`idfriend`)
            VALUES ($iduser,$idAddFriend)";
        $q = $db->query($strsql);
        $strsql = "INSERT INTO `friends`(`iduser`,`idfriend`)
            VALUES ($idAddFriend,$iduser)";
        $q = $db->query($strsql);
        //признак запроса в друзья
        $strsql = "UPDATE `friends_requests` SET `confirmed`=true  WHERE `id_friend`=$iduser AND `id_user`=$idAddFriend";
        $q = $db->query($strsql);

    }
}

//удаление из друзей
if (isset($_POST['idDelFriend'])) {
        $strsql = "DELETE FROM `friends` WHERE `idfriend` = ".$_POST['idDelFriend']." AND `iduser`=$iduser";
                $q = $db->query($strsql);
}
//Создание новой заявки в друзья
if (isset($_POST['requestAddFriend'])) {
    $strsql = "INSERT INTO `friends_requests` (`id_user`,`id_friend`,`confirmed`) 
                                        VALUES ($iduser,".$_POST['requestAddFriend'].",false)";
    $q = $db->query($strsql);
}
//отклонение входящей заявки в друзья
if (isset($_GET['delr'])) {
    $idDelReq=$db->real_escape_string($_GET['delr']);

    $strsql = "DELETE FROM `friends_requests` WHERE `id_friend` = $iduser AND `id` = $idDelReq AND `confirmed`=false";
    $q = $db->query($strsql);
}

//отклонение исходящей заявки в друзья
if (isset($_GET['delmyr'])) {
    $idDelReq=$db->real_escape_string($_GET['delmyr']);
    $strsql = "DELETE FROM `friends_requests` WHERE `id_user` = $iduser AND `id` = $idDelReq  AND `confirmed`=false";
    $q = $db->query($strsql);
}

//формируем массив друзей
$friends = array();
$friends[]=$iduser;

$str=" SELECT `friends`.`idfriend`, `users`.`login`, `users`.`online`  
FROM `friends` INNER JOIN `users` ON `users`.`id` = `friends`.`idfriend` 
WHERE `friends`.`iduser` = $iduser";

$q = $db->query($str);

while($res = $q->fetch_assoc()) {
    $arrOut[] = $res;
    $friends[] = $res['idfriend'];
    $filename='static/users/id/'.$res['idfriend'].'/ava.jpg';
    if (file_exists($filename)) {
        $avaArr[ $res['idfriend'] ] = '<img src=/'.$filename.'>';
    } else {
        $avaArr[ $res['idfriend'] ] = '';
    }
}
////массив входящих заявок в друзья
$friends_requests=array();
$str=" SELECT `friends_requests`.`id_user`,`friends_requests`.`id` as `id_req`, `users`.`login`, `users`.`online`  
FROM `friends_requests` INNER JOIN `users` ON `users`.`id` = `friends_requests`.`id_user` 
WHERE `friends_requests`.`id_friend` = $iduser AND `friends_requests`.`confirmed` = false";
$q = $db->query($str);
while($res = $q->fetch_assoc()) {
    $friends_requests[] = $res;
    $filename='static/users/id/'.$res['id_user'].'/ava.jpg';
    if (file_exists($filename)) {
        $avaArr[ $res['id_user'] ] = '<img src=/'.$filename.'>';
    } else {
        $avaArr[ $res['id_user'] ] = '';
    }
}    
////массив исходящих заявок пользователя
$my_requests=array();
$str=" SELECT `friends_requests`.`id_friend`,`friends_requests`.`id` as `id_req`, `users`.`login`, `users`.`online`  
FROM `friends_requests` INNER JOIN `users` ON `users`.`id` = `friends_requests`.`id_friend` 
WHERE `friends_requests`.`id_user` = $iduser AND `friends_requests`.`confirmed` = false";
$q = $db->query($str);
while($res = $q->fetch_assoc()) {
    $my_requests[] = $res;
    $filename='static/users/id/'.$res['id_friend'].'/ava.jpg';
    if (file_exists($filename)) {
        $avaArr[ $res['id_friend'] ] = '<img src=/'.$filename.'>';
    } else {
        $avaArr[ $res['id_friend'] ] = '';
    }
}    

$friends = join(',', $friends);

// поиск пользователя
$find = '';
$findUser = '';
$arrFindUser = array();

if (isset($_GET['m'])) {
    $find = $_GET['m'];
    $findUser = $_POST['find_user'];
    if (($find=='find') && ($findUser!='')) {
        if (!empty($friends)) {
            $buf = "AND `id` NOT IN ({$friends})";
        } else {
            $buf = "";
        }
        
        $q = $db->query(" SELECT `login`, `id` FROM `users` WHERE `login` like '%$findUser%' $buf");
        while($res = $q->fetch_assoc()) {
                $arrFindUser[] = $res;
            
            $filename='static/users/id/'.$res['id'].'/ava.jpg';
            if (file_exists($filename)) {
                $avaArr[ $res['id'] ] = '<img src=/'.$filename.'>';
            } else {
                $avaArr[ $res['id'] ] = '';
            }

        }
    }
}
display('friends', compact('title','find', 'user','arrOut','arrFindUser','findUser','avaArr','friends_requests','my_requests'));
