<?php   
defined('C5_EXECUTE') or die(_("Access Denied."));

Loader::model('form','sixeightforms');
Loader::model('field','sixeightforms');

$ch = Page::getByPath("/dashboard/sixeightforms/forms");
$chp = new Permissions($ch);
if (!$chp->canRead()) {
	die(_("Access Denied."));
}

$field = sixeightField::getByID(intval($_GET['ffID']));

$field->updateLabel($_GET['label']);

if($field->type == 'Text (no user input)') {
	$field->updateText($_GET['label']);
}

$newField = sixeightField::getByID(intval($_GET['ffID']));
echo $newField->shortLabel;