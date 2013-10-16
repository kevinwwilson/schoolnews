<?php     
defined('C5_EXECUTE') or die(_("Access Denied."));
$form = Loader::helper('form');
?>

<?php 
$forms = sixeightForm::getAll();		
if(count($forms) == 0) {
	$isReady = false;
} else {
	$isReady = true;
}
if($isReady) {
	$forms = sixeightForm::getAll();
	$styles = sixeightformstyle::getAll();
?>

<table>
	<tr>
		<td>Form</td>
		<td>
			<select name="fID">
			<?php  foreach($forms as $f) {
				echo '<option value="' . $f->fID . '"';
				if($f->fID == $fID) {
					echo ' selected="selected" ';
				}
				echo '>' . $f->properties['name'] . '</option>';
			} ?>
			</select>
		</td>
	</tr>
	<tr>
		<td>Style</td>
		<td>
			<select name="sID">
				<option value="0">None</option>
				<?php   foreach($styles as $style) { ?>
					<option value="<?php  echo $style->sID; ?>" <?php   if($style->sID == $sID) { echo 'selected="selected"'; } ?>><?php  echo $style->name; ?></option>
				<?php   } ?>
			</select>
		</td>
	</tr>
	<tr>
		<td><?php   echo t('Require SSL?'); ?></td>
		<td>
			<select name="requireSSL">
				<option value="0">---</option>
				<option value="1" <?php  if($requireSSL == 1) { echo 'selected="selected"'; } ?>><?php   echo t('Yes'); ?></option>
				<option value="0" <?php  if($requireSSL == 0) { echo 'selected="selected"'; } ?>><?php   echo t('No'); ?></option>				
			</select>
		</td>
	</tr>
</table>

<?php   } else { ?>
<h2><?php   echo t('Block Not Ready'); ?></h2>
<p><?php   echo t('In order to use the Advanced Forms block, you must first create at least one form, on '); ?><a href="<?php   echo View::url('/dashboard/sixeightforms/') ?>"><?php   echo t('Advanced Forms'); ?></a> <?php   echo t(' pages in the Dashboard.'); ?></p>
<?php   } ?>