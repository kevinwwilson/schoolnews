<?php     
defined('C5_EXECUTE') or die(_("Access Denied."));
Loader::model('form_style','sixeightforms');

$sID = intval($_GET['sID']);

header("Content-Type: text/css");
header("Cache-Control: max-age=86400, must-revalidate"); // HTTP/1.1
header("Pragma: cache_asset");        // HTTP/1.0
if($sID != 0) {
	$style = sixeightformstyle::getByID(intval($_GET['sID']));
	$style->render();
} else {
	Loader::packageElement('default_css','sixeightforms');
}
?>