<?php 
/* Adjusted to use new model format on 11-26-10 */

defined('C5_EXECUTE') or die(_("Access Denied."));

$ch = Page::getByPath("/dashboard/sixeightforms/forms");
$chp = new Permissions($ch);
if (!$chp->canRead()) {
	die(_("Access Denied."));
}

Loader::library('view');
Loader::model('form','sixeightforms');
Loader::model('field','sixeightforms');
Loader::model('answer_set','sixeightforms');

$ch = Page::getByPath("/dashboard/sixeightforms/forms");
$chp = new Permissions($ch);
if (!$chp->canRead()) {
	die(_("Access Denied."));
}
?>
<div>
	If
	<select name="ffID[]">
	<?php  
	$form = sixeightForm::getByID(intval($_GET['fID']);
	$fields = $form->getFields();
	foreach($fields as $field) {
		echo '<option value="' . $field->ffID . '">' . $field->label . '</option>';
	}
	?>
	</select>
	<select name="condition[]">
		<option value="is equal to"><?php   echo t('is equal to'); ?></option>
		<option value="is not equal to"><?php   echo t('is not equal to'); ?></option>
		<option value="contains"><?php   echo t('contains'); ?></option>
		<option value="does not contain"><?php   echo t('does not contain'); ?></option>
	</select>
	<input type="text" size="25" name="value[]" />
</div>
<div style="border-bottom:dotted 1px #ccc">
	<?php   echo t('send an e-mail'); ?> 
	<select name="data[]">
		<option value="1"><?php   echo t('with form data'); ?></option>
		<option value="0"><?php   echo t('without form data'); ?></option>
	</select>
	 <?php   echo t('to'); ?> <input name="email[]" type="text" size="25" />
 </div>