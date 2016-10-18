<?php
defined('C5_EXECUTE') or die(_("Access Denied."));
$type = strtolower($_REQUEST['type']);
$name = $_REQUEST['name'];
$loaderHelper = Loader::helper('load_feed');
$json = $loaderHelper->getFeed($type, $name);

header('Content-Type: application/javascript');
echo $_GET['callback']."(";
echo $json;
echo");";
