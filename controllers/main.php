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
if (isset($_SESSION['user']))  {
   $user=$_SESSION['user']; 
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
    display("user", compact('title', 'user','mode'));
}

