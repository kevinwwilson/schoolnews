<?php     
defined('C5_EXECUTE') or die(_("Access Denied."));

Loader::model('form_style','sixeightforms');

/*
header('Expires: 0');
header('Cache-control: private');
header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
header('Content-Description: File Transfer');
header('Content-Type: text/xml');
header('Content-disposition: attachment; filename=export.xml');
*/

$s = sixeightformstyle::getByID(intval($_GET['sID']));
echo $s->getXML();