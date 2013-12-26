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
$h = Loader::helper('concrete/interface');
$uh = Loader::helper('concrete/urls');
$formsURL=View::url('/dashboard/sixeightforms/forms', 'updateForm');
$txt = Loader::helper('text');
?>

<script type="text/javascript">
	$(document).ready(function() {	
		$('.tabs li a').click(function(e) {
			e.preventDefault();
			$('.tabs li').removeClass('active');
			$(this).parent().addClass('active');
			$('.ccm-tab').hide();
			$($(this).attr('href')).show();
		});
		
		$('#requiredColor').ColorPicker({
			onSubmit: function(hsb, hex, rgb, cal) {
				$('#requiredColor').val(hex);
				cal.hide();
			},
			onBeforeShow: function () {
				$(this).ColorPickerSetColor(this.value);
			}
		});
		
		$('#afterSubmit').change(function(e) {
			switch($(this).val()) {
				case 'thankyou':
					$('#afterSubmitRedirect').hide();
					$('#afterSubmitMessage').show();
					$('#afterSubmitURL').hide();
					$('#passRecordID-row').hide();
					$('#recordIDParameter-row').hide();
					break;
				case 'redirect':
					$('#afterSubmitRedirect').show();
					$('#afterSubmitMessage').hide();
					$('#afterSubmitURL').hide();
					$('#passRecordID-row').show();
					$('#recordIDParameter-row').show();
					break;
				case 'url':
					$('#afterSubmitRedirect').hide();					
					$('#afterSubmitMessage').hide();
					$('#afterSubmitURL').show();
					$('#passRecordID-row').show();
					$('#recordIDParameter-row').show();
					break;
			}
		});
			
<?php  
$form = sixeightForm::getByID(intval($_GET['fID']));
$fields = $form->getFields();
foreach($form->properties as $property => $value) {
?>
			$('#<?php  echo $property; ?>').val('<?php  echo str_replace("\r\n",'\n',str_replace("'","\'",$value)); ?>');
<?php  } ?>

<?php  if(is_numeric($form->properties['mailFrom'])) { ?>
		$('#mailFrom').hide();
		$('#mailFrom').val('');
		$('#mailFromType').val('dynamic');
		$('#dynamicMailFrom').val(<?php  echo intval($form->properties['mailFrom']); ?>);
<?php  } else { ?>
		$('#dynamicMailFrom').hide();
		$('#mailFromType').val('static');
<?php  } ?>

		$('#mailFromType').change(function() {
			if($(this).val() == 'static') {
				$('#dynamicMailFrom').hide();
				$('#mailFrom').show();
			} else {
				$('#mailFrom').hide();
				$('#dynamicMailFrom').show();
			}
		});

<?php  if(is_numeric($form->properties['mailFromAddress'])) { ?>
		$('#mailFromAddress').hide();
		$('#mailFromAddress').val('');
		$('#mailFromAddressType').val('dynamic');
		$('#dynamicMailFromAddress').val(<?php  echo intval($form->properties['mailFromAddress']); ?>);
<?php  } else { ?>
		$('#dynamicMailFromAddress').hide();
		$('#mailFromAddressType').val('static');
<?php  } ?>

		$('#mailFromAddressType').change(function() {
			if($(this).val() == 'static') {
				$('#dynamicMailFromAddress').hide();
				$('#mailFromAddress').show();
			} else {
				$('#mailFromAddress').hide();
				$('#dynamicMailFromAddress').show();
			}
		});

	});
</script>

<script type="text/javascript">
	$(document).ready(function() {
		switch($('#afterSubmit').val()) {
			case 'thankyou':
				$('#afterSubmitRedirect').hide();
				$('#afterSubmitMessage').show();
				$('#afterSubmitURL').hide();
				$('#passRecordID-row').hide();
				$('#recordIDParameter-row').hide();
				break;
			case 'redirect':
				$('#afterSubmitRedirect').show();
				$('#afterSubmitMessage').hide();
				$('#afterSubmitURL').hide();
				$('#passRecordID-row').show();
				$('#recordIDParameter-row').show();
				break;
			case 'url':
				$('#afterSubmitRedirect').hide();					
				$('#afterSubmitMessage').hide();
				$('#afterSubmitURL').show();
				$('#passRecordID-row').show();
				$('#recordIDParameter-row').show();
				break;
		}
	});
