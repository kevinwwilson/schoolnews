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

$h = Loader::helper('concrete/interface');

$form = sixeightForm::getByID(intval($_GET['fID']));
$notifications = $form->getNotifications();
?>

<script type="text/javascript">
	function removeNotification(notification) {
		if($(notification).parent().parent().attr('id') != 'original-form') {
			$(notification).parent().fadeOut('fast',function() {
				$(notification).parent().remove();
			});
		}
	}
	
	function addNotification() {
		$('#conditional-form-elements').append($('#original-form').html());
	}
	
	function saveConfirmation() {
		$('#confirmation-form').submit();
	}
	
	function saveNotification() {
		$('#notification-form').submit();
	}

	$(document).ready(function() {
		$('.ccm-dialog-tabs li a').click(function(e) {
			e.preventDefault();
			$('.ccm-dialog-tabs li').removeClass('ccm-nav-active');
			$(this).parent().addClass('ccm-nav-active');
			$('.ccm-tab').hide();
			$($(this).attr('href')).show();
		});
	});
</script>

<style type="text/css">
.remove-notification-link {
	cursor:pointer;
	float:right;
	margin:3px;
}
</style>

<ul class="ccm-dialog-tabs">
<li class="ccm-nav-active"><a href="#tab-uc"><?php   echo t('User Confirmation'); ?></a></li>
<li><a href="#tab-cn"><?php   echo t('Conditional Notifications'); ?></a></li>
</ul>
<div style="clear:both"></div>
<div class="ccm-ui">
<form id="confirmation-form" action="<?php   echo View::url('/dashboard/sixeightforms/forms', 'saveConfirmation'); ?>" method="post">
<input type="hidden" name="fID" value="<?php   echo intval($_GET['fID']); ?>" />
	<table cellpadding="8" cellspacing="0" border="0" id="tab-uc" class="ccm-tab" width="100%">
		<tr>
			<td colspan="2">
				<p><?php   echo t('To send a confirmation e-mail to users after they submit a form, select the field that should be used as the email address and enter a message.'); ?></p>
			</td>
		<tr>
			<td>
				<strong><?php   echo t('Send confirmation to'); ?></strong><br />
				<div>
				<select name="ffID">
				<?php  
				$fields = $form->getFields(intval($_GET['fID']));
				foreach($fields as $field) {
					echo '<option value="' . $field->ffID . '"';
					if($form->properties['confirmationField'] == $field->ffID) {
						echo ' selected="selected" ';
					}
					echo '>' . $field->shortLabel . '</option>';
				}
				?>
				</select>
				</div>
				<br />
			</td>
			<td>
				<strong><?php   echo t('Subject'); ?></strong><br />
				<div>
					<input type="text" name="confirmationSubject" value="<?php  echo htmlspecialchars($form->properties['confirmationSubject']); ?>" style="width:200px" maxlength="255" />
				</div>
				<br />
			</td>
		</tr>
		<tr>
			<td>
				<strong><?php  echo t('From Address'); ?></strong><br />
				<div>
					<input type="text" name="confirmationFromAddress" value="<?php  echo htmlspecialchars($form->properties['confirmationFromAddress']); ?>" style="width:200px" maxlength="255" />
				</div>
				<br />
			<td>
				<strong><?php  echo t('From Name'); ?></strong><br />
				<div>
					<input type="text" name="confirmationFrom" value="<?php  echo htmlspecialchars($form->properties['confirmationFrom']); ?>" style="width:200px" maxlength="255" />
				</div>
				<br />
			</td>
		</tr>
		<tr>
			<td colspan="2">
				<strong><?php   echo t('Message'); ?></strong><br />
				<textarea style="width:95%;height:110px;font-size:12px;" name="message"><?php  echo htmlspecialchars($form->properties['confirmationEmail']); ?></textarea>
			</td>
		</tr>
		<tr>
			<td colspan="2">
				<hr />
				<a href="javascript:void(0)" onclick="$.fn.dialog.closeTop();" class="btn ccm-button-left"><?php  echo t('Cancel'); ?></a>
				<a href="javascript:void(0)" onclick="saveConfirmation();" class="btn ccm-button-right primary"><?php  echo t('Save'); ?></a>
			</td>
		</tr>
	</table>
