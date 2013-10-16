<?php   
defined('C5_EXECUTE') or die(_("Access Denied."));
Loader::library('view');
Loader::model('form','sixeightforms');
Loader::model('field','sixeightforms');
Loader::model('answer_set','sixeightforms');

$ch = Page::getByPath("/dashboard/sixeightforms/forms");
$chp = new Permissions($ch);
if (!$chp->canRead()) {
	die(_("Access Denied."));
}

$as = sixeightAnswerSet::getByID(intval($_GET['asID']));
$as->sendNotification($_GET['email']);
?>