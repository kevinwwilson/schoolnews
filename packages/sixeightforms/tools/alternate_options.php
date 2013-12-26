<?php 
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

$ff = sixeightField::getByID(intval($_GET['ffID']));

?>
<div class="ccm-ui">
<form method="post" action="<?php  echo View::url('/dashboard/sixeightforms/forms','saveAlternateOptions'); ?>" id="options-form">
<input type="hidden" name="ffID" value="<?php  echo intval($ff->ffID); ?>" />
<input type="hidden" name="fID" value="<?php  echo intval($ff->fID); ?>" />
<table id="options-container" cellpadding="8" cellspacing="0" border="0">
	<tr>
		<td>
			<?php  echo t('Alternate options allow you to specify different sets of options that can be used in coordination with rules.'); ?>
		</td>
	</tr>
	<?php  if(count($ff->getAlternateOptions()) > 0) { ?>
	<?php  foreach($ff->getAlternateOptions() as $optionGroup) { ?>
	<tr>
		<td>
			<div>
				<input type="hidden" name="ogID[]" value="<?php  echo $optionGroup['ogID']; ?>" />
				<img src="<?php  echo ASSETS_URL_IMAGES?>/icons/close.png" class="remove-option-group-link" onclick="removeOptionGroup(this);" />		
				<div><?php  echo t('Option Group Description'); ?></div>
				<input style="width:300px" type="text" name="name[]" style="margin:2px" value="<?php  echo htmlentities($optionGroup['name']); ?>" />
				<div><?php  echo t('Options'); ?></div>
				<textarea name="options[]" style="width:300px;height:80px;font-size:11px;" ><?php  foreach($optionGroup['options'] as $option) { echo $option . "\n";} ?></textarea>
				<div style="clear:both"></div>
			</div>
		</td>
	</tr>
	<?php  } ?>
	<?php  } ?>
</table>

<a id="add-option-group-button" class="btn" href="javascript:void(0);" onclick="addOptionGroup();">Add Option Group</a>
<input class="btn primary" type="submit" value="Save Alternate Options" />
</form>
</div>

<div style="display:none" id="original-option-group-form">
	<div>
		<img src="<?php  echo ASSETS_URL_IMAGES?>/icons/close.png" class="remove-option-group-link" onclick="removeOptionGroup(this);" />		
		<div><?php  echo t('Option Group Description'); ?></div>
		<input style="width:300px" type="text" name="name[]" style="margin:2px" />
		<div><?php  echo t('Options'); ?></div>
		<textarea name="options[]" style="width:300px;height:80px;font-size:11px;" ></textarea>
		<div style="clear:both"></div>
	</div>
</div>
<style type="text/css">
.remove-option-group-link {
	cursor:pointer;
	float:right;
	margin:3px;
}
</style>
<script type="text/javascript">
	function addOptionGroup() {
		$('#options-container').append('<tr><td>' + $('#original-option-group-form').html() + '</td></tr>');
	}
		
	function removeOptionGroup(element) {
		$(element).parent().parent().remove();
	}
	
	function saveOptions() {
		$('#options-form').submit();
	}
</script>