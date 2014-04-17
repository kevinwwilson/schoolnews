<?php
header('Content-Type: application/javascript');
echo $_GET['callback']."(";
echo file_get_contents('http://' . $_SERVER['HTTP_HOST'] . '/files/widget/news.json');
echo");";
?>