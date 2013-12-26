<?php    
defined('C5_EXECUTE') or die(_("Access Denied."));
$form = Loader::helper('form');
$uh = Loader::helper('concrete/urls');
?>

<?php    
if($isReady) {
$f = sixeightForm::getByID($fID);
$fields = $f->getFields();
?>
<script type="text/javascript">
	$('.edit-answer-link').click(function (e) {
		e.preventDefault();
		var href = $(this).attr('href');
		$.fn.dialog.open({
			width: 500,
			height: 390,
			modal: false,
			href: href,
			title: '<?php  echo t('Edit Record'); ?>'			
		});
	});

	$('#fID').change(function() {
		$('#sem-loading-indicator').show();
		var fID = $(this).val();
		var newOptions = '';
		$.ajax({
			type:'GET',
			url: '<?php  echo $uh->getToolsURL('getFormFieldsJSON','sixeightdatadisplay') . '?fID='; ?>' + fID,
			dataType: 'json',
			success: function(fields) {
				$.each(fields, function(i,field) {
					newOptions += '<option value="' + field.ffID + '">' + field.label + '</option>';
				});
				$('#defaultFilterField').html('<option value="0">---</option>' + newOptions);
				$('#sortBy').html('<option value="0">Timestamp</option>' + newOptions);
				$('#sortableFields').html(newOptions);
				$('#sample-field').html('<option value="">---</option>' + newOptions);
			}
		});
	});
	
	$('#sample-field').change(function() {
		$('#sem-loading-indicator').show();
		var sampleData = '<table cellpadding="6" cellspacing="0" border="0" width="100%">';
		$.ajax({
			type:'GET',
			url: '<?php  echo $uh->getToolsURL('getFormDataSamples','sixeightdatadisplay') . '?fID='; ?>' + $('#fID').val() + '&ffID=' + $(this).val(),
			dataType: 'json',
			success: function(records) {
				$.each(records, function(i,record) {
					sampleData += '<tr><td>' + record.sample.value + '</td><td style="text-align:right;"><a href="<?php  echo $uh->getToolsURL('edit_answerset','sixeightforms'); ?>?asID=' + record.asID + '&editCode=' + record.editCode + '" class="edit-answer-link">Edit</a></tr>';
				});
				sampleData += '</table>';
				$('#sample-data').html(sampleData);
				$('.edit-answer-link').bind('click',function(e) {
					e.preventDefault();
					var href = $(this).attr('href');
					$.fn.dialog.open({
						width: 500,
						height: 390,
						modal: false,
						href: href,
						title: '<?php  echo t('Edit Record'); ?>'			
					});
				});
				$('#sem-loading-indicator').hide();
			}
		});
	});
	
	$('#add-filter-link').click(function(e) {
		e.preventDefault();
		$('#filtersTable tr.filter-row').clone().removeClass('filter-row').appendTo('#filtersTable').show();

	});
	
	function editAnswer(e) {
		e.preventDefault();
		var href = $(this).attr('href');
		$.fn.dialog.open({
			width: 500,
			height: 390,
			modal: false,
			href: href,
			title: '<?php  echo t('Edit Record'); ?>'			
		});
	}
	
	function removeFilter(element) {
		$(element).parent().parent().remove();
	}

</script>
<ul id="ccm-datadisplay-tabs" class="ccm-dialog-tabs">
	<li class="ccm-nav-active"><a id="ccm-datadisplay-tab-general" href="javascript:void(0);"><?php  echo t('General')?></a></li>
	<li class=""><a id="ccm-datadisplay-tab-searching"  href="javascript:void(0);"><?php  echo t('Search/Filter')?></a></li>
	<li class=""><a id="ccm-datadisplay-tab-sorting"  href="javascript:void(0);"><?php  echo t('Sorting')?></a></li>
	<li class=""><a id="ccm-datadisplay-tab-data"  href="javascript:void(0);"><?php  echo t('Data')?></a></li>
</ul>
<div style="clear:both"></div>
<div class="ccm-ui">
<div class="ccm-datadisplayPane" id="ccm-datadisplayPane-general">
	<table cellpadding="2" cellspacing="1" border="0">
		<tr>
			<td width="170"><?php  echo t('Form');?></td>
			<td>
				<select name="fID" id="fID">
					<?php  foreach($forms as $f) { ?>
						<option value="<?php  echo $f->fID; ?>" <?php  if ($fID == $f->fID) { echo 'selected="selected"'; } ?>><?php  echo $f->properties['name']; ?></option>
					<?php  } ?>
				</select>
			</td>
		</tr>
		<tr>
			<td><?php  echo t('List template');?></td>
			<td>
				<select name="listTemplateID">
				<?php    
				foreach($listTemplates as $listTemplate) {
					echo '<option value="' . $listTemplate['tID'] . '" ';
					if ($listTemplateID == $listTemplate['tID']) {
						echo 'selected="selected"';
					}
					echo ' >' . $listTemplate['templateName'] . '</option>';
				}
				?>
				</select>
			</td>
		</tr>
		<tr>
			<td><?php  echo t('Detail template');?></td>
			<td>
				<select name="detailTemplateID">
				<?php    
				foreach($detailTemplates as $detailTemplate) {
					echo '<option value="' . $detailTemplate['tID'] . '" ';
					if ($detailTemplateID == $detailTemplate['tID']) {
						echo 'selected="selected"';
					}
					echo ' >' . $detailTemplate['templateName'] . '</option>';
				}
				?>
				</select>
			</td>
		</tr>
		<tr>
			<td><?php  echo t('Show expired records?');?></td>
			<td><input name="showExpiredRecords" type="checkbox" value="1" <?php  if($showExpiredRecords==1) { echo 'checked="checked"'; } ?> /></td>
		</tr>
		<tr>
			<td><?php  echo t('Show approved records only?');?></td>
			<td><input name="approvedOnly" type="checkbox" value="1" <?php  if($approvedOnly==1) { echo 'checked="checked"'; } ?> /></td>
		</tr>
		<tr>
			<td><?php  echo t('Limit to records owned by user?');?></td>
			<td><input name="showOwnedRecords" type="checkbox" value="1" <?php  if($showOwnedRecords==1) { echo 'checked="checked"'; } ?> /></td>
		</tr>
		<tr>
			<td><?php  echo t('Items per page');?><div class="ccm-note"><?php  echo t('Enter a zero for unlimited');?></div></td>
			<td><input type="text" name="itemsPerPage" size="3" value="<?php  echo $itemsPerPage; ?>" /></td>
		</tr>
		<tr>
			<td><?php  echo t('Show paginator?');?></td>
			<td><input name="displayPaginator" type="checkbox" value="1" <?php  if($displayPaginator==1) { echo 'checked="checked"'; } ?> /></td>
		</tr>
		<tr>
			<td><?php  echo t('Display details on');?></td>
			<td>
				<select name="detailsInline">
                	<?php    
					if (($detailsInline == 1) || ($detailsInline == '')) {
						$detailsInline = 1;
					} else {
						$detailsInline = 0;
					}
					?>
					<option value="1" <?php  if($detailsInline==1) { echo 'selected="selected"'; } ?>><?php  echo t('This page'); ?></option>
					<option value="0" <?php  if($detailsInline==0) { echo 'selected="selected"'; } ?>><?php  echo t('The page specified below'); ?></option>
				</select>
			</td>
		</tr>
		<tr>
			<td><?php  echo t('Detail page');?><div class="ccm-note"><?php  echo t('The Data Display block must exist on the selected page');?></div></td>
			<td>
				<?php    
				$form = Loader::helper('form/page_selector');
				print $form->selectPage('detailPage', $detailPage);
				?>
			</td>
		</tr>
	</table>
</div>
<div class="ccm-datadisplayPane" id="ccm-datadisplayPane-searching" style="display:none">
	<table cellpadding="2" cellspacing="1" border="0" width="100%">
		<tr>
			<td colspan="2"><h4><?php  echo t('Search Options'); ?></h4></td>
		</tr>
		<tr>
			<td width="170"><?php  echo t('Display search form?');?></td>
			<td><input name="enableSearch" type="checkbox" value="1" <?php  if($enableSearch==1) { echo 'checked="checked"'; } ?> /></td>
		</tr>
		<tr>
			<td width="170"><?php  echo t('Display search reset button?');?></td>
			<td><input name="enableSearchReset" type="checkbox" value="1" <?php  if($enableSearchReset==1) { echo 'checked="checked"'; } ?> /></td>
		</tr>
		<tr>
			<td width="170"><?php  echo t('Search box placeholder');?></td>
			<td><input type="text" value="<?php  echo $searchPlaceholder; ?>" name="searchPlaceholder" style="width:200px" /></td>
		</tr>
		<tr>
			<td width="170"><?php  echo t('Search button label');?></td>
			<td><input type="text" value="<?php  echo $searchButtonText; ?>" name="searchButtonText" style="width:200px" /></td>
		</tr>
		<tr>
			<td colspan="2"><h4><?php  echo t('Filter Options'); ?></h4></td>
		</tr>
		<tr>
			<td colspan="2">
				<table id="filtersTable" style="width:100%">
					<tr>
						<td><?php  echo t('Field'); ?></td>
						<td><?php  echo t('Value'); ?></td>
						<td></td>
					</tr>
					<tr class="filter-row" style="display:none">
						<td>
							<select name="filterField[]" style="max-width:250px">
								<option value="0">---</option>
								<?php  foreach($fields as $field){ 
									echo '<option value="' . $field->ffID . '" ';
									if ($defaultFilterField == $field->ffID) {
										echo 'selected="selected"';
									}
									echo ' >' . $field->shortLabel . '</option>';
								} ?>
							</select>
						</td>
						<td><input name="filterValue[]" /></td>
						<td><a onclick="removeFilter(this);" href="javascript:void(0);"><?php  echo t('Remove'); ?></a></td>
					</tr>
					
					<?php  
					$filterFields = explode(',',$defaultFilterField);
					$filterValues = explode(',',$defaultFilterValue);
					?>
					<?php  if(count($filterFields) > 0) { ?>
					<?php  $i = 0; ?>
					<?php  foreach($filterFields as $filterField) { ?>
					<?php  if(intval($filterField) != 0) { ?>
					<tr>
						<td>
							<select name="filterField[]" style="max-width:250px">
								<option value="0">---</option>
								<?php  foreach($fields as $field){ 
									echo '<option value="' . $field->ffID . '" ';
									if ($filterField == $field->ffID) {
										echo 'selected="selected"';
									}
									echo ' >' . $field->shortLabel . '</option>';
								} ?>
							</select>
						</td>
						<td><input name="filterValue[]" value="<?php  echo htmlentities($filterValues[$i]); ?>" /></td>
						<td><a onclick="removeFilter(this);" href="javascript:void(0);"><?php  echo t('Remove'); ?></a></td>
					</tr>
					<?php  } ?>
					<?php  $i++; ?>
					<?php  } ?>
					<?php  } ?>
				</table>
				<a href="javascript:void(0);" id="add-filter-link"><?php  echo t('Add a Filter'); ?></a>
			</td>
		</tr>
	</table>
</div>
<div class="ccm-datadisplayPane" id="ccm-datadisplayPane-sorting" style="display:none">
	<table cellpadding="2" cellspacing="1" border="0" width="100%">
		<tr><td colspan="2"><h2>Sorting</h2></td></tr>
		<tr>
			<td width="170"><?php  echo t('Default sort field');?></td>
			<td>
				<select id="sortBy" name="sortBy" style="max-width:250px;">
				<option value="0">Timestamp</option>
				<?php     
					foreach($fields as $field){ 
					echo '<option value="' . $field->ffID . '" ';
					if ($sortBy == $field->ffID) {
						echo 'selected="selected"';
					}
					echo ' >' . $field->shortLabel . '</option>';
				} ?>
				</select>
			</td>
		</tr>
		<tr>
			<td><?php  echo t('Default sort order');?></td>
			<td>
				<select name="sortOrder">
					<option value="ASC" <?php  if($sortOrder=='ASC') { echo 'selected="selected"'; } ?>>Ascending</option>
					<option value="DESC" <?php  if($sortOrder=='DESC') { echo 'selected="selected"'; } ?>>Descending</option>
					<option value="RAND" <?php  if($sortOrder=='RAND') { echo 'selected="selected"'; } ?>>Random Order</option>
				</select>
			</td>
		</tr>
		<tr>
			<td><?php  echo t('Enable user sort');?></td>
			<td><input name="enableUserSort" type="checkbox" value="1"  <?php  if($enableUserSort==1) { echo 'checked="checked"'; } ?> /></td>
		</tr>
		<tr>
			<td><?php  echo t('User sortable fields');?></td>
			<td>
				<select id="sortableFields" name="sortableFields[]" size="7" multiple="multiple" style="max-width:250px">
				<?php     
					foreach($fields as $field){ 
					echo '<option value="' . $field->ffID . '" ';
					if ($controller->fieldIsSortable($field->ffID,$sortableFields)) {
						echo 'selected="selected"';
					}
					echo ' >' . $field->shortLabel . '</option>';
				} ?>
				</select>
			</td>
		</tr>
		<tr>
			<td width="170"><?php  echo t('Sort Form Intro Text');?></td>
			<td><input type="text" value="<?php  echo $sortLabel; ?>" name="sortLabel" style="width:200px" /></td>
		</tr>
		<tr>
			<td width="170"><?php  echo t('Sort Button Label');?></td>
			<td><input type="text" value="<?php  echo $sortButtonLabel; ?>" name="sortButtonLabel" style="width:200px" /></td>
		</tr>
	</table>
</div>

<div class="ccm-datadisplayPane" id="ccm-datadisplayPane-data" style="display:none">
	<h2>Edit Data</h2>
	Sample Field: 
	<select id="sample-field">
		<option value="">---</option>
		<?php  foreach($fields as $field){ 
			echo '<option value="' . $field->ffID . '">' . $field->shortLabel . '</option>';
		} ?>
	</select>
	<span id="sem-loading-indicator" style="display:none"><img src="<?php  echo DIR_REL; ?>/concrete/images/throbber_white_16.gif" /></span>
	<div id="sample-data">
		<table cellpadding="2" cellspacing="1" border="0" width="100%" id="sample-data"></table>
	</div>
</div>
</div>
<?php  } else { ?>
<h2>Block Not Ready</h2>
<p>In order to use the Data Display block, you must first create at least one form, one list template and one detail template on <a href="<?php  echo View::url('/dashboard/sixeightdatadisplay/') ?>">Data Display</a> pages in the Dashboard.</p>

<?php  } ?>