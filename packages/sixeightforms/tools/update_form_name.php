<?php   
defined('C5_EXECUTE') or die(_("Access Denied."));

Loader::model('form','sixeightforms');

$ch = Page::getByPath("/dashboard/sixeightforms/forms");
$chp = new Permissions($ch);
if (!$chp->canRead()) {
	die(_("Access Denied."));
}

$f = sixeightform::getByID(intval($_GET['fID']));
$f->updateName($_GET['name']);