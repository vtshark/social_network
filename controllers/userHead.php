<?php
$online = '<p>в сети</p>';
///если зашли на страницу другого пользователя///

if (isset($request[1]) && $request[0]!='msg') {
    $idUserWall = $request[1];
}

if ($idUserWall=='') {
 $idUserWall = $iduser;
 $logUserWall = $user;
} else {
    $res = sql("SELECT `login`,`online` FROM `users` WHERE `id` = $idUserWall",1);
    $logUserWall = $res['login'];

    $online=getOnline($res['online']);
    
    if ($logUserWall=='') {
        //если указан не существующий ID
        header('Location:/user/');
    }
}

$res = sql("SELECT * FROM `profile` WHERE `id_user` = $idUserWall",1);
$name=$res['name'];
$secondName=$res['second_name'];
