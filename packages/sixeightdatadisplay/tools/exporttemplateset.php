<?php     
defined('C5_EXECUTE') or die(_("Access Denied."));
Loader::model('sixeightdatadisplay','sixeightdatadisplay');
/*
header('Expires: 0');
header('Cache-control: private');
header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
header('Content-Description: File Transfer');
header('Content-Type: text/xml');
header('Content-disposition: attachment; filename=export.xml');
*/

$tXML = sixeightdatadisplay::getXML($_GET['tID']);
echo $tXML;