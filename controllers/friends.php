<?php
$arr_user = checklogin();
$user = $arr_user['login'];
$iduser = $arr_user['id'];
$arrOut = array();
$title = "Мои друзья"; 

include "userHead.php";

if ($idUserWall==$iduser) {
    //добавление в друзья
    if (($request[2]=='addf') && ($request[3]!='')) {
    
        $idAddFriend=$db->real_escape_string($request[3]);
        //проверка get запроса, есть ли у пользователя в базе запрос с таким id друга
        $str=" SELECT `friends_requests`.`id_user` 
        FROM `friends_requests` 
        WHERE `friends_requests`.`id_friend` = $iduser AND `friends_requests`.`id_user` =$idAddFriend  
        AND `friends_requests`.`confirmed` = false";
        $res = sql($str,1);

        if ($res) {
            //таблица друзей
            $db->query("INSERT INTO `friends`(`iduser`,`idfriend`)  VALUES ($iduser,$idAddFriend)");
            
            $db->query("INSERT INTO `friends`(`iduser`,`idfriend`)  VALUES ($idAddFriend,$iduser)");
                
            //признак запроса в друзья
            $db->query("UPDATE `friends_requests` SET `confirmed`=true  WHERE `id_friend`=$iduser AND `id_user`=$idAddFriend");
        }
    }

    //удаление из друзей
    if (isset($_POST['idDelFriend'])) {
        $db->query("DELETE FROM `friends` WHERE `idfriend` = ".$_POST['idDelFriend']." AND `iduser`=$iduser");
    
        $db->query("DELETE FROM `friends` WHERE `idfriend` = $iduser AND `iduser`=".$_POST['idDelFriend']);
    }
    //Создание новой заявки в друзья
    if (isset($_POST['requestAddFriend'])) {
        $db->query("INSERT INTO `friends_requests` (`id_user`,`id_friend`,`confirmed`) 
                    VALUES ($iduser,".$_POST['requestAddFriend'].",false)");
    }
    //отклонение входящей заявки в друзья
    if (($request[2]=='delr') && ($request[3]!='')) {
        $idDelReq=$db->real_escape_string($request[3]);
        $db->query("DELETE FROM `friends_requests` WHERE `id_friend` = $iduser AND `id` = $idDelReq AND `confirmed`=false");
    }
    //отклонение исходящей заявки в друзья
    if (($request[2]=='delmyr') && ($request[3]!='')) {
        $idDelReq=$db->real_escape_string($request[3]);
        $db->query("DELETE FROM `friends_requests` WHERE `id_user` = $iduser AND `id` = $idDelReq  AND `confirmed`=false");
    }
}


//формируем массив друзей
$friends = array();
$friends[]=$iduser;
    
$str=" SELECT `friends`.`idfriend`, `users`.`online`, `profile`.`name`, `profile`.`second_name` 
FROM (`friends` INNER JOIN `users` ON `users`.`id` = `friends`.`idfriend`) 
            INNER JOIN `profile` ON `profile`.`id_user` = `friends`.`idfriend`
WHERE `friends`.`iduser` = {$idUserWall}";
$q = $db->query($str);
while($res = $q->fetch_assoc()) {
    $arrOut[] = $res;
    $friends[] = $res['idfriend'];
}


$friends_requests=array();
$my_requests=array();
$arrFindUser = array();
if ($idUserWall==$iduser) {
    ////массив входящих заявок в друзья
    $str="SELECT `friends_requests`.`id_user`,`friends_requests`.`id` as `id_req`, `users`.`online`, 
                    `profile`.`name`, `profile`.`second_name` 
    FROM (`friends_requests` INNER JOIN `users` ON `users`.`id` = `friends_requests`.`id_user`) 
                        INNER JOIN `profile` ON `profile`.`id_user` = `friends_requests`.`id_user`
    WHERE `friends_requests`.`id_friend` = {$iduser} AND `friends_requests`.`confirmed` = false";
    $friends_requests = sql($str,0);

    ////массив исходящих заявок пользователя
    $str="SELECT `friends_requests`.`id_friend`,`friends_requests`.`id` as `id_req`, 
                    `users`.`online`, `profile`.`name`, `profile`.`second_name` 
    FROM (`friends_requests` INNER JOIN `users` ON `users`.`id` = `friends_requests`.`id_friend`) 
                                INNER JOIN `profile` ON `profile`.`id_user` = `friends_requests`.`id_friend`
    WHERE `friends_requests`.`id_user` = $iduser AND `friends_requests`.`confirmed` = false";
    $my_requests = sql($str,0);

    $friends = join(',', $friends);

    // поиск пользователя
    $find = '';
    $findName = '';
    $findSName = '';

    if ($request[2]=='find') {
        $find =$request[2];
        $findName = $_POST['find_user_name'];
        $findSName = $_POST['find_user_sname'];
        if ( ($findName!='') || ($findSName!='') ) {
            if (!empty($friends)) {
                $buf = "AND `id_user` NOT IN ({$friends})";
            } else {
                $buf = "";
            }
        
            $bufn="";
            if ($findName!="") $bufn=" `name` like LOWER('%$findName%')";
            if ($findSName!="") {
                if ($bufn!="") {
                    $bufn=$bufn." AND ";
                }    
                    $bufn=$bufn."`second_name` like '%$findSName%'";
            }
            $arrFindUser = sql("SELECT `id_user`,`second_name`,`name` as name  FROM `profile` WHERE $bufn $buf",0);
        }
    }
}


display('friends',compact('title','find','user','arrOut',
        'arrFindUser','findName','findSName','friends_requests','my_requests',
        'name','secondName','online','iduser','idUserWall'));
