<?php
$arr_user = checklogin();
$user = $arr_user['login'];
$iduser = $arr_user['id'];
$title = 'Фотоальбомы';
$arrOut = array();
$error = array();
$idAlbum = '';
$dirAlbum = '';

include "userHead.php";

//если выбран альбом
if (($request[2] == 'album') && ($request[3] != '')) {
    $idAlbum = $db->real_escape_string($request[3]);
    $dirAlbum="static/users/id/$idUserWall/albums/$idAlbum/";
}

if ($idUserWall == $iduser) {
    //создание нового альбома
    if ( isset($_POST['newAlbum']) )  {

        $newAlbum = $db->real_escape_string($_POST['newAlbum']);;
        if ($newAlbum != "") {
        
            $uploadDir = 'static/users/id/'.$iduser.'/albums';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir);
            }

            $db->query("INSERT INTO `albums`(`id_user`,`name`) VALUES ($iduser,'$newAlbum')");
            $latest_id = $db->insert_id;

            $uploadDir = $uploadDir.'/'.$latest_id."/";
            mkdir($uploadDir);
        } else {
            $error[] = "Укажите название альбома!";
        }
        
    }
    //удаление альбома
    if (($request[2]=='delAlbum') && ($request[3]!='')) {
        $idDelAlbum = $db->real_escape_string($request[3]);
        $dir = "static/users/id/$iduser/albums/$idDelAlbum/";

        $res1 = sql("SELECT `id` FROM `albums` WHERE `id_user` = $iduser AND `id` = $idDelAlbum",1);
        //если у данного пользователя существовал такой альбом
        if ($res1) {
            //удаление всех файлов из альбома
            $q = $db->query("SELECT `file` FROM `foto` WHERE `id_album`=$idDelAlbum");
            while($res = $q->fetch_assoc()) {
                if (file_exists($dir.$res['file']))  {
                    unlink($dir.$res['file']);
                }        
            }

            $db->query("DELETE FROM `foto` WHERE `id_album` = $idDelAlbum");
            $db->query("DELETE FROM `albums` WHERE `id_user` = $iduser AND `id` = $idDelAlbum");
            rmdir($dir);
        }

    }
    //удаление фото
    if ( ($request[4]='delf') && ($request[5]!='') )  {

        $IdDelFile=$db->real_escape_string($request[5]);

        $res = sql("SELECT `file` FROM `foto` WHERE `id` = $IdDelFile AND `id_album`=$idAlbum",1);
        if ($res) {
            //debug($res['file']);
            if (file_exists($dirAlbum.$res['file']))  {
                unlink($dirAlbum.$res['file']);
            }
            $db->query("DELETE FROM `foto` WHERE `id` = $IdDelFile AND `id_album`=$idAlbum");
        }
    }

    //загрузка фото в альбом
    if (!empty($_FILES)) {
        if ($_FILES['newFoto']['name']!='') {
            $uploadfile=$dirAlbum.$_FILES['newFoto']['name'];
            if (!file_exists($uploadfile))  {
        
                $db->query("INSERT INTO `foto` (`id_album`,`about`,`file`) 
                            VALUES ($idAlbum,'','".$_FILES['newFoto']['name']."')");
                $tmpfile=$_FILES['newFoto']['tmp_name'];
                uploadFoto($uploadfile,$tmpfile,300,300);
        
                //обновление авы фотоальбома
                $uploadDir=$dirAlbum."/ava/";
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir);
                }
                $uploadfile=$uploadDir."ava.jpg";
                uploadFoto($uploadfile,$tmpfile,70,70);
            } else {
                $error[]="Фото с таким именем файла уже добавлено в альбом!";
            }
        } else {
            $error[]="Не выбран файл!";
        }
        
    }
}
//если выбран альбом
if ($idAlbum!='') {

    $arrOut = sql("SELECT * FROM `foto` WHERE `id_album` = $idAlbum ORDER BY `id` DESC",0);

} else {

    $arrOut = sql("SELECT * FROM `albums` WHERE `id_user` = $idUserWall",0);
}    

display('foto', compact('title','user','iduser','arrOut','idAlbum','dirAlbum',
                        'name','secondName','online','idUserWall','error'));