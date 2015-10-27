<?php 
$user='';
$iduser='';
if (isset($_SESSION['user']))  {
   $user=$_SESSION['user'];
   $iduser=$_SESSION['iduser'];
}

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
                //debug($_POST['inddelnews']);

    }
}
/////////////////////////////////////


if ($user=='')  {
    $title = "Авторизация";
    display("main", compact('title', 'user'));
} else {
    $mode='';
    if (isset($_GET['m'])) {
        $mode=$_GET['m'];
    }
    $arr_out=array();
    switch ($mode) {
        case 'friends':
            
            break;
        case 'msg':
            
            break;
        case "news":
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
            break;
        default:    
    }
        
    display("user", compact('title', 'user','mode','arr_out'));
}