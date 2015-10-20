<?php 

$title = "<script>alert('admin');</script>Title from compact";
$body = 'etsdt';


display("index", compact('title', 'body'));