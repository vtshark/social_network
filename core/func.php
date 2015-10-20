<?php
//Вспомогательные функции

function errorPage() {
    die("404");
}


//Ищет шаблон отображения и выводит его;
function display($template, $data = array()) {
    $fullPath = TEMPLATE_PATH.$template.TEMPLATE_TYPE;
    
    if( !file_exists($fullPath) ) {
        debug("Can't find template name ".$fullPath);
        return;
    }
    
    extract($data);
    ob_start();
    include $fullPath;
    
    $page = ob_get_clean();

    exit($page);
}

function out($str) {
    return htmlspecialchars($str);
}

function debug($string) {
    if(DEBUG) {
        echo $string;
    }
}