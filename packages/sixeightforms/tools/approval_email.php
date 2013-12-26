<?php  
defined('C5_EXECUTE') or die(_("Access Denied."));

$ch = Page::getByPath("/dashboard/sixeightforms/forms");
$chp = new Permissions($ch);
if (!$chp->canRead()) {
	die(_("Access Denied."));
}

Loader::model('form','sixeightforms');
Loader::model('field','sixeightforms');
Loader::model('answer_set','sixeightforms');
$uh = Loader::helper('concrete/urls');

$as = sixeightAnswerSet::getByID(intval($_GET['asID']));
if($asUI = UserInfo::getByID($as->creator)) {
	$asEmail = $asUI->getUserEmail();
}

if($_GET['status'] == 0) {
	$status = t('pending');
} elseif($_GET['status'] == 1) {
	$status = t('approved');
} else {
	$status = t('rejected');
}

$f = sixeightform::getByID($as->fID);
$fields = $f->getFields();
$emailData = '';
foreach($fields as $field) {
	if(!$field->excludeFromEmail) {
		if($field->type == 'Credit Card') {
			$emailData .= $field->label . "\nXXXX\n\n";
		} elseif(($field->type == 'File Upload') || ($field->type == 'File from File Manager')) {
			$file = File::getByID($as->answers[$field->ffID]['value']);
			$fv = $file->getApprovedVersion();
			$emailData .= strip_tags($field->label) . "\n";
			$emailData .= BASE_URL . $fv->getRelativePath() . "\n\n";
		} else {
			$emailData .= strip_tags($field->label) . "\n";
			$emailData .= strip_tags($as->answers[$field->ffID]['value']) . "\n\n";
		}
	}
}
?>
<div class="ccm-ui">
<form id="email-form" method="post" action="<?php  echo $uh->getToolsURL('send_email'); ?>">
Send to: <br />
<input name="email" style="width:350px" type="text" value="<?php  echo $asEmail; ?>" /><br /><br />
Subject: <br />
<input name="subject" style="width:350px" type="text" value="<?php  echo $f->properties['name']; ?> Approval Notification" /><br /><br />
<textarea name="body" style="width:380px;height:180px">
The request you submitted at <?php  echo $f->properties['name']; ?> on <?php  echo date('F j, Y',$as->dateSubmitted); ?> at <?php  echo date('g:i a',$as->dateSubmitted); ?> is now set to <?php  echo $status; ?>.

Thanks,
<?php  echo SITE; ?>
 
----------

RECORD DETAILS
	
<?php  echo $emailData; ?>
	</textarea><br /><br />
	<input id="send-button" type="submit" class="btn success" value="Send" />
	<div style="float:right">
	<a href="javascript:window.location.reload(true);" class="btn danger">Don't Send</a>
	</div>
	</form>
</div>

<script type="text/javascript">
$('#email-form').submit(function() {
	var data = $(this).serialize();
	$.ajax({
		url:'<?php  echo $uh->getToolsURL("send_email","sixeightforms"); ?>',
		data:data,
		type:'POST',
		success: function(r) {
			$('#send-button').val('Sending...').animate({
				opacity:1
			},1500,'',function() {
				$(this).val('Sent!');
				window.location.reload(true);
			});
		}
	});
	return false;
});
</script>