</form>
<form id="notification-form" action="<?php   echo View::url('/dashboard/sixeightforms/forms', 'saveNotifications'); ?>" method="post">
<input type="hidden" name="fID" value="<?php   echo intval($_GET['fID']); ?>" />
	<table cellpadding="8" cellspacing="0" border="0" style="display:none" id="tab-cn" class="ccm-tab">
		<tr>
			<td>
				<div class="ccm-note"><?php   echo t('Use conditional notifications to send e-mail notifications based on values entered by the end-user.'); ?></div>
			</td>
		</tr>
		<tr>
			<td>
				<div id="conditional-form-elements">
					<?php   foreach($notifications as $n) { ?>
					<div style="border:solid 1px #dedede;margin-top:5px;margin-bottom:5px;padding:5px;">
						<img src="<?php   echo ASSETS_URL_IMAGES?>/icons/close.png" class="remove-notification-link" onclick="removeNotification(this);"/>
						If	<select name="ffID[]">
						<?php  
						$fields = $form->getFields();
						foreach($fields as $field) {
							echo '<option value="' . $field->ffID . '"';
							if($n['ffID'] == $field->ffID) {
								echo ' selected="selected" ';
							}
							echo '>' . $field->shortLabel . '</option>';
						}
						?>
						</select>
						<select name="conditionType[]">
							<option value="is equal to" <?php   if($n['conditionType'] == 'is equal to') { echo ' selected="selected" '; } ?>><?php   echo t('is equal to'); ?></option>
							<option value="is not equal to" <?php   if($n['conditionType'] == 'is not equal to') { echo ' selected="selected" '; } ?>><?php   echo t('is not equal to'); ?></option>
							<option value="contains" <?php   if($n['conditionType'] == 'contains') { echo ' selected="selected" '; } ?>>contains</option>
							<option value="does not contain" <?php   if($n['conditionType'] == 'does not contain') { echo ' selected="selected" '; } ?>><?php   echo t('does not contain'); ?></option>
						</select>
						<input type="text" size="25" name="value[]" value="<?php   echo htmlspecialchars($n['value']); ?>" style="margin:2px" /><br />
						<?php   echo t('send an e-mail'); ?> 
						<select name="sendData[]">
							<option value="1" <?php   if($n['sendData'] == '1') { echo ' selected="selected" '; } ?>><?php   echo t('with form data'); ?></option>
							<option value="0" <?php   if($n['sendData'] == '0') { echo ' selected="selected" '; } ?>><?php   echo t('without form data'); ?></option>
						</select>
						 to <input name="email[]" type="text" size="25" value="<?php   echo htmlspecialchars($n['email']); ?>" style="margin:2px"/>
					</div>
					<?php   } ?>
			 	</div>
			</td>
		</tr>
		<tr>
			<td>
				<div>
					<hr />
					<a href="javascript:void(0)" onclick="addNotification();" class="btn ccm-button-left"><?php  echo t('Add Notification'); ?></a>
					<a href="javascript:void(0)" onclick="saveNotification();" class="btn ccm-button-right primary"><?php  echo t('Save Notifications'); ?></a>
				</div>
			</td>
		</tr>
	</table>
</form>
</div>
<div id="original-form" style="display:none">
	<div style="border:solid 1px #dedede;margin-top:5px;margin-bottom:5px;padding:5px;">
		<img src="<?php   echo ASSETS_URL_IMAGES?>/icons/close.png" class="remove-notification-link" onclick="removeNotification(this);"/>
		If	<select name="ffID[]">
		<?php 
		$fields = $form->getFields();
		foreach($fields as $field) {
			echo '<option value="' . $field->ffID . '">' . $field->shortLabel . '</option>';
		}
		?>
		</select>
		<select name="conditionType[]">
			<option value="is equal to"><?php   echo t('is equal to'); ?></option>
			<option value="is not equal to"><?php   echo t('is not equal to'); ?></option>
			<option value="contains"><?php   echo t('contains'); ?></option>
			<option value="does not contain"><?php   echo t('does not contain'); ?></option>
		</select>
		<input type="text" size="25" name="value[]" style="margin:2px" />
		<?php   echo t('send an e-mail'); ?>
		<select name="sendData[]">
			<option value="1"><?php   echo t('with form data'); ?></option>
			<option value="0"><?php   echo t('without form data'); ?></option>
		</select>
		 <?php   echo t('to'); ?> <input name="email[]" type="text" size="25" style="margin:2px"/>
	</div>
</div>