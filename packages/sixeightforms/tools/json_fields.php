<?php  
defined('C5_EXECUTE') or die(_("Access Denied."));

$ch = Page::getByPath("/dashboard/sixeightforms/forms");
$chp = new Permissions($ch);
if (!$chp->canRead()) {
	die(_("Access Denied."));
}

Loader::model('form','sixeightforms');
Loader::model('field','sixeightforms');

$form = sixeightForm::getByID(intval($_GET['fID']));
$fields = $form->getFields();
$ffData = array();
$i = 0;
foreach($fields as $ff) {
	$ffData[$i]['ffID'] = $ff->ffID;
	$ffData[$i]['shortLabel'] = $ff->shortLabel;
	$i++;
}
echo json_encode($ffData);