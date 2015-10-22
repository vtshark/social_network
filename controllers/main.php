<?php 

/*
Главная страница сайта и пример контроллера
Тут может быть любая логика + работа с базой.
В конце отдаем скрипту на вывод шаблон + пробрасываем внутрь него необходимые переменные.
Функция compact собирает переменные в массив. Противоположная функции extract
*/

$title = "Главная страница";

//$q = mysql_query("SELECT");
$q = $db->query("SELECT `id`, `online` FROM `users` WHERE `login` = 'test' AND `passwd` = 'testpasswd'");
//mysql_fetch_assoc($q);
//array("login" => "test", "passwd" => "test", ...);

while($res = $q->fetch_assoc()) {
    debug($res);
}

if(!$res) {
    echo 'В базе нету пользователей';
}


display("index", compact('title', 'body'));
