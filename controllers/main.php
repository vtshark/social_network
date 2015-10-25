<?php 

/*
Главная страница сайта и пример контроллера
Тут может быть любая логика + работа с базой.
В конце отдаем скрипту на вывод шаблон + пробрасываем внутрь него необходимые переменные.
Функция compact собирает переменные в массив. Противоположная функции extract
*/


//$q = mysql_query("SELECT");
//$q = $db->query("SELECT `id`, `online` FROM `users` WHERE `login` = 'test' AND `passwd` = 'testpasswd'");
//mysql_fetch_assoc($q);
//array("login" => "test", "passwd" => "test", ...);

//while($res = $q->fetch_assoc()) {
    //debug($res);
//}

//if(!$res) {
    //echo 'В базе нету пользователей';
//}

$user='';
$iduser='';
if (isset($_SESSION['user']))  {
   $user=$_SESSION['user'];
   $iduser=$_SESSION['iduser'];
}
//debug($user);
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
                //debug($res);
                $i++;
                $arr_out[$i]['text']=$res['text'];
                $arr_out[$i]['data']=$res['data'];
                $arr_out[$i]['autor']=$res['login'];
                
            }
                //debug($arr_out);
            //foreach ($res as $key => $val) {
                //$arr_out[]=$val[$key];
                //debug($val);
            //}
            break;
        default:    
    }
        
    display("user", compact('title', 'user','mode','arr_out'));
}