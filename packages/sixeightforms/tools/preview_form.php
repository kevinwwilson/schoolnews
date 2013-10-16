<?php 
/* Adjusted to use new model format on 11-26-10 */

defined('C5_EXECUTE') or die(_("Access Denied."));

$ch = Page::getByPath("/dashboard/sixeightforms/forms");
$chp = new Permissions($ch);
if (!$chp->canRead()) {
	die(_("Access Denied."));
}

Loader::model('form','sixeightforms');
Loader::model('field','sixeightforms');
Loader::model('answer_set','sixeightforms');

$formBT = BlockType::getByHandle('sixeightforms');
$formBT->controller->displayFromDashboard = 1;

if($_GET['asID'] != '') {
	$as = sixeightAnswerSet::getByID(intval($_GET['asID']));
	$formBT->controller->fID = $as->fID;
} else {
	$formBT->controller->fID = intval($_GET['fID']);
}

$formBT->render('view');

?>