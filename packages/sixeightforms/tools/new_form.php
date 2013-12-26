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

$ih = Loader::helper('concrete/interface');
$uh = Loader::helper('concrete/urls');
$formsURL=View::url('/dashboard/sixeightforms/forms', 'createForm');

?>

<script type="text/javascript">
function createForm() {
	$('#newFormForm').submit();
}

$(document).ready(function() {
	$('.form-wizard-type').click(function() {
		var formType = $(this).attr('id');
		$('#form-wizard-start').fadeOut('fast', function() {
			$('#' + formType + '-continue').show();
			$('#form-wizard-form-name').fadeIn('fast',function() {
				$('#form-wizard-form-name-input').focus();
			});
		});
	});
});

function loadBasicPage1() {
	$('#form-wizard-mailTo-2').remove();
	if($('#form-wizard-form-name-input').val() == '') {
		$('#form-wizard-form-name-input').css('border','solid 1px #ff0000').focus();
	} else { 
		$('#form-wizard-form-name').fadeOut('fast', function() {
			$('#basic-form-1').fadeIn('fast');
		});
	}
}

function loadAdvancedPage1() {
	$('#form-wizard-mailTo-1').remove();
	$('#sendToHidden, #mailSubjectHidden, #thankyouMsgHidden, #thankyouMsgDD').remove();
	if($('#form-wizard-form-name-input').val() == '') {
		$('#form-wizard-form-name-input').css('border','solid 1px #ff0000').focus();
	} else { 
		$('#form-wizard-form-name').fadeOut('fast', function() {
			$('#advanced-form-1').fadeIn('fast');
		});
	}
}

function loadAdvancedPage2() {
	$('#advanced-form-1').fadeOut('fast', function() {
		$('#advanced-form-2').fadeIn('fast');
	});
}

function loadAdvancedPage3() {
	$('#advanced-form-1').fadeOut('fast', function() {
		$('#advanced-form-2').fadeOut('fast', function() {
			$('#advanced-form-3').fadeIn('fast');
		});
	});
}

function loadAdvancedPage4() {
	$('#advanced-form-3').fadeOut('fast', function() {
		$('#advanced-form-4').fadeIn('fast');
	});
}

function loadAdvancedPage5() {
	$('#advanced-form-3').fadeOut('fast', function() {
		$('#advanced-form-5').fadeIn('fast');
	});
}

function loadAdvancedPage6() {
	$('#advanced-form-4').fadeOut('fast', function() {
		$('#advanced-form-5').fadeOut('fast', function() {
			$('#advanced-form-6').fadeIn('fast');
		});
	});
}

function loadDataDisplayPage1() {
	if($('#form-wizard-form-name-input').val() == '') {
		$('#form-wizard-form-name-input').css('border','solid 1px #ff0000').focus();
	} else { 
		$('#form-wizard-form-name').fadeOut('fast', function() {
			$('#thankyouMsgHidden').remove();
			$('#thankyouMsgAdvanced').remove()
			$('#datadisplay-form-1').fadeIn('fast');
			$('#sendMailHidden').val('0');
			$('#sendDataHidden').val('0');
			$('#mailFromHidden').val('');
			$('#mailSubjectHidden').val('');
			$('#afterSubmitHidden').val('thankyou');
			$('#submitLabelHidden').val('Submit');
			$('#requiredIndicatorHidden').val('');
			$('#requiredColorHidden').val('');
			$('#captchaHidden').val('');
		});
	}
}

</script>

<style type="text/css">
div.form-wizard-type-container {
	border-bottom:solid 1px #dedede;
	position:relative;
}

div.form-wizard-type {
	margin-top:2px;
	margin-bottom:2px;
	font-size:14px;
	padding:6px;
	border:solid 1px #fafafa;
	vertical-align:middle;
	color:#666666;
}

