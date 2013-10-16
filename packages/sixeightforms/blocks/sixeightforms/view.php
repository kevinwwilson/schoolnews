<?php     
defined('C5_EXECUTE') or die(_("Access Denied."));
$uh = Loader::helper('concrete/urls');
global $c; 
$cID = intval($c->cID);
$bID = intval($bID);

if(($f->getTotalAnswerSetCount() >= $f->properties['maxSubmissions']) && (intval($f->properties['maxSubmissions']) != 0)) {
	echo t('This form is no longer available.');
} else if(intval($_GET['submittedBID']) != 0) { //If the form was submitted without AJAX
	//Display thank you message (if it was set to redirect, this would have happened when the form was processed)
	echo $f->properties['thankyouMsg'];
} else if((intval($_GET['bID']) != 0) && (intval($_GET['attempted']) == 1)) { //If an e-commerce transaction was attempted
	if($_GET['success'] == 1) { //If there was a successful e-commerce transaction
		if($f->properties['afterSubmit'] == 'thankyou') {
			//Show thank you message
			echo $f->properties['thankyouMsg'];
		} else {
			if($f->properties['afterSubmit'] == 'redirect') {
				//Redirect to page within site
				$redirectURL = View::url(Page::getCollectionPathFromID($f->properties['thankyouCID']));	
			} else {
				//Redirect to URL
				$redirectURL = $f->properties['thankyouURL'];	
			}
			//We have to do this with Javascript since headers are already loaded at this point.  TO DO: Look at moving this to the controller on_page_load function
			echo '<script type="text/javascript">window.location = "' . $redirectURL . '";</script>';
		}
	} else {
		//E-commerce was attempted, but not successful
		echo '<div style="color:#ff0000">' . t('There was an error processing the transaction.') . '</div>';
		$f->loadGatewayConfig();
		echo semGateway::getError($_GET['errorCode']);
	}
} else { // Display the form
?>

<script type="text/javascript">
<?php  if($f->hasWYSIWYGField()) { ?>
tinyMCE.init({
	mode : "textareas",
	theme : "advanced", 
	editor_selector : "sem-wysiwyg-basic",
	relative_urls : false,
	convert_urls: false,
	document_base_url: CCM_REL + '/',
	plugins: "advlink,paste",
    theme_advanced_buttons1 : "bold,italic,underline,strikethrough,link,unlink",
    theme_advanced_buttons2: "",
    theme_advanced_buttons3: "",
    theme_advanced_buttons4: "",
    theme_advanced_toolbar_location : "top",
    theme_advanced_toolbar_align : "left"
});

tinyMCE.init({
	mode : "textareas",
	theme : "concrete", 
	
	editor_selector : "sem-wysiwyg-simple",
	inlinepopups_skin : "concreteMCE",
	theme_concrete_buttons2_add : "spellchecker",
	relative_urls : false,
	document_base_url: CCM_REL + '/',
	convert_urls: false,
	plugins: "inlinepopups,spellchecker,safari,advlink",
	spellchecker_languages : "+English=en",
	force_br_newlines : true,
	force_p_newlines : false

});

tinyMCE.init({
	mode : "textareas",
	theme : "advanced",
	editor_selector : "sem-wysiwyg-advanced",
	theme_concrete_buttons2_add : "spellchecker",
	relative_urls : false,
	document_base_url: CCM_REL + '/',
	convert_urls: false,
	plugins: "inlinepopups,spellchecker,safari,advlink,table,advhr,xhtmlxtras,emotions,insertdatetime,paste,visualchars,nonbreaking,pagebreak,style",
	theme_advanced_buttons1 : "cut,copy,paste,pastetext,pasteword,|,undo,redo,|,styleselect,formatselect,fontsizeselect,fontselect",
	theme_advanced_buttons2 : "bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,|,bullist,numlist,|,outdent,indent,blockquote,|,link,unlink,anchor,|,forecolor,backcolor,|,image,charmap,emotions",
	theme_advanced_buttons3 : "cleanup,code,help,charmap,insertdate,inserttime,visualchars,nonbreaking,pagebreak,hr,|,tablecontrols",
	theme_advanced_blockformats : "p,address,pre,h1,h2,h3,div,blockquote,cite",
	theme_advanced_fonts : "Andale Mono=andale mono,times;Arial=arial,helvetica,sans-serif;Arial Black=arial black,avant garde;Book Antiqua=book antiqua,palatino;Comic Sans MS=comic sans ms,sans-serif;Courier New=courier new,courier;Georgia=georgia,palatino;Helvetica=helvetica;Impact=impact,chicago;Symbol=symbol;Tahoma=tahoma,arial,helvetica,sans-serif;Terminal=terminal,monaco;Times New Roman=times new roman,times;Trebuchet MS=trebuchet ms,geneva;Verdana=verdana,geneva;Webdings=webdings;Wingdings=wingdings,zapf dingbats",
	theme_advanced_font_sizes : "1,2,3,4,5,6,7",
	theme_advanced_more_colors : 1,						
	theme_advanced_toolbar_location : "top",	
	theme_advanced_toolbar_align : "left",
	spellchecker_languages : "+English=en"
});

tinyMCE.init({
	mode : "textareas",
	theme : "advanced",
	editor_selector : "sem-wysiwyg-office", 
	theme_concrete_buttons2_add : "spellchecker",
	relative_urls : false,
	document_base_url: CCM_REL + '/',
	convert_urls: false,
	spellchecker_languages : "+English=en",
	plugins : "safari,spellchecker,pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras", /* ,template,imagemanager,filemanager", */
	theme_advanced_buttons1 : "cut,copy,paste,pastetext,pasteword,|,search,replace,|,undo,redo,|,styleselect,formatselect,fontselect,fontsizeselect,", /* save,newdocument,help,|,  */
	theme_advanced_buttons2 : "bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,|,bullist,numlist,|,outdent,indent,blockquote,|,link,unlink,anchor,image,cleanup,code,|,forecolor,backcolor",
	theme_advanced_buttons3 : "tablecontrols,|,hr,removeformat,visualaid,|,sub,sup,|,insertdate,inserttime,|,ltr,rtl,", //
	theme_advanced_buttons4 : "charmap,emotions,iespell,media,advhr,|,fullscreen,preview,|,styleprops,spellchecker,|,cite,del,ins,attribs,|,visualchars,nonbreaking,blockquote,pagebreak", /* insertlayer,moveforward,movebackward,absolute,|,|,abbr,acronym,template,insertfile,insertimage  */
	theme_advanced_blockformats : "p,address,pre,h1,h2,h3,div,blockquote,cite",
	theme_advanced_fonts : "Andale Mono=andale mono,times;Arial=arial,helvetica,sans-serif;Arial Black=arial black,avant garde;Book Antiqua=book antiqua,palatino;Comic Sans MS=comic sans ms,sans-serif;Courier New=courier new,courier;Georgia=georgia,palatino;Helvetica=helvetica;Impact=impact,chicago;Symbol=symbol;Tahoma=tahoma,arial,helvetica,sans-serif;Terminal=terminal,monaco;Times New Roman=times new roman,times;Trebuchet MS=trebuchet ms,geneva;Verdana=verdana,geneva;Webdings=webdings;Wingdings=wingdings,zapf dingbats",
	theme_advanced_font_sizes : "1,2,3,4,5,6,7",
	theme_advanced_more_colors : 1,				
	theme_advanced_toolbar_location : "top",
	theme_advanced_toolbar_align : "left",		
	theme_advanced_statusbar_location : "bottom",	
	theme_advanced_resizing : true	
});
<?php  } ?>

<?php  if(($f->hasFileManagerField()) || ($f->hasWYSIWYGField())) { ?>

$(document).ready(function() {
	$(".dialog-launch").dialog();
	$('.sem-file-selector').dialog({ width: 900, title: 'Add File' });
});

/* store the selection/position for ie.. */
var bm;
var currentFileField;

setBookMark = function () {
	currentFileField = 0;
	bm = tinyMCE.activeEditor.selection.getBookmark();
}

ccm_selectSitemapNode = function(cID, cName) {
	var mceEd = tinyMCE.activeEditor;	
	var url = '<?php  echo BASE_URL . DIR_REL?>/index.php?cID=' + cID;
	
	mceEd.selection.moveToBookmark(bm);
	var selectedText = mceEd.selection.getContent();
	
	if (selectedText != '') {		
		mceEd.execCommand('mceInsertLink', false, {
			href : url,
			title : cName,
			target : null,
			'class' : null
		});
	} else {
		var selectedText = '<a href="' + url + '" title="' + cName + '">' + cName + '<\/a>';
		tinyMCE.execCommand('mceInsertRawHTML', false, selectedText, true); 
	}
	
}

ccm_chooseAsset = function(obj) {
	if (currentFileField == 0) {
		var mceEd = tinyMCE.activeEditor;
		mceEd.selection.moveToBookmark(bm); /*  reset selection to the bookmark (ie looses it) */
	
		switch(ccm_editorCurrentAuxTool) {
			case "image":
				var args = {};
				tinymce.extend(args, {
					src : obj.filePathInline,
					alt : obj.title,
					width : obj.width,
					height : obj.height
				});
				
				mceEd.execCommand('mceInsertContent', false, '<img id="__mce_tmp" src="javascript:;" />', {skip_undo : 1});
				mceEd.dom.setAttribs('__mce_tmp', args);
				mceEd.dom.setAttrib('__mce_tmp', 'id', '');
				mceEd.undoManager.add();
				break;
			default: /* file */
				var selectedText = mceEd.selection.getContent();
				
				if(selectedText != '') { /*  make a link, let mce deal with the text of the link.. */
					mceEd.execCommand('mceInsertLink', false, {
						href : obj.filePath,
						title : obj.title,
						target : null,
						'class' :  null
					});
				} else { /* insert a normal link */
					var html = '<a href="' + obj.filePath + '">' + obj.title + '<\/a>';
					tinyMCE.execCommand('mceInsertRawHTML', false, html, true); 
				}
			break;
		}
	} else {
		/* File manager field */
		$('#sem-file-selector-' + currentFileField).html(obj.title);
		$('#sem-field-' + currentFileField).val(obj.fID);
	}
}
<?php  } //End check for File Manager/WYSIWYG ?>

$(document).ready(function() {	
	
	$('#sem-form-<?php  echo $bID; ?>').submit(function() {
		
		/* Hide the submit button */
		$('#sem-submit-<?php  echo $bID; ?>-container').hide();
		
		/* Show the submit message */
		$('#sem-submit-<?php  echo $bID; ?>-message').show();
		
		/* If there's no files to be uploaded (or all of them have been completed), process the form */
		if(requiredFileUploads == 0) {
			processSixeightForm('sem-form-<?php  echo $bID; ?>');
		}
		
		<?php   //This code starts the upload process for each file upload
			foreach($fields as $field) {
				if($field->type == 'File Upload') {
		?>
		$('#sem-file-<?php  echo $field->ffID; ?>').uploadifyUpload();
		<?php  
				}
			}
		?>
		
		return false;
	});
	
	function showError(text) {
		$('html,body').animate({
			scrollTop: $('#sem-form-<?php  echo $bID; ?>').offset().top - 50
		},1000);
		$('#sem-error-<?php  echo $bID; ?>').stop().fadeOut().html(text).slideDown().delay(5000).slideUp();
	}
	
	function showMessage(text) {
		$('#sem-message').html(text).slideDown();
	}
	
	function processSixeightForm(bID) {
		<?php  if($f->hasWYSIWYGField()) { ?>
		tinyMCE.triggerSave();
		<?php  } ?>
		var $f = $('#' + bID);
		var formData = $f.serialize();
		$.ajax({
			type: 'POST',
			url: $f.attr('action') + '?js=1',
			dataType: 'json',
			data: formData,
			error: function(XMLHttpRequest,status,errorThrown) {
				showError('<?php  echo t('There was an error submitting this form.  Please contact the administrator of this site.'); ?>');
			},
			success: function(response) {
				if(response.hasErrors == '1') {
					 /* Reset submit button */
					$('#sem-submit-<?php  echo $bID; ?>-message').hide();
					$('#sem-submit-<?php  echo $bID; ?>-container').show();
		
					/* Clear error fields */
					$('.sem-field-container').removeClass('sem-error-field');

					switch(response.errorType) {
						case 'permissions':
							switch(response.permissionsError) {
								case 'add':
									showError('<?php  echo t('You do not have access to submit this form.'); ?>');
									break;
								case 'edit':
									showError('<?php  echo t('You do not have access to edit this record.'); ?>');
									break;
							}
							break;
						case 'captcha':
							$('#sem-captcha-image-<?php  echo $fID; ?>').load('<?php  echo $uh->getToolsURL('refresh_captcha','sixeightforms'); ?>');
							showError('<?php  echo t('Incorrect CAPTCHA code.  Please try again.'); ?>');
							break;
						case 'validation':
							 /* Highlight the error fields */
							$.each(response.errors, function(i,error) {
								$('#sem-field-container-' + error.ffID).addClass('sem-error-field');
								$('.sem-error-field').focus(function() {
									$(this).removeClass('sem-error-field');
								});
							});
							showError('<?php  echo t('Please review the highlighted fields.'); ?>');
							break;
					}
				} else if((response.hasCommerce == '1') && (response.amountCharged > 0)) {
					$('#sem-form-<?php  echo $bID; ?>').fadeOut('fast',function() {
						$.post('<?php  echo $uh->getToolsURL('payment_form','sixeightforms'); ?>', {
								fID: <?php  echo $fID; ?>,
								asID: response.asID,
								asC: response.asC,
								cc: response.cc,
								cID: <?php  echo $cID; ?>,
								bID: <?php  echo intval($bID); ?>
							}, function(data) {
							$('#sem-form-response-<?php  echo $bID; ?>').html(data);
							$('#sem-form-response-<?php  echo $bID; ?>').fadeIn('fast');
						});
					});
				} else {
					switch(response.action) {
						case 'thankyou':
							$('#sem-form-<?php  echo $bID; ?>').fadeOut('fast',function() {
								<?php   if($displayInDialog != 1) { ?>
									$('#sem-form-response-<?php  echo $bID; ?>').html(response.response);
								<?php   } ?>
								$('#sem-form-response-<?php  echo $bID; ?>').fadeIn('fast');
								<?php   if($displayInDialog == 1) { ?>
									window.location.reload(true); 
								<?php   } ?>
							});
							break;
						case 'redirect':
							window.location = response.response;
							break;
						case 'url':
							window.location = response.response;
							break;
					}
				}
			}
		});
	}
	
	var requiredFileUploads = 0;
	
	<?php  
		foreach($fields as $field) {
			if($field->type == 'File Upload') {
	?>
	$('#sem-file-<?php  echo $field->ffID; ?>').uploadify({
		'uploader'	:	'<?php  echo $this->getBlockURL(); ?>/swf/uploadify.swf',
		'script'	:	'<?php  echo $uh->getToolsURL('upload_file','sixeightforms'); ?>',
		'cancelImg'	:	'<?php  echo $this->getBlockURL(); ?>/images/btn_cancel.png',
		'buttonImg'	:	'<?php  echo $this->getBlockURL(); ?>/images/btn_browse.png',
		'width'		:	86,
		'height'	:	38,
		'wmode'		:	'transparent',
		'auto'		:	false,
		'onSelect'	:	function() {
			/* Each time a file has been selected, increment the number of file uploads we wait on */
			requiredFileUploads++;
		},
		'onCancel'	:	function() {
			requiredFileUploads--;
		},
		'onComplete':	function(event,queueID,fileObj,response) {
			requiredFileUploads--;
			$('#sem-field-<?php  echo $field->ffID; ?>').val(response);
			if(requiredFileUploads == 0) {
				processSixeightForm('sem-form-<?php  echo $bID; ?>');
			}
		}
	});
	<?php  
			}
		}
	?>
	
	$('.sem-next-button').click(function(e) {
		e.preventDefault();
		var $section = $(this).parent().parent();
		
		if($(this).hasClass('validate-section')) {
			var formData = $('#sem-form-<?php  echo $bID; ?>').serialize();
			var buttonID = $(this).attr('name');
			$.ajax({
				type: 'POST',
				url: '<?php  echo $uh->getToolsURL('validate_fields','sixeightforms'); ?>?fID=<?php  echo $fID; ?>&sectionID=' + buttonID,
				dataType: 'json',
				data: formData,
				success: function(response) {
					if(response.hasErrors == '1') {
						/* Reset submit button */
						$('#sem-submit-<?php  echo $bID; ?>-message').hide();
						$('#sem-submit-<?php  echo $bID; ?>-container').show();
			
						/* Clear error fields */
						$('.sem-field-container').removeClass('sem-error-field');
						
						/* Highlight the error fields */
						$.each(response.errors, function(i,error) {
							$('#sem-field-container-' + error.ffID).addClass('sem-error-field');
							$('.sem-error-field').focus(function() {
								$(this).removeClass('sem-error-field');
							});
						});
						showError('<?php  echo t('Please review the highlighted fields.'); ?>');
					} else {
						$section.fadeOut('fast',function() {
							$section.next().fadeIn();
						});
					}
				}
			});
		}
	});
	
	$('.sem-previous-button').click(function(e) {
		e.preventDefault();
		var $section = $(this).parent().parent();
		$section.fadeOut('fast',function() {
			$section.prev().fadeIn();
		});
	});
	
	<?php  foreach($f->getRules() as $rule) { ?>
		$('#sem-field-container-<?php  echo $rule['actionField']; ?>').hide();
		$('.sem-field-<?php  echo $rule['ffID']; ?>').live('change',function() {
			if(($(this).is(':checkbox')) && ($(this).val() != '<?php  echo $rule['value']; ?>')) {
				return false;
			}
			
			$('#sem-field-container-<?php  echo $rule['actionField']; ?>').hide();
		});
	<?php  } ?>
	
	<?php  foreach($f->getRules() as $rule) { ?>
	$('.sem-field-<?php  echo $rule['ffID']; ?>').live('change',function() {
		<?php  if ($rule['comparison'] == 'is equal to') { ?>
		if((($(this).attr('type') != 'checkbox') && ($(this).val() == '<?php  echo $rule['value']; ?>')) || (($(this).attr('type') == 'checkbox') && ($(this).val() == '<?php  echo $rule['value']; ?>') && ($(this).attr('checked')))) {
		<?php  } else { ?>
		if((($(this).attr('type') != 'checkbox') && ($(this).val() != '<?php  echo $rule['value']; ?>')) || (($(this).attr('type') == 'checkbox') && ($(this).val() == '<?php  echo $rule['value']; ?>') && (!$(this).attr('checked')))) {
		<?php  } ?>
			$('#sem-field-container-<?php  echo $rule['actionField']; ?>').show();
			<?php  if ($rule['ogID'] != 0) { ?>
				$('#sem-field-container-<?php  echo $rule['actionField']; ?>').empty();
				$('#sem-field-container-<?php  echo $rule['actionField']; ?>').html(optionGroups<?php  echo $rule['ogID']; ?>);
			<?php  } ?>
		}
	});
	<?php  } ?>

	<?php  Loader::helper('form','sixeightforms'); ?>
	<?php  $f->renderAlternateOptions(); ?>
	
	$('.sem-checkbox').change(function(e) {
		if($(this).is(':checked')) {
			$(this).parent().addClass('active');
		} else {
			$(this).parent().removeClass('active');
		}
	});
	
	$('.sem-radio-button').click(function(e) {
		var id = $(this).attr('name');
		$('label','#sem-fieldset-' + id).removeClass('active');
		$(this).parent().addClass('active');
	});
});

</script>

<div id="sem-message" style="display:none"></div>
<?php 
if(intval($_GET['errorBID']) != 0) { 
	echo '<div id="sem-error-' . $bID . '">';
	if((count($_GET['errorField']) > 0) && ($_GET['errorType'] == 'validation')) {
		echo t('<strong>Please review the following fields:</strong>');
		echo '<ul>';
		foreach($_GET['errorField'] as $errorFfID) {
			$errorField = sixeightfield::getByID($errorFfID);
			echo '<li>' . $errorField->label . '</li>';
		}
		echo '</ul>';
	} elseif ($_GET['errorType'] == 'captcha') {
		echo t('Incorrect CAPTCHA code.  Please try again.');
	}
	echo '</div>';
} else {
?>
<div class="sem-error" id="sem-error-<?php  echo $bID; ?>" style="display:none"></div>
<?php  } ?>
<form class="sem-form" id="sem-form-<?php  echo $bID; ?>" enctype="multipart/form-data" method="post" action="<?php  echo $uh->getToolsURL('process_form','sixeightforms'); ?>">
<input name="sem-form-id" type="hidden" value="<?php  echo $f->properties['fID']; ?>" />
<?php   if($editingRecord) { ?>
<input name="asID" value="<?php  echo $asID; ?>" type="hidden" />
<input name="editCode" value="<?php  echo htmlspecialchars($editCode); ?>" type="hidden" />
<?php   } ?>
<input name="bID" value="<?php  echo $bID; ?>" type="hidden" />
<?php 
	$section = 1;
?>
<div class="sem-form-section sem-form-section-<?php  echo $section; ?> sem-form-<?php  echo $f->properties['fID']; ?>-section" id="sem-form-<?php  echo $f->properties['fID']; ?>-section-<?php  echo $section; ?>">
<?php  
global $c;
$i=1;
foreach($fields as $field) {
	if($field->type == 'Hidden') {
		$field->render();
	} elseif($field->type == 'Section Divider') {
		$section++;
		echo '</div><!-- /.sem-form-section -->';
		echo '<div class="sem-form-section sem-form-' . $f->properties['fID'] . '-section" id="sem-form-' . $f->properties['fID'] . '-section-' . $section . '">';
	} else {
		$field->render();
	}
}
?>
<?php  if(($f->properties['captcha'] == '1') && (!$editingRecord)) { ?>
<div class="sem-field-container" id="sem-captcha-container-<?php  echo $fID; ?>">
	<div class="sem-field-label" id="sem-captcha-label-<?php  echo $fID; ?>">
		<?php  echo t('Please type the letters and numbers shown in the image.'); ?>
	</div>
	<div class="sem-field-wrapper" id="sem-captcha-wrapper-<?php  echo $fID; ?>">
		<div class="sem-captcha-image" id="sem-captcha-image-<?php  echo $fID; ?>">
		<?php  
		$captcha = Loader::helper('validation/captcha');
		$captcha->display();
		?>
		</div>
		<div class="sem-captcha-input" id="sem-captcha-input-<?php  echo $fID; ?>">
		<?php  
		$captcha->showInput();
		?>
		</div>
	</div>
</div>
<?php  } ?>
<div class="sem-field-container" id="sem-submit-<?php  echo $bID; ?>-message" style="display:none"><img src="<?php   echo DIR_REL; ?>/concrete/images/throbber_white_16.gif" /> <?php  echo t('Processing'); ?></div>
<div class="sem-field-container" id="sem-submit-<?php  echo $bID; ?>-container">
	<input type="submit" name="sem-submit-<?php  echo $bID; ?>" class="sem-submit" value="<?php  echo $f->properties['submitLabel']; ?>" />
</div>
</div><!-- /#sem-form-section -->
</form>
<div class="sem-form-response" id="sem-form-response-<?php  echo $bID; ?>" style="display:none"><div style="text-align:center"><b><?php  echo t('The data has been submitted.'); ?></b><br /><img src="<?php  echo ASSETS_URL_IMAGES?>/throbber_white_32.gif" width="32" height="32" /></div></div>
<?php  } ?>