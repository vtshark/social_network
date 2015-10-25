<?php
unset($_SESSION['user']);
unset($_SESSION['iduser']);
//$_SESSION['user']='';

$title='';
display("exit",compact('title'));