</script>
<div class="ccm-pane-controls ccm-ui">
	<form id="newFormForm" action="<?php  echo $formsURL; ?>" method="POST">
		<input type="hidden" name="fID" value="<?php  echo intval($_GET['fID']); ?>" />
		
		<ul class="nav-tabs tabs">
			<li class="active"><a href="#tab-general"><?php   echo t('General'); ?></a></li>
			<li><a href="#tab-submission"><?php   echo t('Submission'); ?></a></li>
			<li><a href="#tab-ecommerce"><?php   echo t('eCommerce'); ?></a></li>
			<li><a href="#tab-other"><?php   echo t('Other Settings'); ?></a></li>
			<?php   if(Package::getByHandle('sixeightdatadisplay')) { ?><li><a href="#tab-data-display"><?php   echo t('Data Display Integration'); ?></a></li><?php   } ?>
		</ul>
		
		<div class="spacer"></div>
		<div style="clear:both"></div>

		<div id="tab-general" class="ccm-tab" style="display:block">
			<div class="clearfix">
				<label for="name"><?php  echo t('Form Name'); ?></label>
				<div class="input">
					<input id="name" name="name" type="text" size="25" value="<?php  echo $_GET['newFormName']; ?>" class="ccm-input-text" />
				</div>
			</div>
			
			<div class="clearfix">
				<label for="sendMail"><?php  echo t('Send email notification?'); ?></label>
				<div class="input">
					<select name="sendMail" id="sendMail">
						<option value="">---</option>
						<option value="1"><?php  echo t('Yes'); ?></option>
						<option value="2"><?php  echo t('Yes, but not on edit.'); ?></option>
						<option value="0"><?php  echo t('No'); ?></option>
					</select>
				</div>
			</div>
			
			<div class="clearfix row-email">
				<label for="sendData"><?php  echo t('Include form data in email?'); ?></label>
				<div class="input">
					<select name="sendData" id="sendData">
						<option value="">---</option>
						<option value="1"><?php  echo t('Yes'); ?></option>
						<option value="0"><?php  echo t('No'); ?></option>
					</select>
				</div>
			</div>
	
			<div class="clearfix">
				<label for="mailFromType"><?php  echo t('From Name Method'); ?></label>
				<div class="input">
					<select id="mailFromType" name="mailFromType">
						<option value="static">Manually</option>
						<option value="dynamic">Based on form field value</option>
					</select>
					<br /><br />
					<div class="help-block">Send from a specific name or pull name from field data</div>
				</div>
			</div>
			
			<div class="clearfix row-email">
				<label for="mailFrom"><?php  echo t('From Name'); ?></label>
				<div class="input">
					<input id="mailFrom" name="mailFrom" type="text" size="25" />
					<select name="dynamicMailFrom" id="dynamicMailFrom">
						<option value="0">Field to use as from name</option>
						<option value="0">---</option>
						<?php  if(count($fields) > 0) { foreach($fields as $field) { ?>
							<option value="<?php  echo $field->ffID; ?>"><?php  echo $field->shortLabel; ?></option>
						<?php  } }?>
					</select>
				</div>
			</div>
			
			<div class="clearfix">
				<label for="mailFromAddressType"><?php  echo t('From Address Method'); ?></label>
				<div class="input">
					<select id="mailFromAddressType" name="mailFromAddressType">
						<option value="static">Manually</option>
						<option value="dynamic">Based on form field value</option>
					</select>
				</div>
			</div>
			
			<div class="clearfix row-email">
				<label for="mailFromAddress"><?php  echo t('From Email Address'); ?></label>
				<div class="input">
					<input id="mailFromAddress" name="mailFromAddress" type="text" size="25" />
					<select name="dynamicMailFromAddress" id="dynamicMailFromAddress">
						<option value="0">Field to use as from address</option>
						<option value="0">---</option>
						<?php  if(count($fields) > 0) { foreach($fields as $field) { ?>
							<option value="<?php  echo $field->ffID; ?>"><?php  echo $field->shortLabel; ?></option>
						<?php  } }?>
					</select>
				</div>
			</div>
			
			<div class="clearfix row-email">
				<label for="mailTo"><?php  echo t('Send email to'); ?></label>
				<div class="input">
					<input id="mailTo" name="mailTo" type="text" size="25" />
				</div>
			</div>
			
			<div class="clearfix row-email">
				<label for="mailSubject"><?php  echo t('Subject'); ?></label>
				<div class="input">
						<input id="mailSubject" name="mailSubject" type="text" size="25" />
				</div>
			</div>
		</div>
		
		<div id="tab-submission" class="ccm-tab" style="display:none">
			<div class="clearfix">
				<label for="afterSubmit"><?php  echo t('After submitting form'); ?></label>
				<div class="input">
					<select name="afterSubmit" id="afterSubmit">
						<option value="thankyou"><?php  echo t('Display a message'); ?></option>
						<option value="redirect"><?php  echo t('Redirect to a page within this site'); ?></option>
						<option value="url"><?php  echo t('Redirect to a specific URL'); ?></option>
					</select>
				</div>
			</div>
			<div class="clearfix" id="afterSubmitRedirect">
				<label for="thankYouCID"><?php  echo t('Page to redirect to'); ?></label>
				<div class="input">
					<?php  
					$ps = Loader::helper('form/page_selector');
					print $ps->selectPage('thankyouCID',$form->properties['thankyouCID']);
					?>
				</div>
			</div>
			<div class="clearfix" id="afterSubmitMessage">
				<label for="thankyouMsg"><?php  echo t('Thank you message'); ?></label>
				<div class="input">
					<textarea name="thankyouMsg" id="thankyouMsg" style="width:400px;height:100px"></textarea>
					<br /><br />
					<span class="help-block">(<?php  echo t('HTML Capable'); ?>)</span>
				</div>
			</div>
			<div class="clearfix" id="afterSubmitURL">
				<label for="thankyouURL"><?php  echo t('URL'); ?></label>
				<div class="input">
					<input type="text" size="50" id="thankyouURL" name="thankyouURL" />
					<br /><br />
					<span class="help-block"><?php  echo t('Must start with'); ?> http://</span>
				</div>
			</div>
			<div class="clearfix" id="passRecordID-row">
				<label for="passRecordID"><?php  echo t('Pass record ID to this page?'); ?></label>
				<div class="input">
					<select id="passRecordID" name="passRecordID">
						<option value="0"><?php  echo t('No'); ?></option>
						<option value="1"><?php  echo t('Yes'); ?></option>
					</select>
				</div>
			</div>
			<div class="clearfix" id="recordIDParameter-row">
				<label for="recordIDParameter"><?php  echo t('Record ID URL parameter name'); ?></label>
				<div class="input">
					<input type="text" size="50" id="recordIDParameter" name="recordIDParameter" />
				</div>
			</div>
			<div class="clearfix">
				<label for="captcha"><?php  echo t('Use') ;?> CAPTCHA?</label>
				<div class="input">
					<select id="captcha" name="captcha">
						<option value="0"><?php  echo t('No'); ?></option>
						<option value="1"><?php  echo t('Yes'); ?></option>
					</select>
				</div>
			</div>
			<div class="clearfix">
				<label for="autoIndex"><?php  echo t('Re-index search when form is submitted?'); ?></label>
				<div class="input">
					<select id="autoIndex" name="autoIndex">
						<option value="0"><?php  echo t('No'); ?></option>
						<option value="1"><?php  echo t('Yes'); ?></option>
					</select>
					<br /><br />
					<span class="help-block">If set to "no," you must manually re-index data</span>
				</div>
			</div>
			<div class="clearfix">
				<label for="submitLabel"><?php  echo t('Submit label text'); ?></label>
				<div class="input">
					<input id="submitLabel" name="submitLabel" type="text" value="Submit" />
				</div>
			</div>
			<div class="clearfix">
				<label for="maxSubmissions"><?php  echo t('Maximum submissions'); ?></label>
				<div class="input">
					<input id="maxSubmissions" name="maxSubmissions" type="text" />
				</div>
			</div>
		</div>
		
		<div id="tab-ecommerce" class="ccm-tab" style="display:none">
			<div class="clearfix">
				<label for="gateway"><?php  echo t('Payment Gateway'); ?></label>
				<div class="input">
					<select name="gateway" id="gateway">
						<option value="">---</option>
						<?php  foreach (sixeightform::getPaymentGateways() as $g) { ?>
							<option value="<?php  echo $g; ?>">
								<?php  if (strpos($g, '.') !== false) {
									print substr($txt->unhandle($g), 0, strrpos($g, '.'));
								} else {
									print $txt->unhandle($g);
								} ?>
							</option>
						<?php  } ?>
					</select>
				</div>
			</div>
			<div class="clearfix">
				<label for="currencySymbol"><?php  echo t('Currency Symbol'); ?></label>
				<div class="input">
					<input id="currencySymbol" name="currencySymbol" type="text" size="6" />
				</div>
			</div>
			<div class="clearfix">
				<label for="maximumPrice"><?php  echo t('Maximum Order Price'); ?></label>
				<div class="input">
					<input id="maximumPrice" name="maximumPrice" type="text" size="6" />
				</div>
			</div>
			<div class="clearfix">
				<label for="ecommerceConfirmation"><?php  echo t('Message before Processing eCommerce:'); ?></label>
				<div class="input">
					<textarea name="ecommerceConfirmation" id="ecommerceConfirmation" style="width:400px;height:100px" style="font-size:11px;"></textarea>
					<br /><br />
					<span class="help-block">(<?php  echo t('HTML Capable'); ?>)</span>
				</div>
			</div>
		</div>
		
		<div id="tab-other" class="ccm-tab" style="display:none">
			<div class="clearfix">
				<label for="handle"><?php  echo t('Form Handle'); ?></label>
				<div class="input">
					<input id="handle" name="handle" text="text" />
				</div>
			</div>
			<div class="clearfix">
				<label for="useHTML5"><?php  echo t('Use HTML5 field types?'); ?></label>
				<div class="input">
					<select name="useHTML5" id="useHTML5">
						<option value="0"><?php  echo t('No'); ?></option>
						<option value="1"><?php  echo t('Yes'); ?></option>
					</select>
				</div>
			</div>
			<div class="clearfix">
				<label for="sendApprovalNotification"><?php  echo t('Notify user upon approval?'); ?></label>
				<div class="input">
					<select name="sendApprovalNotification" id="sendApprovalNotification">
						<option value="0"><?php  echo t('No'); ?></option>
						<option value="1"><?php  echo t('Yes'); ?></option>
					</select>
				</div>
			</div>
			<div class="clearfix">
				<label for="requiredIndicator"><?php  echo t('Required field indicator'); ?></label>
				<div class="input">
					<input id="requiredIndicator" name="requiredIndicator" type="text" size="6" value="*" />
				</div>
			</div>
			<div class="clearfix">
				<label for="requiredColor"><?php  echo t('Required indicator color'); ?> (RGB)</label>
				<div class="input">
					&#35;<input id="requiredColor" name="requiredColor" type="text" size="6" value="ff0000"/>
				</div>
			</div>
			<div class="clearfix">
				<label for="autoExpire"><?php  echo t('Records automatically expire after'); ?></label>
				<div class="input">
					<input id="autoExpire" name="autoExpire" type="text" size="6" value=""/> <?php  echo t('days'); ?>
				</div>
			</div>
		</div>
		<?php  if(Package::getByHandle('sixeightdatadisplay')) { ?>
		<div id="tab-data-display" class="ccm-tab" style="display:none">
			<div class="clearfix">
				<label for="disableCache"><?php  echo t('Disable cache'); ?>?</label>
				<div class="input">
					<select name="disableCache" id="disableCache">
						<option value="0">No</option>
						<option value="1">Yes</option>
					</select>
				</div>
			</div>
			<div class="clearfix">
				<label for="autoCreatePage"><?php  echo t('Create a new page when form is submitted?'); ?></label>
				<div class="input">
					<select name="autoCreatePage" id="autoCreatePage">
						<option value="0"><?php  echo t('No'); ?></option>
						<option value="1"><?php  echo t('Yes'); ?></option>
					</select>
				</div>
			</div>
			<div class="clearfix">
				<label for="detailTemplateID"><?php  echo t('Default Detail Template'); ?></label>
				<div class="input">
					<select name="detailTemplateID" id="detailTemplateID">
					<?php  
						Loader::model('sixeightdatadisplay','sixeightdatadisplay');
						$detailTemplates = sixeightdatadisplay::getTemplates('detail');
						foreach($detailTemplates as $t) {
					?>
						<option value="<?php  echo $t['tID']; ?>"><?php  echo $t['templateName']; ?></option>
					<?php  } ?>
					</select>
				</div>
			</div>
			<div class="clearfix">
				<label for="parentCID"><?php  echo t('Create page as child of:'); ?></label>
				<div class="input">
					<?php  
					$fh = Loader::helper('form/page_selector');
					print $fh->selectPage('parentCID', $form->properties['parentCID']);
					?>
				</div>
			</div>
			<div class="clearfix">
				<label for="ctID"><?php  echo t('Page Type'); ?></label>
				<div class="input">
					<select name="ctID" id="ctID">
					<?php  
						Loader::model('collection_types');
						$ctArray = CollectionType::getList();
						foreach($ctArray as $ct) {
					?>
						<option value="<?php  echo $ct->getCollectionTypeID(); ?>"><?php  echo $ct->getCollectionTypeName()?></option>
					<?php  } ?>
					</select>
				</div>
			</div>
			<div class="clearfix">
				<label for="cName"><?php  echo t('Name'); ?></label>
				<div class="input">
					<select name="cName" id="cName">
						<?php  if(count($fields) > 0) { foreach($fields as $field) { ?>
							<option value="<?php  echo $field->ffID; ?>"><?php  echo $field->shortLabel; ?></option>
						<?php  } }?>
					</select>
				</div>
			</div>
			<div class="clearfix">
				<label for="cHandle"><?php  echo t('Alias'); ?></label>
				<div class="input">
					<select name="cHandle" id="cHandle">
						<option value="0">---</option>
						<?php  if(count($fields) > 0) { foreach($fields as $field) { ?>
							<option value="<?php  echo $field->ffID; ?>"><?php  echo $field->shortLabel; ?></option>
						<?php  } }?>
					</select>
				</div>
			</div>
			<div class="clearfix">
				<label for="cDescription"><?php  echo t('Description'); ?></label>
				<div class="input">
					<select name="cDescription" id="cDescription">
						<option value="0">---</option>
						<?php  if(count($fields) > 0) { foreach($fields as $field) { ?>
							<option value="<?php  echo $field->ffID; ?>"><?php  echo $field->shortLabel; ?></option>
						<?php  } }?>
					</select>
				</div>
			</div>
			<div class="clearfix">
				<label for="meta_title"><?php  echo t('Meta Title'); ?></label>
				<div class="input">
					<select name="meta_title" id="meta_title">
						<option value="0">---</option>
						<?php  if(count($fields) > 0) { foreach($fields as $field) { ?>
							<option value="<?php  echo $field->ffID; ?>"><?php  echo $field->shortLabel; ?></option>
						<?php  } }?>
					</select>
				</div>
			</div>
			<div class="clearfix">
				<label for="meta_keywords"><?php  echo t('Meta Keywords'); ?></label>
				<div class="input">
					<select name="meta_keywords" id="meta_keywords">
						<option value="0">---</option>
						<?php  if(count($fields) > 0) { foreach($fields as $field) { ?>
							<option value="<?php  echo $field->ffID; ?>"><?php  echo $field->shortLabel; ?></option>
						<?php  } }?>
					</select>
				</div>
			</div>
			<div class="clearfix">
				<label for="meta_description"><?php  echo t('Meta Description'); ?></label>
				<div class="input">
					<select name="meta_description" id="meta_description">
						<option value="0">---</option>
						<?php  if(count($fields) > 0) { foreach($fields as $field) { ?>
							<option value="<?php  echo $field->ffID; ?>"><?php  echo $field->shortLabel; ?></option>
						<?php  } }?>
					</select>
				</div>
			</div>
			<div class="clearfix">
				<label for="exclude_nav"><?php  echo t('Exclude from Nav?'); ?></label>
				<div class="input">
					<select name="exclude_nav" id="exclude_nav">
						<option value="0"><?php  echo t('No'); ?></option>
						<option value="1"><?php  echo t('Yes'); ?></option>
					</select>
				</div>
			</div>
		</div>
		<?php  } ?>
		<div class="dialog-buttons">
			<a href="javascript:void(0)" onclick="$.fn.dialog.closeTop();" class="btn ccm-button-left"><?php  echo t('Cancel'); ?></a>
			<a href="javascript:void(0)" onclick="validateFormName();" class="btn ccm-button-right primary"><?php  echo t('Save'); ?></a>
		</div>
	</form>
</div>