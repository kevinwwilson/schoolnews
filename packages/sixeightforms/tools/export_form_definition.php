<?php     
defined('C5_EXECUTE') or die(_("Access Denied."));

Loader::model('form','sixeightforms');
Loader::model('field','sixeightforms');
Loader::model('answer_set','sixeightforms');
Loader::block('form');

/*
header('Expires: 0');
header('Cache-control: private');
header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
header('Content-Description: File Transfer');
header('Content-Type: text/xml');
header('Content-disposition: attachment; filename=export.xml');
*/

$f = sixeightform::getByID(intval($_GET['fID']));
echo $f->getXML();