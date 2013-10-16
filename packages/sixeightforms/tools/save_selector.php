<?php   
defined('C5_EXECUTE') or die(_("Access Denied."));
Loader::model('form_style','sixeightforms');

$ch = Page::getByPath("/dashboard/sixeightforms/forms");
$chp = new Permissions($ch);
if (!$chp->canRead()) {
	die(_("Access Denied."));
}
sixeightformstyle::setSelectorByID($_POST['ssID'],$_POST['css']);
?>