<?php  
defined('C5_EXECUTE') or die(_("Access Denied."));

$ch = Page::getByPath("/dashboard/sixeightforms/forms");
$chp = new Permissions($ch);
if (!$chp->canRead()) {
	die(_("Access Denied."));
}

Loader::model('form','sixeightforms');
Loader::model('field','sixeightforms');
Loader::model('answer_set','sixeightforms');

$as = sixeightAnswerSet::getByID(intval($_GET['asID']));
$nextAnswerSet = $as->getPrevious();
echo intval($nextAnswerSet->asID);