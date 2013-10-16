<?php   
defined('C5_EXECUTE') or die(_("Access Denied."));
Loader::library('view');

Loader::model('form','sixeightforms');
Loader::model('field','sixeightforms');
Loader::model('answer_set','sixeightforms');

$uh = Loader::helper('concrete/urls');


$fID = intval($_POST['sem-form-id']);
$form = sixeightForm::getByID($fID);
$fields = $form->getFields();

//Upload file
Loader::library("file/importer");
$fi = new FileImporter();
$resp = $fi->import($_FILES['Filedata']['tmp_name'], $_FILES['Filedata']['name']);
if (!($resp instanceof FileVersion)) {
	echo 'Error uploading';
	switch($resp) {
		case FileImporter::E_FILE_INVALID_EXTENSION:
			$errors['fileupload'] = t('Invalid file extension.');
			break;
		case FileImporter::E_FILE_INVALID:
			$errors['fileupload'] = t('Invalid file.');
			break;
	}
} else {
	echo $resp->getFileID();
}
?>