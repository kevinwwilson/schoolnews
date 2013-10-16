<?php    
defined('C5_EXECUTE') or die(_("Access Denied."));
Loader::model('form','sixeightforms');
$f = sixeightForm::getByID(intval($_GET['fID']));
$fields = $f->getFields();
foreach($fields as $field) {
	$updatedFields[] = array('ffID'=>$field->ffID,'label'=>$field->shortLabel);
}
$fields = json_encode($updatedFields);
echo $fields;
?>
