<?php    
$txt = Loader::helper('text');
$h = Loader::helper('concrete/interface');
$uh = Loader::helper('concrete/urls');
$form = Loader::helper('form');
$this->addHeaderItem($html->javascript('codemirror.js', 'sixeightdatadisplay'));
?>
<script type="text/javascript">
$(document).ready(function() {
	$('.delete-template-link').click(function(e) {
		if(confirm('<?php  echo t('Are you sure you want to delete this template?'); ?>')) {
			return true;
		} else {
			e.preventDefault();
		}
	});

	$('#new-template-form').submit(function(e) {
		if($('#newTemplateName').val()=='') {
			alert('<?php  echo t('Please enter a template name'); ?>');
			e.preventDefault();
		} else if($('#newTemplateFID').val()=='') {
			alert('<?php  echo t('Please select a form to use with the template.'); ?>');
			e.preventDefault();
		}
	});
});

function closeEditor() {
	if(confirm('<?php  echo t('Are you sure you want to close the editor?'); ?>\n\n<?php  echo t('Click "Cancel" to continue editing.'); ?>')) {
		location.href = '<?php  echo $this->url("/dashboard/sixeightdatadisplay/list") ?>';
	}
}

function w3validate(url) {
	if(confirm('<?php  echo t('This will validate the selected section as well-formed XML using the W3C Markup Validation Service.  In order to validate your template:'); ?>\n\n<?php  echo t('1. Your template must be saved (It checks only checks what you have saved).'); ?>\n<?php  echo t('2.Your site must be publicly accessible.'); ?>\n\n<?php  echo t('Click \"OK\" to continue.'); ?>')) {
		window.open(url);
	}
}

function ddEditorSitemapOverlay() {
    $.fn.dialog.open({
        title: '<?php  echo t('Choose A Page'); ?>',
        href: CCM_TOOLS_PATH + '/sitemap_overlay.php?sitemap_mode=select_page',
        width: '550',
        modal: false,
        height: '400'
    });
}

ccm_selectSitemapNode = function(cID, cName) {
	var url = '<?php  echo BASE_URL . DIR_REL?>/index.php?cID=' + cID;
	switch(dd_currentEditor) {
		case "content":
			templateContentEditor.replaceSelection(url);
			break;
		case "alternateContent":
			templateAlternateContentEditor.replaceSelection(url);
	}
}

var ccm_editorCurrentAuxTool = '';
var dd_currentEditor = '';
ccm_chooseAsset = function(obj) {
	switch(ccm_editorCurrentAuxTool) {
		case "image":
			code = '<img src="' + obj.filePathInline + '" width="' + obj.width + '" height="' + obj.height + '" />';
			break;
		default: // file
			code = obj.filePath;
			break;
	}
	
	switch(dd_currentEditor) {
		case "content":
			templateContentEditor.replaceSelection(code);
			break;
		case "alternateContent":
			templateAlternateContentEditor.replaceSelection(code);
	}
}

$(document).ready(function() {
	$('.ccm-results-list tr:even').addClass('ccm-list-record-alt');
	 $("#content-file-selector,#alternateContent-file-selector").click(function(e) {
	 	e.preventDefault();
	 	var href= $(this).attr('href');
	 	$.fn.dialog.open({
			width: 900,
			height: 600,
			modal: false,
			href: href,
			title: '<?php  echo t('Select File'); ?>'			
		});
	 }); 
	 $("#content-image-selector,#alternateContent-image-selector").click(function(e) {
	 	e.preventDefault();
	 	var href= $(this).attr('href');
	 	$.fn.dialog.open({
			width: 900,
			height: 600,
			modal: false,
			href: href,
			title: '<?php  echo t('Add Image'); ?>'			
		});
	 });
});

</script>
<?php  if(!isset($loadedTemplate)) {  //Begin default section ?>
<?php  echo Loader::helper('concrete/dashboard')->getDashboardPaneHeaderWrapper(t('Templates'), false);?>

	<table id="ccm-search-form-table" >
		<tr>
			<td width="210" valign="top" class="ccm-search-form-advanced-col">
				<form id="new-template-form" action="<?php  echo $this->url($c->getCollectionPath(),'createTemplate')?>" method="get">
					<div class="ccm-search-advanced-fields" >
						<h3><?php  echo t('Create Template'); ?></h3>
						<div id="ccm-search-advanced-fields-inner">
							<div class="help-block"><?php  echo t('Template Name'); ?></div>
							<div class="ccm-search-field">
								<input type="text" id="newTemplateName" name="templateName" style="width:200px" class="ccm-input-text" />
							</div>
							<div class="ccm-search-field">
								<div class="help-block"><?php  echo t('Form'); ?></div>
								<select name="fID" id="newTemplateFID">
									<option value="">---</option>
									<?php  foreach($forms as $form) { ?>
										<option value="<?php  echo $form->fID; ?>"><?php  echo $form->properties['name']; ?></option>
									<?php  } ?>
								</select>
							</div>
							<div class="ccm-search-field">
								<strong><?php  echo t('Template Type'); ?>:</strong>
								<input type="radio" name="templateType" value="list" checked="checked" /> <?php  echo t('List'); ?> <input type="radio" name="templateType" value="detail" /> <?php  echo t('Detail'); ?>
							</div>
							<input type="submit" class="btn primary" value="<?php  echo t('Create Template'); ?>" />
						</div>
					</div>
				</form>
			</td>
			<td valign="top">
				
				<?php  if(count($templates)) { ?>
					<table width="100%" cellpadding="8" cellspacing="0" border="0" class="ccm-results-list">
						<tr>
							<th><?php  echo t('Template Name'); ?></th>
							<th width="25"><?php  echo t('Type'); ?></th>
							<th width="25"><?php  echo t('Edit'); ?></th>
							<th width="25"><?php  echo t('Delete'); ?></th>
						</tr>
						<?php  foreach($templates as $t) { ?>
							<tr class="ccm-list-record">
								<td><?php  echo $t['templateName']; ?></td>
								<td><?php  echo ucwords($t['templateType']); ?></td>
								<td><a href="<?php  echo View::url('/dashboard/sixeightdatadisplay/list/getTemplate'); ?>?tID=<?php  echo $t['tID']; ?>"><img src="<?php  echo ASSETS_URL_IMAGES; ?>/icons/edit_small.png" /></a></td>
								<td><a class="delete-template-link" href="<?php  echo View::url('/dashboard/sixeightdatadisplay/list/deleteTemplate'); ?>?tID=<?php  echo $t['tID']; ?>"><img src="<?php  echo ASSETS_URL_IMAGES; ?>/icons/delete_small.png" /></a></td>
							</tr>
						<?php  } ?>
					</table>
				<?php  } ?>
			</td>
		</tr>
	</table>

<?php  } else { //Begin template editor section ?>
<?php  echo Loader::helper('concrete/dashboard')->getDashboardPaneHeaderWrapper(t('Edit Template'), false);?>

	<?php  if($loadedTemplate['templateType'] == 'list') { ?>
		<form id="save-template-form" action="<?php  echo  $this->url($c->getCollectionPath(),'saveTemplate')?>" method="post">
		<input type="hidden" name="tID" value="<?php  echo $loadedTemplate['tID']; ?>" />
		<table class="entry-form" border="0" cellspacing="1" cellpadding="0">
			<tr>
				<td class="subheader" width="50%"><?php  echo t('Template Name'); ?></td>
				<td class="subheader"><?php  echo t('Associated Form'); ?></td>
				<td class="subheader"><?php  echo t('Template Type'); ?></td>
			</tr>
			<tr>
				<td><input id="templateName" name="templateName" type="text" style="width:90%" value="<?php  echo $loadedTemplate['templateName']; ?>" /></td>
				<td>
				<select name="fID">
							<?php  foreach($forms as $form) { ?>
									<option value="<?php  echo $form->fID; ?>" <?php  if ($loadedTemplate['fID'] == $form->fID) { echo 'selected="selected"'; } ?>><?php  echo $form->properties['name']; ?></option>
							<?php  } ?>
						</select>
				</td>
				<td><strong><?php  echo ucwords($loadedTemplate['templateType']); ?></strong></td>
			</tr>
			<tr>
				<td colspan="3" class="subheader"><?php  echo t('Header'); ?></td>
			</tr>
			<tr>
				<td colspan="3"><textarea id="templateHeader" name="templateHeader" style="width:100%;height:250px;"><?php  echo htmlspecialchars($loadedTemplate['templateHeader']); ?></textarea></td>
			</tr>
			<tr>
				<td colspan="3" class="subheader">
					<?php  echo t('Content'); ?><br />
				</td>
			</tr>
			<tr> 
				<td colspan="3">
					<div style="float:left">
						<select id="phContentSelect">
						<?php  foreach($placeholders as $ph) {
							echo '<option value="' . $ph['code'] . '">' . $ph['title'] . '</option>';
						} ?>
						</select>
						<input type="button" onclick="templateContentEditor.replaceSelection($('#phContentSelect').val());" value="<?php  echo t('Insert'); ?>" />&nbsp;&nbsp;&nbsp;
						<a href="javascript:void(0)" onclick="ddEditorSitemapOverlay();dd_currentEditor='content';" class="dialog-launch"><?php  echo t('Insert Path to Page'); ?></a>&nbsp;&nbsp;&nbsp;
						<a id="content-file-selector" href="<?php  echo REL_DIR_FILES_TOOLS_REQUIRED?>/files/search_dialog?search=1" onclick="ccm_editorCurrentAuxTool='file';dd_currentEditor='content';" class="dialog-launch" dialog-modal="false"><?php  echo t('Insert Path to File'); ?></a>&nbsp;&nbsp;&nbsp;
						<a id="content-image-selector" href="<?php  echo REL_DIR_FILES_TOOLS_REQUIRED?>/files/search_dialog?search=1" onclick="ccm_editorCurrentAuxTool='image';dd_currentEditor='content';" class="dialog-launch" dialog-modal="false"><?php  echo t('Insert Image'); ?></a>
					</div>
					<div style="float:right">
						<input type="button" value="<?php  echo t('Validate'); ?>" onclick="w3validate('http://validator.w3.org/check?uri=<?php  echo urlencode(BASE_URL . $uh->getToolsURL('templateContent','sixeightdatadisplay') . '?tID=' . $loadedTemplate['tID']); ?>')">
					</div>
				</td>
			</tr>
			<tr>
				<td colspan="3">
					<textarea id="templateContent" name="templateContent" style="width:100%;height:200px;"><?php  echo htmlspecialchars($loadedTemplate['templateContent']); ?></textarea>
				</td>
			</tr>
			<tr>
				<td colspan="3" class="subheader"><?php  echo t('Alternate Content'); ?></td>
			</tr>
			<tr> 
				<td colspan="3">
					<div style="float:left">
						<select id="phAlternateContentSelect">
							<option value="">---</option>
						<?php     foreach($placeholders as $ph) {
							echo '<option value="' . $ph['code'] . '">' . $ph['title'] . '</option>';
						} ?>
						</select>
						<input type="button" onclick="templateAlternateContentEditor.replaceSelection($('#phAlternateContentSelect').val());" value="<?php  echo t('Insert'); ?>" />&nbsp;&nbsp;&nbsp;
						<a href="javascript:void(0)" onclick="ddEditorSitemapOverlay();dd_currentEditor='alternateContent';" class="dialog-launch"><?php  echo t('Insert Path to Page'); ?></a>&nbsp;&nbsp;&nbsp;
						<a id="alternateContent-file-selector" href="<?php  echo REL_DIR_FILES_TOOLS_REQUIRED?>/files/search_dialog?search=1" onclick="ccm_editorCurrentAuxTool='file';dd_currentEditor='alternateContent';" class="dialog-launch" dialog-modal="false"><?php  echo t('Insert Path to File'); ?></a>&nbsp;&nbsp;&nbsp;
						<a id="alternateContent-image-selector" href="<?php  echo REL_DIR_FILES_TOOLS_REQUIRED?>/files/search_dialog?search=1" onclick="ccm_editorCurrentAuxTool='image';dd_currentEditor='alternateContent';" class="dialog-launch" dialog-modal="false"><?php  echo t('Insert Image'); ?></a>
					</div>
					<div style="float:right">
						<input type="button" value="<?php  echo t('Validate'); ?>" onclick="w3validate('http://validator.w3.org/check?uri=<?php  echo urlencode(BASE_URL . $uh->getToolsURL('templateContent','sixeightdatadisplay') . '?section=alt&tID=' . $loadedTemplate['tID']); ?>')">
					</div>
				</td>
			</tr>
			<tr>
				<td colspan="3"><textarea id="templateAlternateContent" name="templateAlternateContent" style="width:100%;height:200px;"><?php  echo htmlspecialchars($loadedTemplate['templateAlternateContent']); ?></textarea></td>
			</tr>
			<tr>
				<td colspan="3" class="subheader"><?php  echo t('Footer'); ?></td>
			</tr>
			<tr>
				<td colspan="3"><textarea id="templateFooter" name="templateFooter" style="width:100%;height:200px;"><?php  echo htmlspecialchars($loadedTemplate['templateFooter']); ?></textarea></td>
			</tr>
			<tr>
				<td colspan="3" class="subheader"><?php  echo t('Empty (Used when no results are found)'); ?></td>
			</tr>
			<tr>
				<td colspan="3"><textarea id="templateEmpty" name="templateEmpty" style="width:100%;height:200px;"><?php  echo htmlspecialchars($loadedTemplate['templateEmpty']); ?></textarea></td>
			</tr>
		</table>
		<?php  echo $h->button_js( t('Save'), 'saveTemplate()','left','primary'); ?>
		<?php  echo $h->button_js( t('Cancel'), 'closeEditor()','left'); ?>
		<div style="clear:both"></div>
		</form>
		
		<script type="text/javascript">
		function saveTemplate() {
			if($('#templateName').val() == '') {
				alert('<?php  echo t('Please enter a name for the template'); ?>');
				return false;
			} else {
				$('#templateHeader').val(templateHeaderEditor.getCode());
				$('#templateContent').val(templateContentEditor.getCode());
				$('#templateAlternateContent').val(templateAlternateContentEditor.getCode());
				$('#templateFooter').val(templateFooterEditor.getCode());
				$('#templateEmpty').val(templateEmptyEditor.getCode());
				$('#save-template-form').submit();
			}
		}
		var templateHeaderEditor = CodeMirror.fromTextArea('templateHeader', {
			height: "200px",
			parserfile: ["parsexml.js", "parsecss.js", "tokenizejavascript.js", "parsejavascript.js","parsehtmlmixed.js"],
			stylesheet: [CCM_REL + "/packages/sixeightdatadisplay/css/xmlcolors.css", CCM_REL + "/packages/sixeightdatadisplay/css/jscolors.css", CCM_REL + "/packages/sixeightdatadisplay/css/csscolors.css"],
			path: CCM_REL + '/packages/sixeightdatadisplay/js/',
			tabMode: "shift",
			indentUnit: 4
		});
		
		var templateContentEditor = CodeMirror.fromTextArea('templateContent', {
			height: "200px",
			parserfile: ["parsexml.js", "parsecss.js", "tokenizejavascript.js", "parsejavascript.js","parsehtmlmixed.js"],
			stylesheet: [CCM_REL + "/packages/sixeightdatadisplay/css/xmlcolors.css", CCM_REL + "/packages/sixeightdatadisplay/css/jscolors.css", CCM_REL + "/packages/sixeightdatadisplay/css/csscolors.css"],
			path: CCM_REL + '/packages/sixeightdatadisplay/js/',
			tabMode: "shift",
			indentUnit: 4
		});
		
		var templateAlternateContentEditor = CodeMirror.fromTextArea('templateAlternateContent', {
			height: "200px",
			parserfile: ["parsexml.js", "parsecss.js", "tokenizejavascript.js", "parsejavascript.js","parsehtmlmixed.js"],
			stylesheet: [CCM_REL + "/packages/sixeightdatadisplay/css/xmlcolors.css", CCM_REL + "/packages/sixeightdatadisplay/css/jscolors.css", CCM_REL + "/packages/sixeightdatadisplay/css/csscolors.css"],
			path: CCM_REL + '/packages/sixeightdatadisplay/js/',
			tabMode: "shift",
			indentUnit: 4
		});
		
		var templateFooterEditor = CodeMirror.fromTextArea('templateFooter', {
			height: "200px",
			parserfile: ["parsexml.js", "parsecss.js", "tokenizejavascript.js", "parsejavascript.js","parsehtmlmixed.js"],
			stylesheet: [CCM_REL + "/packages/sixeightdatadisplay/css/xmlcolors.css", CCM_REL + "/packages/sixeightdatadisplay/css/jscolors.css", CCM_REL + "/packages/sixeightdatadisplay/css/csscolors.css"],
			path: CCM_REL + '/packages/sixeightdatadisplay/js/',
			tabMode: "shift",
			indentUnit: 4
		});
		
		var templateEmptyEditor = CodeMirror.fromTextArea('templateEmpty', {
			height: "200px",
			parserfile: ["parsexml.js", "parsecss.js", "tokenizejavascript.js", "parsejavascript.js","parsehtmlmixed.js"],
			stylesheet: [CCM_REL + "/packages/sixeightdatadisplay/css/xmlcolors.css", CCM_REL + "/packages/sixeightdatadisplay/css/jscolors.css", CCM_REL + "/packages/sixeightdatadisplay/css/csscolors.css"],
			path: CCM_REL + '/packages/sixeightdatadisplay/js/',
			tabMode: "shift",
			indentUnit: 4
		});
		</script>
	<?php  } else { // It's a detail template ?>
		<form id="save-template-form" action="<?php  echo $this->url($c->getCollectionPath(),'saveTemplate')?>" method="post">
		<input type="hidden" name="tID" value="<?php  echo $loadedTemplate['tID']; ?>" />
		<table class="entry-form" border="0" cellspacing="1" cellpadding="0">
			<tr>
				<td class="subheader" width="50%"><?php  echo t('Template Name'); ?></td>
				<td class="subheader"><?php  echo t('Associated Form'); ?></td>
				<td class="subheader"><?php  echo t('Template Type'); ?></td>
			</tr>
			<tr>
				<td><input name="templateName" type="text" style="width:90%;" value="<?php  echo $loadedTemplate['templateName']; ?>" /></td>
				<td>
				<select name="fID">
							<option value=""></option>
							<?php  foreach($forms as $form) { ?>
								<option value="<?php  echo $form->fID; ?>" <?php  if ($loadedTemplate['fID'] == $form->fID) { echo 'selected="selected"'; } ?>><?php  echo $form->properties['name']; ?></option>
							<?php     } ?>
						</select>
				</td>
				<td><strong><?php  echo ucwords($loadedTemplate['templateType']); ?></strong></td>
			</tr>
			<tr>
				<td colspan="3" class="subheader"><?php  echo t('Content'); ?></td>
			</tr>
			<tr> 
				<td colspan="3">
					<div style="float:left">
						<select id="phContentSelect">
							<option value="">---</option>
						<?php     foreach($placeholders as $ph) {
							echo '<option value="' . $ph['code'] . '">' . $ph['title'] . '</option>';
						} ?>
						</select>
						<input type="button" onclick="templateContentEditor.replaceSelection($('#phContentSelect').val());" value="<?php  echo t('Insert'); ?>" />&nbsp;&nbsp;&nbsp;
						<a href="javascript:void(0)" onclick="ddEditorSitemapOverlay();dd_currentEditor='alternateContent';" class="dialog-launch"><?php  echo t('Insert Path to Page'); ?></a>&nbsp;&nbsp;&nbsp;
						<a id="content-file-selector" href="<?php  echo REL_DIR_FILES_TOOLS_REQUIRED?>/files/search_dialog?search=1" onclick="ccm_editorCurrentAuxTool='file';dd_currentEditor='content';" class="dialog-launch" dialog-modal="false"><?php  echo t('Insert Path to File'); ?></a>&nbsp;&nbsp;&nbsp;
						<a id="content-image-selector" href="<?php  echo REL_DIR_FILES_TOOLS_REQUIRED?>/files/search_dialog?search=1" onclick="ccm_editorCurrentAuxTool='image';dd_currentEditor='content';" class="dialog-launch" dialog-modal="false"><?php  echo t('Insert Image'); ?></a>
					</div>
					<div style="float:right">
						<input type="button" value="<?php  echo t('Validate'); ?>" onclick="w3validate('http://validator.w3.org/check?uri=<?php  echo urlencode(BASE_URL . $uh->getToolsURL('templateContent','sixeightdatadisplay') . '?tID=' . $loadedTemplate['tID']); ?>')">
					</div>
				</td>
			</tr>
			<tr>
				<td colspan="3"><textarea id="templateContent" name="templateContent" style="width:100%;height:200px;"><?php  echo htmlspecialchars($loadedTemplate['templateContent']); ?></textarea></td>
			</tr>
			<tr>
				<td colspan="3" class="subheader"><?php  echo t('Empty (Used when no results are found)'); ?></td>
			</tr>
			<tr>
				<td colspan="3"><textarea id="templateEmpty" name="templateEmpty" style="width:100%;height:200px;"><?php  echo htmlspecialchars($loadedTemplate['templateEmpty']); ?></textarea></td>
			</tr>
		</table>
		<?php  echo $h->button_js( t('Save'), 'saveTemplate()','left','primary'); ?> 
		<?php  echo $h->button_js( t('Cancel'), 'closeEditor()','left'); ?>
		<div style="clear:both"></div>
		</form>
		<script type="text/javascript">
		function saveTemplate() {
			if($('#templateName').val() == '') {
				alert('<?php  echo t('Please enter a name for the template'); ?>');
				return false;
			} else {
				$('#templateContent').val(templateContentEditor.getCode());
				$('#templateEmpty').val(templateEmptyEditor.getCode());
				$('#save-template-form').submit();
			}
		}
		var templateContentEditor = CodeMirror.fromTextArea('templateContent', {
			height: "200px",
			parserfile: ["parsexml.js", "parsecss.js", "tokenizejavascript.js", "parsejavascript.js","parsehtmlmixed.js"],
			stylesheet: [CCM_REL + "/packages/sixeightdatadisplay/css/xmlcolors.css", CCM_REL + "/packages/sixeightdatadisplay/css/jscolors.css", CCM_REL + "/packages/sixeightdatadisplay/css/csscolors.css"],
			path: CCM_REL + '/packages/sixeightdatadisplay/js/',
			tabMode: "shift",
			indentUnit: 4
		});
		var templateEmptyEditor = CodeMirror.fromTextArea('templateEmpty', {
			height: "200px",
			parserfile: ["parsexml.js", "parsecss.js", "tokenizejavascript.js", "parsejavascript.js","parsehtmlmixed.js"],
			stylesheet: [CCM_REL + "/packages/sixeightdatadisplay/css/xmlcolors.css", CCM_REL + "/packages/sixeightdatadisplay/css/jscolors.css", CCM_REL + "/packages/sixeightdatadisplay/css/csscolors.css"],
			path: CCM_REL + '/packages/sixeightdatadisplay/js/',
			tabMode: "shift",
			indentUnit: 4
		});
		</script>
	<?php  } ?>

<?php  } //End template editor ?>