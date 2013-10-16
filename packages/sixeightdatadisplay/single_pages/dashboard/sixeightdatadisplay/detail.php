<?php    
$txt = Loader::helper('text');
$h = Loader::helper('concrete/interface');
$uh = Loader::helper('concrete/urls');
$form = Loader::helper('form');
$this->addHeaderItem($html->javascript('codemirror.js', 'sixeightdatadisplay'));
?>
<script type="text/javascript">
function validateNewTemplate() {
	if($('#newTemplateName').val()=='') {
		alert('<?php  echo t('Please enter a template name'); ?>');
		return false;
	} else if($('#newTemplateFID').val()=='') {
		alert('<?php  echo t('Please select a form to use with the template.'); ?>');
		return false;
	} else {
		$('#new-template-form').submit();
	}
}

function loadSelectedTemplate() {
	if(!$('#selectedTemplate').val()) {
		alert('<?php  echo t('Please select a template.'); ?>');
		return false;
	} else {
		$('#load-template-form').submit();
	}
}

function duplicateSelectedTemplate() {
	if(!$('#selectedTemplate').val()) {
		alert('<?php  echo t('Please select a template.'); ?>');
		return false;
	}
	var newTemplateName = prompt("<?php  echo t('Give your new template a name:'); ?>", "");
	if(newTemplateName) {
		location.href = '<?php  echo $this->url("/dashboard/sixeightdatadisplay/detail/-/duplicateTemplate") ?>?tID=' + $('#selectedTemplate').val() + '&name=' + newTemplateName;
	} else {
		alert('<?php  echo t('Error: You must give your template a name.'); ?>');	
	}
}

function deleteSelectedTemplate() {
	if(!$('#selectedTemplate').val()) {
		alert('<?php  echo t('Please select a template.'); ?>');
		return false;
	} else {
		if(confirm("<?php  echo t('Are you sure you want to delete this template?  This action cannot be undone!').'\n' ?>")) {
			$('#load-template-form').attr('action','<?php  echo $this->url($c->getCollectionPath(),'deleteTemplate'); ?>').submit();
		} else {
			return false;
		}
	}
}

function saveTemplate() {
	if($('#templateName').val() == '') {
		alert('<?php  echo t('Please enter a name for the template'); ?>');
		return false;
	} else {
		$('#templateContent').val(templateContentEditor.getCode());
		$('#save-template-form').submit();
	}
}

function closeEditor() {
	if(confirm('<?php  echo t('Are you sure you want to close the editor?'); ?>\n\n<?php  echo t('Click "Cancel" to continue editing.'); ?>')) {
		location.href = '<?php  echo $this->url("/dashboard/sixeightdatadisplay/detail") ?>';
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
	templateContentEditor.replaceSelection(url);
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
	
	templateContentEditor.replaceSelection(code);
}

$(document).ready(function() {
	 $("#content-file-selector").dialog({ width: 900, title: '<?php  echo t("Add File"); ?>' }); 
	 $("#content-image-selector").dialog({ width: 900, title: '<?php  echo t("Add Image"); ?>' }); 
});

</script>

<?php    
if(!isset($loadedTemplate)) {
?>
<h1><span><?php     echo t('Create Detail Template')?></span></h1>
<div class="ccm-dashboard-inner">
	<form id="new-template-form" action="<?php  echo $this->url($c->getCollectionPath(),'createTemplate')?>" method="get">
		<table cellpadding="8" cellspacing="0" border="0">
			<tr>
				<td>
					<div><?php  echo t('Template Name'); ?></div>
					<input id="newTemplateName" name="templateName" type="text" size="25" />
				</td>
				<td>
					<div><?php  echo t('Form to use with template'); ?></div>
					<select id="newTemplateFID" name="fID">
						<option value="">---</option>
						<?php  foreach($forms as $form) { ?>
							<option value="<?php  echo $form->fID; ?>" <?php  if ($questionSet == $form->fID) { echo 'selected="selected"'; } ?>><?php  echo $form->properties['name']; ?></option>
						<?php  } ?>
					</select>
				</td>
				<td>
					<?php    
					echo $h->button_js( t('Create Template'), 'validateNewTemplate()','left');
					?>
				</td>
			</tr>
		</table>
	</form>
</div>

<h1><span><?php     echo t('Manage Detail Templates') ?></span></h1>
<div class="ccm-dashboard-inner">
	<?php    
	if( !count($detailTemplates) ){
		echo t('You have not yet created any detail templates.');
	} else {
		echo '<form id="load-template-form" action="' . $this->url($c->getCollectionPath(),'getTemplate') . '" method="get">';
		echo '<table cellpadding="8" cellspacing="0" border="0">';
		echo '<tr><td>';
		echo t('Select a Template');
		echo '<br /><select id="selectedTemplate" name="tID"><option value="">---</option>';
		foreach($detailTemplates as $template) {
			echo '<option value="' . $template['tID'] . '">' . $template['templateName'] . '</option>';
		}
		echo '</select>';
		echo '</td><td>';
		echo $h->button_js( t('Load Template'), 'loadSelectedTemplate()','left');
		echo $h->button_js( t('Duplicate Template'), 'duplicateSelectedTemplate()','left');
		echo $h->button_js( t('Delete Template'), 'deleteSelectedTemplate()','left');
		echo '</td></tr></table>';
		echo '</form><br /><br />';
	}
	?>
</div>
<?php    	
} else {
?>
<h1><span><?php  echo t('Edit Detail Template'); ?></span></h1>
<div class="ccm-dashboard-inner">
	<form id="save-template-form" action="<?php  echo $this->url($c->getCollectionPath(),'saveTemplate')?>" method="post">
	<input type="hidden" name="tID" value="<?php  echo $loadedTemplate['tID']; ?>" />
	<table class="entry-form" border="0" cellspacing="1" cellpadding="0">
		<tr>
			<td class="subheader" width="50%"><?php  echo t('Template Name'); ?></td>
			<td class="subheader"><?php  echo t('Associated Form'); ?></td>
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
		</tr>
		<tr>
			<td colspan="2" class="subheader"><?php  echo t('Content'); ?></td>
		</tr>
		<tr> 
			<td colspan="2">
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
			<td colspan="2"><textarea id="templateContent" name="templateContent" style="width:100%;height:200px;"><?php  echo htmlspecialchars($loadedTemplate['templateContent']); ?></textarea></td>
		</tr>
	</table>
	<?php    
		echo $h->button_js( t('Save'), 'saveTemplate()','left');
		echo $h->button_js( t('Cancel'), 'closeEditor()','left');
	?>
	<div style="clear:both"></div>
	</form>
</div>
<script type="text/javascript">
var templateContentEditor = CodeMirror.fromTextArea('templateContent', {
	height: "200px",
	parserfile: ["parsexml.js", "parsecss.js", "tokenizejavascript.js", "parsejavascript.js","parsehtmlmixed.js"],
	stylesheet: [CCM_REL + "/packages/sixeightdatadisplay/css/xmlcolors.css", CCM_REL + "/packages/sixeightdatadisplay/css/jscolors.css", CCM_REL + "/packages/sixeightdatadisplay/css/csscolors.css"],
	path: CCM_REL + '/packages/sixeightdatadisplay/js/',
	tabMode: "shift",
	indentUnit: 4
});
</script>
<?php    
	}
?>