<?php

//Подключаем все конфиги и константы
require "core/config.php";

//Подключаемся к базе данных
require "core/db.php";

//Подтягиваем доп функции для дальнейшего использования
require "core/func.php";

//Разбираем URL и подключаем необходимый контроллер (модуль)
session_start();
require "core/route.php";

