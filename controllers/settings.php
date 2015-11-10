<?php
$arr_user = checklogin($db);
$user = $arr_user['login'];
$iduser = $arr_user['id'];
$title='Настройки аккаунта';
/////обновление аватара////
if (!empty($_FILES)) {
    //var_dump($_FILES);
    $uploadfile='static/users/id/'.$iduser.'/ava.jpg';
    if (!is_dir('static/users/id/'.$iduser)) {
        mkdir('static/users/id/'.$iduser);
    }
    //$uploadfile = $uploaddir . basename($_FILES['userfile']['name']);
    //var_dump($_FILES);
    if (move_uploaded_file($_FILES['userfile']['tmp_name'], $uploadfile)) {
        //echo "Файл корректен и был успешно загружен.\n";
        $size=getimagesize($uploadfile);
        $w=70;
        $h=70;
        $new=imagecreatetruecolor($w,$h);
        $im=imagecreatefromjpeg($uploadfile);
        imagecopyresampled($new,$im,0,0,0,0,$w,$h,$size[0],$size[1]);
        imagejpeg($new,$uploadfile);
    } else {
        //echo "Возможная атака с помощью файловой загрузки!\n";
    }
}
///////////////////////////
$filename='static/users/id/'.$iduser.'/ava.jpg';
if (file_exists($filename)) {
    $ava='<img src="/'.$filename.'">';
} else {
    $ava='';
}

display("settings", compact('title', 'user','iduser','ava'));