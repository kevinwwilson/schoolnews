<?php   
defined('C5_EXECUTE') or die(_("Access Denied."));

Loader::model('form','sixeightforms');
Loader::model('field','sixeightforms');
Loader::model('answer_set','sixeightforms');

$f = sixeightform::getByID(intval($_GET['fID']));
$fields = $f->getFields();
$optionGroups = $f->getAlternateOptions();

$ch = Page::getByPath("/dashboard/sixeightforms/forms");
$chp = new Permissions($ch);
if (!$chp->canRead()) {
	die(_("Access Denied."));
}
?>
<div class="ccm-ui">
<form method="post" action="<?php  echo View::url('/dashboard/sixeightforms/forms','saveRules'); ?>" id="rules-form">
	<input type="hidden" name="fID" value="<?php  echo intval($f->fID); ?>" />
	<table id="rules-container" cellpadding="8" cellspacing="0" border="0">
		<tr>
			<td>
				<?php  echo t('Rules allow you to display certain fields based on values specified in other fields.'); ?>
				<hr />
			</td>
		</tr>
		<?php  if(count($f->getRules()) > 0) { ?>
		<?php  foreach($f->getRules() as $rule) { ?>
		<tr>
			<td>
				<div>
					<img src="<?php   echo ASSETS_URL_IMAGES?>/icons/close.png" class="remove-rule-link" onclick="removeRule(this);" />		
					<?php  echo t('If'); ?> 
					<select name="ffID[]" style="margin:2px">
						<?php  foreach($fields as $field) { ?>
							<option value="<?php  echo $field->ffID; ?>" <?php  if ($rule['ffID'] == $field->ffID) { echo 'selected="selected"'; } ?>><?php  echo htmlentities($field->shortLabel); ?></option>
						<?php  } ?>
					</select>
					<select name="comparison[]">
						<option value="is equal to" <?php  if ($rule['comparison'] == 'is equal to') { echo 'selected="selected"'; } ?>><?php  echo t('is equal to'); ?></option>
						<option value="is not equal to" <?php  if ($rule['comparison'] == 'is not equal to') { echo 'selected="selected"'; } ?>><?php  echo t('is not equal to'); ?></option>
					</select>
					<input type="text" name="value[]" style="margin:2px" value="<?php  echo htmlentities($rule['value']); ?>" /> show 
					<select name="actionField[]" style="margin:2px">
						<?php  if(count($fields) > 0) { ?>
							<option value="0">---<?php  echo t('Fields'); ?>---</option>
							<?php  foreach($fields as $field) { ?>
								<option value="<?php  echo $field->ffID; ?>" <?php  if (($rule['actionField'] == $field->ffID) && ($rule['ogID'] == 0)) { echo 'selected="selected"'; } ?>><?php  echo htmlentities($field->shortLabel); ?></option>
								<?php  } ?>
						<?php  } ?>
						<?php  if(count($optionGroups) > 0) { ?>
							<option value="0">---<?php  echo t('Alternate Options'); ?>---</option>
							<?php  foreach($optionGroups as $og) { ?>
								<option value="<?php  echo $og['ffID'] . ':' . $og['ogID']; ?>"<?php  if (($rule['actionField'] == $og['ffID']) && ($rule['ogID'] == $og['ogID'])) { echo 'selected="selected"'; } ?>><?php  echo htmlentities($og['label']); ?></option>
							<?php  } ?>
						<?php  } ?>
					</select>
					<hr />
					<div style="clear:both"></div>
				</div>
			</td>
		</tr>
		<?php  } ?>
		<?php  } ?>
	</table>
	
	<div class="dialog-buttons">
		<a href="javascript:void(0)" onclick="addRule();" id="add-rule-button" class="btn ccm-button-left"><?php  echo t('Add Rule'); ?></a>
		<a href="javascript:void(0)" onclick="saveRules();" class="btn ccm-button-right primary">Save Rules</a>
	</div>
</form>
</div>

<div style="display:none" id="original-rule-form">
	<div>
		<img src="<?php   echo ASSETS_URL_IMAGES?>/icons/close.png" class="remove-rule-link" onclick="removeRule(this);" />		
		<?php  echo t('If'); ?> 
		<select name="ffID[]" style="margin:2px">
			<?php  foreach($fields as $field) { ?>
				<option value="<?php  echo $field->ffID; ?>"><?php  echo htmlentities($field->shortLabel); ?></option>
			<?php  } ?>
		</select>
		<select name="comparison[]">
			<option value="is equal to"><?php  echo t('is equal to'); ?></option>
			<option value="is not equal to"><?php  echo t('is not equal to'); ?></option>
		</select>
		<input type="text" name="value[]" style="margin:2px" /> show 
		<select name="actionField[]" style="margin:2px">
			<?php  if(count($fields) > 0) { ?>
				<option value="0">---<?php  echo t('Fields'); ?>---</option>
				<?php  foreach($fields as $field) { ?>
					<option value="<?php  echo $field->ffID; ?>"><?php  echo htmlentities($field->shortLabel); ?></option>
				<?php  } ?>
			<?php  } ?>
			<?php  if(count($optionGroups) > 0) { ?>
				<option value="0">---<?php  echo t('Alternate Options'); ?>---</option>
				<?php  foreach($optionGroups as $og) { ?>
					<option value="<?php  echo $og['ffID'] . ':' . $og['ogID']; ?>"><?php  echo htmlentities($og['label']); ?></option>
				<?php  } ?>
			<?php  } ?>
		</select>
		<hr />
		<div style="clear:both"></div>
	</div>
</div>
<style type="text/css">
.remove-rule-link {
	cursor:pointer;
	float:right;
	margin:3px;
}
</style>
<script type="text/javascript">
	function addRule() {
		$('#rules-container').append('<tr><td>' + $('#original-rule-form').html() + '</td></tr>');
	}
		
	function removeRule(element) {
		$(element).parent().parent().remove();
	}
	
	function saveRules() {
		$('#rules-form').submit();
	}
</script>