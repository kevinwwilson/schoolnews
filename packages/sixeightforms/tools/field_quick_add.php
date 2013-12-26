<?php  

/* Adjusted to use new model format on 11-26-10 */

defined('C5_EXECUTE') or die(_("Access Denied."));

$ch = Page::getByPath("/dashboard/sixeightforms/forms");
$chp = new Permissions($ch);
if (!$chp->canRead()) {
	die(_("Access Denied."));
}

Loader::model('form','sixeightforms');
$data = array();
$data['fID'] = $_GET['fID'];
$data['label'] = 'New ' . $_GET['type'] . ' Field';
$data['type'] = $_GET['type'];
$f = sixeightForm::getByID(intval($_GET['fID']));
$f->addField($data);