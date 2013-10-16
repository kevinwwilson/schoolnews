<?php   
defined('C5_EXECUTE') or die(_("Access Denied."));
Loader::model('form','sixeightforms');

$f = sixeightForm::getByID(intval($_GET['fID']));
$h = Loader::helper('concrete/interface');
$uh = Loader::helper('concrete/urls');
$ch = Page::getByPath("/dashboard/sixeightforms/forms");
$chp = new Permissions($ch);
if (!$chp->canRead()) {
	die(_("Access Denied."));
}

Loader::model('search/group');
Loader::library('view');
$gl = new GroupSearch();
$gl->includeAllGroups();
$gl->sortBy('gID','asc');
$gResults = $gl->get();
?>
<script type="text/javascript">
	function savePermissions() {
		$('#permissions-form').submit();
	}
</script>
<div class="ccm-ui">
	<h3><?php  echo t('Form Record Permissions'); ?></h3>
	<form method="post" action="<?php  echo View::url('/dashboard/sixeightforms/forms','savePermissions'); ?>" id="permissions-form">
	<input type="hidden" name="fID" value="<?php  echo intval($_GET['fID']); ?>" />
	<table id="ccmPermissionsTable" width="100%" class="ccm-grid" border="0" cellspacing="0" cellpadding="0">
		<tr>
			<th><div style="width: 200px">&nbsp;</div></th>
			<th style="text-align:center"><?php  echo t('Add')?></th>
			<th style="text-align:center"><?php  echo t('Edit')?></th>
			<th style="text-align:center"><?php  echo t('Delete')?></th>
			<th style="text-align:center"><?php  echo t('Approve')?></th>            	
		</tr>
		<?php  foreach ($gResults as $g) { ?>
			<tr>
				<td><?php  echo $g['gName']; ?></td>
				<td style="text-align:center"><input type="checkbox" name="addRecords[<?php  echo $g['gID']; ?>]" value="1" <?php  if($f->groupCanAddRecords($g['gID'])) { echo 'checked="checked"'; } ?> /></td>
				<td style="text-align:center"><input type="checkbox" name="editRecords[<?php  echo $g['gID']; ?>]" value="1" <?php  if($f->groupCanEditRecords($g['gID'])) { echo 'checked="checked"'; } ?> /></td>
				<td style="text-align:center"><input type="checkbox" name="deleteRecords[<?php  echo $g['gID']; ?>]" value="1" <?php  if($f->groupCanDeleteRecords($g['gID'])) { echo 'checked="checked"'; } ?> /></td>
				<td style="text-align:center"><input type="checkbox" name="approveRecords[<?php  echo $g['gID']; ?>]" value="1" <?php  if($f->groupCanApproveRecords($g['gID'])) { echo 'checked="checked"'; } ?> /></td>
			</tr>
		<?php  } ?>
	</table>
	<br />
	<h3><?php  echo t('Ownership Permissions'); ?></h3>
	<div class="help-block"><?php  echo t('These permissions override permissions specified above.'); ?></div>
	<input type="checkbox" name="ownerCanEdit" value="1" <?php  if($f->ownerCanEdit()) { echo 'checked="checked"'; } ?>  /> <?php  echo t('Users can edit record(s) they own.'); ?><br />
	<input type="checkbox" name="ownerCanDelete" value="1" <?php  if($f->ownerCanDelete()) { echo 'checked="checked"'; } ?> /> <?php  echo t('Users can delete record(s) they own.'); ?><br />
	<input type="checkbox" name="oneRecordPerUser" value="1" <?php  if($f->oneRecordPerUser()) { echo 'checked="checked"'; } ?> /> <?php  echo t('Limit to one record per user.'); ?>
	</form>
	<div class="dialog-buttons">
		<a href="javascript:void(0)" onclick="$.fn.dialog.closeTop();" class="btn ccm-button-left"><?php  echo t('Cancel'); ?></a>
		<a href="javascript:void(0)" onclick="savePermissions();" class="btn ccm-button-right primary"><?php  echo t('Save'); ?></a>
	</div>
</div>