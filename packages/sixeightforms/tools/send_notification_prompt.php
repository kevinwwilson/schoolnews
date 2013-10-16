<?php   
defined('C5_EXECUTE') or die(_("Access Denied."));
Loader::library('view');
Loader::model('form','sixeightforms');
Loader::model('field','sixeightforms');
Loader::model('answer_set','sixeightforms');

$ch = Page::getByPath("/dashboard/sixeightforms/forms");
$chp = new Permissions($ch);
if (!$chp->canRead()) {
	die(_("Access Denied."));
}

$ih = Loader::helper('concrete/interface');
$uh = Loader::helper('concrete/urls');
$as = sixeightAnswerSet::getByID(intval($_GET['asID']));
?>
<table cellpadding="8" cellspacing="0" border="0">
	<tr>
		<td>
			<?php  echo t('Email Address(es)'); ?>
			<input type="text" name="to" style="width:150px" id="email-to" />
		</td>
		<td>
			<?php  echo $ih->button_js(t('Send Email'),'sendEmail();'); ?>
		</td>
	</tr>
</table>
<div style="text-align:center;display:none" id="email-sent"><strong><?php  echo t('Email sent'); ?></strong></div>
<script type="text/javascript">
	function sendEmail() {
		$.ajax({
			url:'<?php  echo $uh->getToolsURL("send_notification","sixeightforms"); ?>?asID=<?php  echo intval($_GET['asID']); ?>&email=' + $('#email-to').val(),
			success: function(response) {
				$('#email-to').val('');
				$('#email-sent').fadeIn('slow').delay(1000).fadeOut('slow');
			}
		});
	}
</script>
