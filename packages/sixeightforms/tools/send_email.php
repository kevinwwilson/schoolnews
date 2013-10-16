<?php  
defined('C5_EXECUTE') or die(_("Access Denied."));

$ch = Page::getByPath("/dashboard/sixeightforms/forms");
$chp = new Permissions($ch);
if (!$chp->canRead()) {
	die(_("Access Denied."));
}

Loader::model('form','sixeightforms');

echo $_POST['email'];
sixeightForm::sendMail($_POST['email'],'','',$_POST['subject'],$_POST['body']);