<?php
$idFriend = "";
if (isset($request[1])) {
    $idFriend = $request[1];
}
$prizn = $request[2]; //признак первого считывания сообщений из диалога
$iduser = $_SESSION['iduser'];

$arrOut = readMsg($db,$iduser,$idFriend,$prizn);
echo json_encode($arrOut);
exit();