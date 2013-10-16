<?php  
$h = Loader::helper('concrete/interface');
$uh = Loader::helper('concrete/urls');
?>
<script type="text/javascript">
function createStyle() {
	if($('#newStyleName').val() != '') {
		$('#new-style-form').submit();
	} else {
		alert('Please specify a style name.');
	}
}

function deleteStyle(sID) {
	if(confirm("<?php  echo t('Are you sure you want to delete this style? This action cannot be undone!').'\n' ?>")) {
		location.href = '<?php  echo $this->url($c->getCollectionPath(),'deleteStyle')?>?sID=' + sID;
	}
}

function saveSelector(ssID) {
	$('#changed-indicator-' + ssID).show().html('<span><img src="<?php  echo DIR_REL; ?>/concrete/images/throbber_white_16.gif" /></span>');
	$.ajax({
		url:'<?php  echo $uh->getToolsURL("save_selector","sixeightforms"); ?>?ssID=' + ssID,
		type:'post',
		data:'ssID=' + ssID + '&css=' + $('#selector-css-' + ssID).val(),
		success:function(data) {
			$('#changed-indicator-' + ssID).stop().html('Saved!').delay(1000).fadeOut('fast',function() {
				$(this).html('*');
			});
		}
	});
}

</script>
<?php  if($_GET['sID'] == '') { ?>
<?php  echo Loader::helper('concrete/dashboard')->getDashboardPaneHeaderWrapper(t('Styles'), false);?>
	<?php  echo $h->button_js( t('Return to Form List'), 'window.location = \'' . $this->url("/dashboard/sixeightforms/forms") . '\'','right','success'); ?>
	<form id="new-style-form" action="<?php  echo $this->url($c->getCollectionPath(),'createStyle')?>" method="post">
		<h3><?php  echo t('Create a New Style'); ?></h3>
		<input id="newStyleName" name="name" type="text" size="25" value="Name" onfocus="if(this.value=='Name') { this.value=''; }" /> <?php  echo $h->button_js( t('Create Style'), 'createStyle()','left'); ?><br />
		<input type="checkbox" name="useTemplate" value="1" checked="checked" /> <?php  echo t('Use default form style as template'); ?>
	</form>

	<h3><span><?php  echo t('Existing Styles'); ?></span></h3>
	<?php  if(is_array($styles)) { ?>
	<table cellpadding="8" cellspacing="0" border="0" class="ccm-results-list">
		<?php  foreach($styles as $style) { ?>
			<tr>
				<td><?php  echo $style->name; ?></td>
				<td style="width:100px"><?php  echo $h->button( t('Edit Style'),$this->url($c->getCollectionPath(),'loadStyle?sID=' . $style->sID),'left'); ?></td>
				<td style="width:120px"><?php  echo $h->button( t('Duplicate Style'),$this->url($c->getCollectionPath(),'duplicateStyle?sID=' . $style->sID),'left'); ?></td>
				<td style="width:120px"><?php  echo $h->button_js( t('Delete Style'), 'deleteStyle(' . $style->sID . ')','left','error'); ?></td>
			</tr>
		<?php  } ?>
	</table>
	<?php  } else { ?>
		<?php  echo t('Use the form above to create a new style.'); ?>
	<?php  } ?>

<?php  } else { ?>
<?php  echo Loader::helper('concrete/dashboard')->getDashboardPaneHeaderWrapper(t('Edit Style'), false);?>
	<?php  echo $h->button_js( t('Return to Styles'), 'window.location = \'' . $this->url("/dashboard/sixeightforms/styles") . '\'','right','success'); ?>
	<h2><?php  echo $style->name; ?></h2>
	<div style="clear:both"></div>
	<hr />
	<form id="style-form" action="<?php  echo $this->url($c->getCollectionPath(),'saveStyle')?>" method="post">
	<input type="hidden" name="sID" value="<?php  echo intval($_GET['sID']); ?>" />
	<?php  if(is_array($style->selectors)) { ?>
		<?php  foreach($style->selectors as $selector) { ?>
			<table class="entry-form" border="0" cellspacing="1" cellpadding="0" width="100%">
				<tr>
					<td colspan="2" class="subheader">
						<?php  echo $h->button_js(t('Save'), 'saveSelector(' . $selector['ssID'] . ');','right','primary'); ?>
						<h4><?php  echo $selector['name']; ?> <span id="changed-indicator-<?php  echo $selector['ssID']; ?>" style="display:none;color:#ff0000">*</span></h4>
						<div class="help-block"><?php  echo $selector['description']; ?></div>
					</td>
				</tr>
				<tr>
					<td><textarea onkeyup="$('#changed-indicator-<?php  echo $selector['ssID']; ?>').show();" style="width:100%;height:200px" name="selector-css[<?php  echo $selector['ssID']; ?>]" id="selector-css-<?php  echo $selector['ssID']; ?>"><?php  echo $selector['css']; ?></textarea></td>
				</tr>
			</table>
		<?php  } ?>
	<?php  } ?>
	<?php  echo $h->button_js(t('Save All'), "$('#style-form').submit();",'left'); ?>
	<div style="clear:both"></div>
	</form>
<?php  } ?>