div.form-wizard-type:hover {
	background-color:#d9e7ff;
	border:solid 1px #94a7c7;
	cursor:pointer;
}
</style>
<div class="ccm-ui">
<form id="newFormForm" action="<?php  echo $formsURL; ?>" method="POST">
	<div class="form-wizard-page" id="form-wizard-form-name" style="text-align:center">
		<h2><?php  echo t('Form Name'); ?></h2>
		<input id="form-wizard-form-name-input" type="text" name="name" style="font-size:18px;border:solid 1px #ccc;margin:0 10px 10px 10px;padding:5px;text-align:center;" />
		<div id="form-wizard-type-basic-continue" style="margin:0 auto;width:85px;margin-top:15px"><?php  echo $ih->button_js( t('Continue'), "loadBasicPage1();",'','primary'); ?></div>
	</div>
	<div class="form-wizard-page" id="basic-form-1" style="display:none;text-align:center">
		<h2><?php  echo t('Where should form data be sent?'); ?></h2>
		<div class="help-block"><?php  echo t('Separate multiple addresses with a comma'); ?></div>
		<?php 
		$u = new User;
		$ui = UserInfo::getByID($u->getUserID());
		$toEmail = $ui->getUserEmail();
		?>
		<input id="form-wizard-mailTo-1" type="text" name="mailTo" style="font-size:18px;border:solid 1px #ccc;margin:0 10px 10px 10px;padding:5px;text-align:center;" value="<?php  echo $toEmail; ?>" /><br />
		<input type="checkbox" name="doNotSend" id="doNotSend" value="1" /> <?php  echo t('Do not send email notifications'); ?>
		<div style="margin:0 auto;width:85px;margin-top:15px"><?php  echo $ih->button_js( t('Create Form!'), "createForm()",'','primary'); ?></div>
		<script type="text/javascript">
			$(document).ready(function() {
				$('#doNotSend').click(function() {
					if($('#doNotSend').is(':checked')) {
						$('#form-wizard-mailTo-1').val('').attr('disabled','disabled');
					} else {
						$('#form-wizard-mailTo-1').removeAttr('disabled');
					}
				});
			});
		</script>
	</div>
	<div class="form-wizard-page" id="advanced-form-1" style="display:none;text-align:center">
		<h1><?php  echo t('Should this form send email notifications?'); ?></h1>
		<div class="ccm-note"><?php  echo t('Data will be stored no matter which option you choose'); ?></div>
		<div style="margin:0 auto;width:270px;margin-top:15px">
			<?php  echo $ih->button_js( t('Yes, send e-mail'), "loadAdvancedPage2();", 'left'); ?>
			<?php  echo $ih->button_js( t('No, just save data'), "loadAdvancedPage3();", 'right'); ?>
		</div>
		<div style="clear:both"></div>
	</div>
	<div class="form-wizard-page" id="advanced-form-2" style="display:none;text-align:center">
		<h1><?php  echo t('Send form data to:'); ?></h1>
		<?php 
		$u = new User;
		$ui = UserInfo::getByID($u->getUserID());
		$toEmail = $ui->getUserEmail();
		?>
		<input id="form-wizard-mailTo-2" type="text" size="40" name="mailTo" style="font-size:15px;border:solid 1px #ccc;margin:0 10px 10px 10px;padding:5px;text-align:center;" value="<?php  echo $toEmail; ?>" />
		<div class="ccm-note"><?php  echo t('Separate multiple addresses with a comma'); ?></div>
		<h1><?php  echo t('Email Subject'); ?></h1>
		<input type="text" size="40" name="mailSubject" style="font-size:15px;border:solid 1px #ccc;margin:0 10px 10px 10px;padding:5px;text-align:center;" value="<?php  echo SITE; ?> Form Submission" onfocus="this.value = '';this.onfocus=null;" onblur="if(this.value == ''){this.value='<?php  echo SITE; ?> <?php  echo t('Form Submission'); ?>';}" />
		<div style="margin:0 auto;width:85px;margin-top:15px"><?php  echo $ih->button_js( t('Continue'), "loadAdvancedPage3()"); ?></div>
	</div>
	<div class="form-wizard-page" id="advanced-form-3" style="display:none;text-align:center">
		<h1><?php  echo t('After the form is submitted:'); ?></h1>
		<div style="margin:0 auto;width:270px;margin-top:15px">
			<?php  echo $ih->button_js( t('Display a message'), "loadAdvancedPage4();", 'left'); ?>
			<?php  echo $ih->button_js( t('Redirect to a page'), "loadAdvancedPage5();", 'right'); ?>
		</div>
	</div>
	<div class="form-wizard-page" id="advanced-form-4" style="display:none;text-align:center">
		<h1><?php  echo t('Thank You Message'); ?></h1>
		<textarea id="thankyouMsgAdvanced" name="thankyouMsg" style="width:360px;height:140px;"></textarea>
		<div style="margin:0 auto;width:130px;margin-top:15px"><?php  echo $ih->button_js( t('Continue (last step)'), "loadAdvancedPage6()"); ?></div>
	</div>
	<div class="form-wizard-page" id="advanced-form-5" style="display:none;text-align:center">
		<h1><?php  echo t('Redirect to:'); ?></h1>
		<div style="margin:0 auto;width:270px;margin-top:15px">
			<?php  echo $ih->button_js( t('A page within this site'), "$('#afterSubmitHidden').val('redirect');$('#thankyouURLContainer').fadeOut('fast',function() {\$('#thankyouCIDContainer, #advancedLastStepButton').fadeIn();});", 'left'); ?>
			<?php  echo $ih->button_js( t('An external URL'), "$('#afterSubmitHidden').val('url');$('#thankyouCIDContainer').fadeOut('fast',function() {\$('#thankyouURLContainer, #advancedLastStepButton').fadeIn();});", 'right'); ?>
		</div>
		<div style="clear:both;height:15px"></div>
		<div id="thankyouCIDContainer" style="display:none">
			<?php 
				$pageSelector = Loader::helper('form/page_selector');
				print $pageSelector->selectPage('thankyouCID');
			?>
		</div>
		<div id="thankyouURLContainer" style="display:none">
			<input id="form-wizard-from-name-input" type="text" size="40" name="thankyouURL" style="font-size:15px;border:solid 1px #ccc;margin:0 10px 10px 10px;padding:5px;text-align:center;" value="http://" />
		</div>
		<div style="clear:both"></div>
		<div id="advancedLastStepButton" style="margin:0 auto;width:130px;margin-top:15px;display:none"><?php  echo $ih->button_js( t('Continue (last step)'), "loadAdvancedPage6()"); ?></div>
	</div>
	<div class="form-wizard-page" id="advanced-form-6" style="display:none;text-align:center">
		<h1><?php  echo t('Additional Options'); ?></h1>
		<table border="0" width="100%">
			<tr>
				<td width="50%" valign="top">
					<h2><?php  echo t('Use'); ?> CAPTCHA?</h2>
					<input type="radio" name="captcha" /> <b><?php  echo t('Yes'); ?></b> <input type="radio" name="captcha" /> <b><?php  echo t('No'); ?></b><br /><br />
					<h2><?php  echo t('Submit button text'); ?></h2>
					<input type="text" name="submitLabel" value="Submit" />
				</td>
				<td width="50%" valign="top">
					<h2><?php  echo t('Required field indicator'); ?></h2>
					<input type="text" value="*" /><br /><br />
					<h2><?php  echo t('Required field color'); ?> (Hex RGB)</h2>
					# <input type="text" value="ff0000" />
				</td>
			</tr>
		</table>
		<div style="margin:0 auto;width:85px;margin-top:15px"><?php  echo $ih->button_js( t('Create Form!'), "createForm()"); ?></div>
	</div>
	<div class="form-wizard-page" id="datadisplay-form-1" style="display:none;text-align:center">
		<h1><?php  echo t('Message after adding record:'); ?></h1>
		<textarea id="thankyouMsgDD" name="thankyouMsg" style="width:360px;height:140px;"><?php  echo t('The record has been added.'); ?></textarea>
		<div style="margin:0 auto;width:85px;margin-top:15px"><?php  echo $ih->button_js( t('Create Form!'), "createForm()"); ?></div>
	</div>
	<div class="form-wizard-hidden-fields" id="form-wizard-hidden-fields-basic" style="display:none">
		<input type="hidden" id="sendMailHidden" name="sendMail" value="1" />
		<input type="hidden" id="sendDataHidden" name="sendData" value="1" />
		<input type="hidden" id="mailFromHidden" name="mailFrom" value="<?php  echo SITE; ?>" />
		<input type="hidden" id="mailSubjectHidden" name="mailSubject" value="<?php  echo SITE; ?> <?php  echo t('Form Submission'); ?>" />
		<input type="hidden" id="afterSubmitHidden" name="afterSubmit" value="thankyou" />
		<input type="hidden" id="thankyouMsgHidden" name="thankyouMsg" value="<?php  echo t('Thank you for contacting us.'); ?>" />
		<input type="hidden" id="submitLabelHidden" name="submitLabel" value="Submit" />
		<input type="hidden" id="requiredIndicatorHidden" name="requiredIndicator" value="*" />
		<input type="hidden" id="requiredColorHidden" name="requiredColor" value="ff0000" />
		<input type="hidden" id="captchaHidden" name="captcha" value="0" />
	</div>
</form>
</div>