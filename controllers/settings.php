<?php
$arr_user = checklogin();
$user = $arr_user['login'];
$iduser = $arr_user['id'];
$title = 'Настройки аккаунта';
$error = array();
$arrErrors = array(
    "Размер принятого файла превысил максимально допустимый размер - 5mb.",
    "Размер принятого файла превысил максимально допустимый размер - 5mb.",
    "Загружаемый файл был получен только частично.",
    "Файл не был загружен.",
    "отсутствует временная папка.",
    "Не удалось записать файл на диск",
    "PHP остановил загрузку файлов"
    );
/////обновление аватара////
if ( !empty($_FILES) ) {

    $uploadfile='static/users/id/'.$iduser.'/ava.jpg';
    if (!is_dir('static/users/id/'.$iduser)) {
        mkdir('static/users/id/'.$iduser);
    }
    if ($_FILES['userfile']['error']==0) {
        $tmpfile=$_FILES['userfile']['tmp_name'];
        uploadFoto($uploadfile,$tmpfile,70,70);
    } else {
        $numErr=$_FILES['userfile']['error'];
        $error[]="Ошибка загрузки фото!<br/>№$numErr.".$arrErrors[$numErr-1];
    }
        
}
/////////сохранение ФИО и даты рождения///
if ( isset($_POST['name']) )  {
    $newName = $db->real_escape_string($_POST['name']);
    $newSecondName = $db->real_escape_string($_POST['secondName']);
    $day = $_POST['day'];
    $month = $_POST['month'];
    $year = $_POST['year'];

    $booldate = false;
    if (($day!='') && ($month!='') && ($year!='')) {
        $booldate=checkdate ($month, $day , $year);
    }
    if (!$booldate) {
        $error[]="Не корректная дата рождения!";
    }
    
    if (($newName!='') && ($newSecondName!='') && $booldate) {
        $dateOfBirth=$year.".".$month.".".$day;
        $q = $db->query(" SELECT * FROM `profile` WHERE `id_user` = $iduser");
        $res = $q->fetch_assoc();
        if ($res) {//существует ли profile пользователя

            $strsql = "UPDATE `profile` SET `name`='$newName',`second_name`='$newSecondName', `date_of_birth`='$dateOfBirth' 
            WHERE `id_user`=$iduser";

            $q = $db->query($strsql);

        } else {

            $strsql="INSERT INTO `profile` (`id_user`, `name`, `second_name`, `date_of_birth`) 
                    VALUES ($iduser,'$newName','$newSecondName','$dateOfBirth')";

            $q = $db->query($strsql);

        }
    }
}
/////////////////////////

$q = $db->query(" SELECT * FROM `profile` WHERE `id_user` = $iduser");
$res = $q->fetch_assoc();
$name = $res['name'];
$secondName = $res['second_name'];
$day = $month = $year = '';
if ($res['date_of_birth']) {
    $day = date("d",strtotime($res['date_of_birth']));
    $month = date("m",strtotime($res['date_of_birth']));
    $year = date("Y",strtotime($res['date_of_birth']));
    if (!checkdate ($month, $day , $year)) {
        $day = $month = $year = '';
    }
}
display("settings", compact('title','error','user','iduser','name','secondName','day','month','year'));