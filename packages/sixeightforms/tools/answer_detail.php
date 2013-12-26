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

$ih = Loader::helper('concrete/interface');
$uh = Loader::helper('concrete/urls');

$as = sixeightAnswerSet::getByID(intval($_GET['asID']));
?>
<script type="text/javascript">
	function sendEmail() {
		if($('#email-to').val() != '') {
			$.ajax({
				url:'<?php  echo $uh->getToolsURL("send_notification","sixeightforms"); ?>?asID=<?php  echo intval($_GET['asID']); ?>&email=' + $('#email-to').val(),
				success: function(response) {
					$('#email-to').val('');
					$('#email-sent').fadeIn('slow').delay(1000).fadeOut('slow');
				}
			});
		} else {
			alert('Please specify an email address.');
		}
	}
	
	function saveAssociatedPage() {
		var asID = <?php  echo $as->asID; ?>;
		var cID = $('#ascid-container').find('[name=asCID]').val();
		$.ajax({
			type:'GET',
			url: '<?php  echo $uh->getToolsURL('save_associated_page','sixeightforms'); ?>?asID=' + asID + '&cID=' + cID,
			success: function(response) {
				alert('<?php  echo t('Page Saved'); ?>');
			}
		});
	}
	
	var currentOwnerASID = 0;
	
	$('.owner-link').click(function(e) {
		e.preventDefault();
		var href = $(this).attr('href');
		$.fn.dialog.open({
			width: 900,
			height:500,
			modal: false,
			href: href,
			title: '<?php  echo t('Select Owner'); ?>'			
		});
	});
	
	function ccm_triggerSelectUser(uID, uName) {
		$.ajax({
			url:'<?php  echo $uh->getToolsURL("set_answer_set_owner","sixeightforms"); ?>?asID=' + currentOwnerASID + '&uID=' + uID,
			success:function(data) {
				$('#owner-link-' + currentOwnerASID).html(uName);
			}
		});
	}
	
	function getPrevious() {
		$.ajax({
			url:'<?php  echo $uh->getToolsURL('get_previous_answer_set','sixeightforms'); ?>?asID=' + $('#asID').val(),
			success:function(asID) {
				if(asID != 0) {
					$.fn.dialog.closeTop();
					$.fn.dialog.open({
						width: 500,
						height: 390,
						modal: false,
						href: '<?php  echo $uh->getToolsURL('answer_detail','sixeightforms') . '?asID='; ?>' + asID,
						title: '<?php  echo t('Answer Details'); ?>'			
					});
				} else {
					alert('<?php  echo t('No previous records.'); ?>');
				}
			}
		});
	}
	
	function getNext() {
		$.ajax({
			url:'<?php  echo $uh->getToolsURL('get_next_answer_set','sixeightforms'); ?>?asID=' + $('#asID').val(),
			success:function(asID) {
				if(asID != 0) {
					$.fn.dialog.closeTop();
					$.fn.dialog.open({
						width: 500,
						height: 390,
						modal: false,
						href: '<?php  echo $uh->getToolsURL('answer_detail','sixeightforms') . '?asID='; ?>' + asID,
						title: '<?php  echo t('Answer Details'); ?>'			
					});
				} else {
					alert ('<?php  echo t('No more records.'); ?>');
				}
			}
		});
	}
</script>
<div class="ccm-ui">
	<table border="0" width="100%">
		<tr>
			<td width="50%">
				<h4><?php  echo t('Date Submitted'); ?></h4>
				<?php  echo date('F j, Y, g:i a',$as->dateSubmitted); ?>
			</td>
			<td width="50%">
				<h4><?php  echo t('IP Address'); ?></h4>
				<?php  echo $as->ipAddress; ?>
			</td>
		</tr>
	    <?php  if ($as->amountCharged > 0) { ?>
	    <tr>
	    	<td>
	        	<h4><?php  echo t('Amount Charged'); ?></h4>
	            $<?php  echo $as->amountCharged; ?>
	        </td>
	        <td>
	        	<h4><?php  echo t('Amount Paid'); ?></h4>
	            $<?php  echo $as->amountPaid; ?>
	        </td>
	    </tr>
	    <tr>
	    	<td>
	    		<h4><?php  echo t('Gateway Response'); ?></h4>
	    		<?php  echo $as->gatewayResponse; ?>
	    	</td>
	    	<td>&nbsp;</td>
	    </tr>
	    <?php  } ?>
	    <tr>
	    	<td>
	    		<h4><?php  echo t('Expiration'); ?></h4>
	    		<?php  if(intval($as->expiration) > 0) { ?>
		    		<?php  echo date('F j, Y, g:i a',$as->expiration); ?>
	    		<?php  } ?>
	    	</td>
	    	<td>
	    		<h4><?php  echo t('Owner'); ?></h4>
	    		<a dialog-modal="false" onclick="currentOwnerASID=<?php  echo $as->asID; ?>;" id="owner-link-<?php  echo $as->asID; ?>" class="owner-link" href="<?php  echo $uh->getToolsURL('users/search_dialog?mode=choose_one'); ?>">
	    		<?php  
				$uName = $as->getOwnerUserName();
				if($uName) {
					echo $uName;
				} else {
					echo '---';
				}
				?>
				</a>
	    	</td>
	    </tr>
	    <tr>
	    	<td colspan="2">
	    		<h4><?php  echo t('Associated Page'); ?></h4>
	    		<div id="ascid-container">
	    		<?php  
					$ps = Loader::helper('form/page_selector');
					print $ps->selectPage('asCID',$as->cID);
	    		?>
	    		</div>
	    		<?php  echo $ih->button_js('Save Associated Page','saveAssociatedPage();','left','primary'); ?>
	    	</td>
	    </tr>
		<tr>
			<td colspan="2">
			<?php  
			foreach($as->answers as $a) { 
				$field = sixeightField::getByID($a['ffID']);
			?>
				<h4><?php  echo $field->label; ?></h4>
				<?php  
				if(($field->type == 'File from File Manager') || ($field->type == 'File Upload')) {
					$file=File::getByID($a['value']);
					if(($file) && (is_numeric($a['value']))) {
						$fv=$file->getApprovedVersion();
						echo $fv->getFileName();
					}
				} else {
					echo $a['value'];
				} 
				?>
				<br /><br />
			<?php  } ?>
			</td>
		</tr>
		<tr>
			<td colspan="2">
				<h4><?php  echo t('Send data to'); ?></h4>
				<div class="help-block">Separate multiple email address with a comma</div>
				<input id="email-to" />
				<?php  echo $ih->button_js('Send','sendEmail()','left','primary'); ?>
				<span style="display:none" id="email-sent"><?php  echo $ih->button_js('Sent!','void(0);','left','success'); ?></span>
			</td>
		</tr>
	</table>
	
	<div class="dialog-buttons">
		<input type="hidden" id="asID" value="<?php  echo intval($_GET['asID']); ?>" />
		<a href="javascript:void(0)" onclick="getPrevious();" class="btn ccm-button-left"><?php  echo t('Previous'); ?></a>
		<a href="javascript:void(0)" onclick="getNext();" class="btn ccm-button-right"><?php  echo t('Next'); ?></a>
	</div>
	
</div>