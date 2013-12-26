<?php  
$h = Loader::helper('concrete/interface');
$uh = Loader::helper('concrete/urls');
?>
<style type="text/css">
#fields-table {
	width:100%;
	font-size:11px;
}

.drag-row td {
	background-color:#ddd;
}

.sem-textarea {
	font-size:12px;
}
</style>
<script type="text/javascript">

$(document).ready(function() {
	$('#fields-table').tableDnD({
		onDragClass: 'drag-row',
		dragHandle: 'drag-handle',
		onDrop: function () {
			saveSortOrder();
		}
	});
});

function saveSortOrder() {
	var formData = $('#field-sort-form').serialize();
	$.ajax({
		type:'POST',
		url: '<?php  echo $uh->getToolsURL('savefieldsort','sixeightforms'); ?>',
		data: formData,
		success: function(msg) {	
		}
	});
	return false;
}

function validateFormName() {
	$('#newFormForm').submit();
}

function confirmDelete(fID) {
	if(confirm('<?php  echo t('Are you sure you want to delete this form and all of its responses?'); ?>')) {
		return true;
	} else {
		return false;
	}
}

function confirmDeleteField(ffID) {
	if(confirm('<?php  echo t('Are you sure you want to delete this field?'); ?>')) {
		window.location = '<?php  echo $this->url($c->getCollectionPath(),'deleteField'); ?>?ffID=' + ffID + '&fID=' + <?php  echo intval($_GET['fID']); ?>;
	} else {
		return false;
	}
}

function confirmDeleteAnswerSet(asID) {
	if(confirm('<?php  echo t('Are you sure you want to delete this record?'); ?>')) {
		window.location = '<?php  echo $this->url($c->getCollectionPath(),'deleteAnswerSet'); ?>?fID=<?php  echo intval($_GET['fID']); ?>&asID=' + asID;
	} else {
		return false;
	}
}

function startNewForm() {
	$.fn.dialog.open({
		width: 400,
		height: 150,
		modal: false,
		href: '<?php  echo $uh->getToolsURL('new_form','sixeightforms'); ?>',
		title: '<?php  echo t('New Form'); ?>'			
	});
}

function addField() {
	$.fn.dialog.open({
		width: 600,
		height: 350,
		modal: false,
		href: '<?php  echo $uh->getToolsURL('new_field?fID=' . $_GET['fID'],'sixeightforms'); ?>',
		title: '<?php  echo t('Add Field'); ?>'
	});
}

function editField(ffID) {
	$.fn.dialog.open({
		width: 600,
		height: 350,
		modal: false,
		href: '<?php  echo $uh->getToolsURL('new_field?fID=' . $_GET['fID'],'sixeightforms'); ?>&ffID=' + ffID,
		title: '<?php  echo t('Edit Field'); ?>'
	});
}

function setNotifications(fID) {
	var href = '<?php  echo $uh->getToolsURL('notifications','sixeightforms') . '?fID='; ?>' + fID;
	$.fn.dialog.open({
		width: 500,
		height: 390,
		modal: false,
		href: href,
		title: '<?php  echo t('Notifications'); ?>'			
	});
}

function setPermissions(fID) {
	var href = '<?php  echo $uh->getToolsURL('permissions','sixeightforms') . '?fID='; ?>' + fID;
	$.fn.dialog.open({
		width: 500,
		height: 390,
		modal: false,
		href: href,
		title: '<?php  echo t('Permissions'); ?>'			
	});
}

function manageRules(fID) {
	var href = '<?php  echo $uh->getToolsURL('rules','sixeightforms') . '?fID='; ?>' + fID;
	$.fn.dialog.open({
		width: 500,
		height: 390,
		modal: false,
		href: href,
		title: '<?php  echo t('Rules'); ?>'			
	});
}

function deleteForm(fID) {
	if(confirmDelete(fID)) {
		$.ajax({
			url:'<?php  echo $uh->getToolsURL("delete_form","sixeightforms"); ?>?fID=' + fID,
			success:function(data) {
				$('#sem-form-list-item-' + fID).fadeOut();
			}
		});
	}
}

$(document).ready(function() {
	$('.ccm-results-list tr:even').addClass('ccm-list-record-alt');
	
	$('.answer-detail-link').click(function (e) {
		e.preventDefault();
		var href = $(this).attr('href');
		$.fn.dialog.open({
			width: 500,
			height: 390,
			modal: false,
			href: href,
			title: '<?php  echo t('Answer Details'); ?>'			
		});
	});
	
	$('.send-notification-link').click(function(e) {
		e.preventDefault();
		var href = $(this).attr('href');
		$.fn.dialog.open({
			width: 300,
			height: 50,
			modal: false,
			href: href,
			title: '<?php  echo t('Send Notification'); ?>'			
		});
	});
	
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
	
	$('.settings-link').click(function (e) {
		e.preventDefault();
		var href = $(this).attr('href');
		$.fn.dialog.open({
			width: 500,
			height: 390,
			modal: false,
			href: href,
			title: '<?php  echo t('Edit Form'); ?>'			
		});
	});
	
	$('.approval-button').click(function(e) {
		$approvalButton = $(this);
		var asID = $approvalButton.attr('data-id');
		var email = $approvalButton.attr('data-email');
		if($approvalButton.hasClass('approval-button-pending')) {
			var status = 0;
		} else if($approvalButton.hasClass('approval-button-approve')) {
			var status = 1;
		} else {
			var status = 2;
		}
		
		e.preventDefault();
		$.ajax({
			url:'<?php  echo $uh->getToolsURL("update_approval","sixeightforms"); ?>?asID=' + asID + '&status=' + status,
			success:function(data) {
				<?php  if($f->properties['sendApprovalNotification'] == 1) { ?>
				if(email != '') {
					$.fn.dialog.open({
						width: 400,
						height: 350,
						modal: false,
						href: '<?php  echo $uh->getToolsURL('approval_email','sixeightforms'); ?>?asID=' + asID + '&status=' + status,
						title: '<?php  echo t('Send Approval Email'); ?>'
					});
				}
				<?php  } ?>
				if(status == 0) {
					$('#approval-button-' + asID).removeClass('success');
					$('#approval-button-' + asID).removeClass('error');
					
					$('i','#approval-button-' + asID).removeClass('icon-thumbs-up');
					$('i','#approval-button-' + asID).removeClass('icon-thumbs-down');
					$('i','#approval-button-' + asID).addClass('icon-question-sign');
					
					$('i','#approval-button-' + asID).removeClass('icon-white');
				} else if (status == 1) {
					$('#approval-button-' + asID).removeClass('error');
					$('#approval-button-' + asID).addClass('success');
					
					$('i','#approval-button-' + asID).removeClass('icon-question-sign');
					$('i','#approval-button-' + asID).removeClass('icon-thumbs-down');
					$('i','#approval-button-' + asID).addClass('icon-thumbs-up');
					
					$('i','#approval-button-' + asID).addClass('icon-white');
				} else {
					$('#approval-button-' + asID).removeClass('success');
					$('#approval-button-' + asID).addClass('error');
					
					$('i','#approval-button-' + asID).removeClass('icon-question-sign');
					$('i','#approval-button-' + asID).removeClass('icon-thumbs-up');
					$('i','#approval-button-' + asID).addClass('icon-thumbs-down');
					
					$('i','#approval-button-' + asID).addClass('icon-white');
				}
			}
		});
	});
	
	$('.required-button').tooltip();
	
	$('.searchable-button').tooltip();
	
	$('.exclude-from-email-button').tooltip();
	
	$('.alternate-options-button').tooltip();
	
	$('.required-button').click(function(e) {
		$requiredButton = $(this);
		$requiredButton.html('<img src="<?php  echo DIR_REL; ?>/concrete/images/throbber_white_16.gif" />');
		e.preventDefault();
		$.ajax({
			url:'<?php  echo $uh->getToolsURL("update_required","sixeightforms"); ?>?ffID=' + $requiredButton.attr('rel'),
			success:function(data) {
				if(data == '1') {
					$requiredButton.html('<img src="<?php  echo DIR_REL; ?>/packages/sixeightforms/images/accept.png" />');
				} else {
					$requiredButton.html('<img src="<?php  echo DIR_REL; ?>/packages/sixeightforms/images/accept-off.png" />');
				}
			}
		});
	});
	
	$('.searchable-button').click(function(e) {
		$searchableButton = $(this);
		$searchableButton.html('<img src="<?php  echo DIR_REL; ?>/concrete/images/throbber_white_16.gif" />');
		e.preventDefault();
		$.ajax({
			url:'<?php  echo $uh->getToolsURL("update_searchable","sixeightforms"); ?>?ffID=' + $searchableButton.attr('rel'),
			success:function(data) {
				if(data == '1') {
					$searchableButton.html('<img src="<?php  echo DIR_REL; ?>/packages/sixeightforms/images/search.png" />');
				} else {
					$searchableButton.html('<img src="<?php  echo DIR_REL; ?>/packages/sixeightforms/images/search-off.png" />');
				}
			}
		});
	});
	
	$('.exclude-from-email-button').click(function(e) {
		$excludeFromEmailButton = $(this);
		$excludeFromEmailButton.html('<img src="<?php  echo DIR_REL; ?>/concrete/images/throbber_white_16.gif" />');
		e.preventDefault();
		$.ajax({
			url:'<?php  echo $uh->getToolsURL("update_exclude_from_email","sixeightforms"); ?>?ffID=' + $excludeFromEmailButton.attr('rel'),
			success:function(data) {
				if(data == '0') {
					$excludeFromEmailButton.html('<img src="<?php  echo DIR_REL; ?>/packages/sixeightforms/images/email.png" />');
				} else {
					$excludeFromEmailButton.html('<img src="<?php  echo DIR_REL; ?>/packages/sixeightforms/images/email-off.png" />');
				}
			}
		});
	});
	
	$('.alternate-options-button').click(function(e) {
		e.preventDefault();
		$button = $(this);
		ffID = $button.attr('rel');
		$.fn.dialog.open({
			width: 400,
			height: 350,
			modal: false,
			href: '<?php  echo $uh->getToolsURL('alternate_options','sixeightforms'); ?>?ffID=' + ffID,
			title: '<?php  echo t('Alternate Options'); ?>'
		});
	});
	
	$('#results-action').change(function(e) {
		if(($(this).val() == 'export') || ($(this).val() == 'summary')) {
			$('#results-range').val('all');
			$('#results-range').attr('disabled',true);
		} else {
			$('#results-range').attr('disabled',false);
		}
	});
	
	$('#sem-answer-select-1').change(function() {
		var ffID = $(this).val();
		$('.sem-results-row').each(function() {
			$('.sem-answer-td-1', this).html($('.answer-' + ffID, this).html());
		});
	});
	
	$('.new-field-link a').dialog();
	
	$('.sem-quick-add').click(function(e) {
		e.stopPropagation();
		var type = $(this).parent().attr('rel');
		alert(type);
	});
	
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

	$('.reset-search-button').click(function(e) {
		e.preventDefault();
		window.location = '<?php  echo View::URL('/dashboard/sixeightforms/forms','results?fID=' . $_GET['fID']); ?>';
	});
		
	$('.form-name').click(function(e) {
		e.preventDefault();
		$(this).hide();
		var fID = $(this).attr('rel');
		$('#form-name-form-' + fID).show();
		$('#form-name-input-' + fID).focus();
	});
	
	$('.sem-form-name-input').blur(function(e) {
		e.preventDefault();
		$(this).parent().hide();
		$('.form-name').show();
	});
	
	$('.sem-form-name-form').submit(function(e) {
		e.preventDefault();
		var fID = $('.sem-form-name-id',this).val();
		var name = $('#form-name-input-' + fID).val();
		$(this).hide();
		$.ajax({
			url:'<?php  echo $uh->getToolsURL("update_form_name","sixeightforms"); ?>?fID=' + fID + '&name=' + encodeURIComponent(name),
			success:function() {
				if($.trim(name).length == 0) {
					$('#form-name-' + fID).html('<?php  echo t('Form '); ?>' + fID);
				} else {
					$('#form-name-' + fID).html(name);
				}
				$('#form-name-' + fID).show();
			}
		});
	});

});

var currentOwnerASID = 0;


	
function editSettings(fID) {
	var href = '<?php  echo $uh->getToolsURL('edit_form','sixeightforms') . '?fID='; ?>' + fID;
	$.fn.dialog.open({
		width: 600,
		height: 450,
		modal: false,
		href: href,
		title: '<?php  echo t('Edit Form'); ?>'			
	});
}

function addRecord(fID) {
	currentFID = <?php  echo intval($_GET['fID']); ?>;
	if(currentFID != 0) {
		fID = currentFID;
	}
	$.fn.dialog.open({
		width: 500,
		height: 390,
		modal: false,
		href: '<?php  echo $uh->getToolsURL('edit_answerset','sixeightforms'); ?>?fID=' + fID,
		title: 'Add Record'			
	});
}

function indexRecords() {
	window.location = '<?php  echo $this->url($c->getCollectionPath(),'results'); ?>?fID=<?php  echo $_GET['fID']; ?>&index=1';
}

function clearCache() {
	window.location = '<?php  echo $this->url($c->getCollectionPath(),'results'); ?>?fID=<?php  echo $_GET['fID']; ?>&clearCache=1';
}

function sortByField() {
	var fID = <?php  echo intval($_GET['fID']); ?>;
	var q = '<?php  echo htmlentities($_GET['q']); ?>';
	var sortOrder = '<?php  echo $newSortOrder; ?>';
	var sortffID = $('#sem-answer-select-1').val();
	window.location = '<?php  echo $this->url($c->getCollectionPath(),'results'); ?>?fID=' + fID + '&q=' + q + '&sortOrder=' + sortOrder + '&sortffID=' + sortffID;
}

function ccm_triggerSelectUser(uID, uName) {
	$.ajax({
		url:'<?php  echo $uh->getToolsURL("set_answer_set_owner","sixeightforms"); ?>?asID=' + currentOwnerASID + '&uID=' + uID,
		success:function(data) {
			$('#owner-link-' + currentOwnerASID).html(uName);
		}
	});
}

</script>
<?php  if ($action == 'results') { //Display form results ?>

<?php  echo Loader::helper('concrete/dashboard')->getDashboardPaneHeaderWrapper(t('Results'), false);?>
<div style="margin:10px 0;">
	<?php  echo $h->button_js( t('Add a Record'), 'addRecord();','left','primary'); ?>
	<?php  echo $h->button_js( t('Update Search Index'), 'indexRecords();','left'); ?>
	<?php  echo $h->button_js( t('Clear Cache'), 'clearCache();','left'); ?>
	<?php  echo $h->button_js( t('Return to Form List'), 'window.location = \'' . $this->url("/dashboard/sixeightforms/forms") . '\'','right','success'); ?>
	<div style="clear:both;text-align:center"></div>
</div>
<form class="ccm-ui" method="get" action="<?php  echo $this->url($c->getCollectionPath(),'results'); ?>">
	<input type="hidden" name="fID" value="<?php  echo $_GET['fID']; ?>" />
	<div id="ccm-user-search-advanced-fields" class="ccm-search-advanced-fields" >
		<h3><?php  echo t('Search'); ?></h3>
		<?php  if($f->properties['indexTimestamp']) { ?>
			<div class="help-block"><?php  echo t('Records last indexed on '); ?> <?php  echo date('F j, Y, g:i a',$f->properties['indexTimestamp']); ?></div>
			<div id="ccm-search-advanced-fields-inner">
				<div class="ccm-search-field">
					<input type="text" name="q" value="<?php  echo htmlspecialchars($_GET['q']); ?>" style="width:200px" style="width:200px" class="ccm-input-text" />
					<input type="submit" class="btn" value="<?php  echo t('Search'); ?>" />
					<input type="submit" class="btn" value="Reset Results">
				</div>
			</div>
		<?php  } else { ?>
			<?php  echo t('You must update the search index before you can search these records.'); ?>
		<?php  } ?>
		
	</div>
</form>
<form action="<?php  echo $this->url($c->getCollectionPath(),'processAnswerSets')?>" method="post">
<input type="hidden" name="fID" value="<?php  echo intval($_GET['fID']); ?>" />

<table cellpadding="8" cellspacing="0" border="0" class="ccm-results-list" id="sem-results-table">
	<tr>
		<th width="25" style="text-align:center"><input type="checkbox" onclick="$(this).is(':checked') ? $('.as-checkbox').attr('checked',true) : $('.as-checkbox').attr('checked',false);" /></th>
		<th width="200"><strong><a href="<?php  echo $this->url($c->getCollectionPath(),'results'); ?>?fID=<?php  echo intval($_GET['fID']); ?>&ffID=<?php  echo intval($_GET['ffID']); ?>&q=<?php  echo $_GET['q']; ?>&sortOrder=<?php  echo $newSortOrder; ?>"><?php  echo t('Date Submitted'); ?><img src="<?php  echo ASSETS_URL_IMAGES?>/icons/arrow_<?php  echo $arrowDirection; ?>.png" /></strong></th>
		<th class="sem-answer-th" id="sem-answer-th-1">
			<select id="sem-answer-select-1">
				<?php  foreach($fields as $field) { ?>
					<option <?php  if($field->ffID == $sortffID) { echo 'selected="selected"'; } ?> value="<?php  echo $field->ffID; ?>"><?php  echo $field->shortLabel; ?></option>
					<?php  $i++; ?>
				<?php  } ?>
			</select>
			<strong><a onclick="sortByField();" href="javascript:void(0);"><img src="<?php  echo ASSETS_URL_IMAGES?>/icons/arrow_<?php  echo $arrowDirection; ?>.png" /></a></strong>
		</th>
		<th width="50" style="text-align:center"><strong><?php  echo t('Status'); ?></strong></th>
		<th width="50" style="text-align:center"><strong><?php  echo t('Edit'); ?></strong></th>
		<th width="50" style="text-align:center"><strong><?php  echo t('Delete'); ?></strong></th>
	</tr>
	<?php   
	if(is_array($answerSets)) {
		foreach($answerSets as $as) {
	?>
	<tr class="ccm-list-record sem-results-row">
		<td style="text-align:center">
			<input type="checkbox" class="as-checkbox" name="sem-as[]" value="<?php  echo $as->asID; ?>" />
			<?php   foreach($fields as $field) { ?>
				<div class="answer-<?php  echo $field->ffID; ?>" style="display:none"><?php  echo $as->answers[$field->ffID]['shortValue']; ?></div>
			<?php   } ?>
		</td>
		<td><a class="answer-detail-link" href="<?php  echo $uh->getToolsURL('answer_detail','sixeightforms') . '?asID=' . intval($as->asID); ?>"><?php  echo date('F j, Y, g:i a',$as->dateSubmitted); ?></a></td>
		<td class="sem-answer-td-1"><?php  echo $as->answers[$fields[$sortffID]->ffID]['shortValue']; ?></td>
		<td>
			<?php  
				if($f->properties['sendApprovalNotification'] == 1) {
					if($asUI = UserInfo::getByID($as->creator)) {
						$asEmail = $asUI->getUserEmail();
					}
				} else {
					$asEmail = '';
				}
			?>
			<div class="btn-group">
			<?php  if($as->isApproved == 1) {
				$approvalClass = 'success';
			} elseif ($as->isApproved == 2) {
				$approvalClass = 'error';
			} else {
				$approvalClass = '';
			}
			?>
			  <a id="approval-button-<?php  echo $as->asID; ?>" class="btn dropdown-toggle <?php  echo $approvalClass; ?>" data-toggle="dropdown" href="#">
			  	<?php  if($as->isApproved == 1) { ?>
			  		<i class="icon-thumbs-up icon-white"></i>
			  	<?php   } elseif ($as->isApproved == 2) { ?>
			  		<i class="icon-thumbs-down icon-white"></i>
			  	<?php   } else { ?>
			   		<i class="icon-question-sign"></i>
			  	<?php  } ?>
			    <span class="caret"></span>
			  </a>
			  <ul class="dropdown-menu" style="padding:5px 10px">
			    <li><a data-id="<?php  echo $as->asID; ?>" data-email="<?php  echo $asEmail; ?>" class="approval-button approval-button-pending" href="#">Pending</a></li>
			    <li><a data-id="<?php  echo $as->asID; ?>" data-email="<?php  echo $asEmail; ?>" class="approval-button approval-button-approve" href="#">Approve</a></li>
			    <li><a data-id="<?php  echo $as->asID; ?>" data-email="<?php  echo $asEmail; ?>" class="approval-button approval-button-reject" href="#">Reject</a></li>
			  </ul>
			</div>
		</td>
		<td style="text-align:center">
			<?php  echo $h->button(t('Edit'),$uh->getToolsURL('edit_answerset','sixeightforms') . '?asID=' . intval($as->asID) . '&editCode=' . $as->editCode,'left','edit-answer-link'); ?>
		</td>
		<td style="text-align:center">
			<?php  echo $h->button_js(t('Delete'),'confirmDeleteAnswerSet(' . $as->asID . ')','left','error'); ?>
		</td>
	</tr>
	<?php   
		}
	} 
	?>
	<tr>
		<th colspan="10" style="text-align:center" valign="top">
			<div style="float:left">
				<select name="action" id="results-action">
					<option value="">---</option>
					<option value="delete"><?php  echo t('Delete'); ?></option>
					<option value="approve"><?php  echo t('Approve'); ?></option>
					<option value="export"><?php  echo t('Export Results'); ?></option>
					<option value="summary"><?php  echo t('Export Summary'); ?></option>
				</select>
				<select name="range" id="results-range">
					<option value="selected"><?php  echo t('selected answers'); ?></option>
					<option value="all"><?php  echo t('all answers'); ?></option>
				</select>
				<input type="submit" value="Go" />
			</div>
		</th>
	</tr>
	
</table>
</form>
<br />
<div style="float:left;">
	<form id="pageSizeForm" method="get">
		<input type="hidden" name="fID" value="<?php  echo intval($_GET['fID']); ?>" />
		<input type="hidden" name="pageNum" value="<?php  echo intval($_GET['pageNum']); ?>" />
		<input type="hidden" name="q" value="<?php  echo $_GET['q']; ?>" />
    	<input type="hidden" name="sortOrder" value="<?php  echo $sortOrder; ?>" />
		<input type="hidden" name="sortffID" value="<?php  echo intval($_GET['sortffID']); ?>" />
		<select name="pageSize" onchange="$('#pageSizeForm').submit();" style="width:80px;">
			<option value="10" <?php   if($pageSize == 10) { echo 'selected="selected"'; } ?> >10</option>
			<option value="25" <?php   if($pageSize == 25) { echo 'selected="selected"'; } ?>>25</option>
			<option value="50" <?php   if($pageSize == 50) { echo 'selected="selected"'; } ?>>50</option>
			<option value="100" <?php   if($pageSize == 100) { echo 'selected="selected"'; } ?>>100</option>
			<option value="250" <?php   if($pageSize == 250) { echo 'selected="selected"'; } ?>>250</option>
			<option value="500" <?php   if($pageSize == 500) { echo 'selected="selected"'; } ?>>500</option>
		</select> <?php  echo t('results per page'); ?>
	</form>
</div>
<div style="float:right;">
    <form id="pageNumForm" method="get">
        <input type="hidden" name="fID" value="<?php  echo intval($_GET['fID']); ?>" />
        <input type="hidden" name="ffID" value="<?php  echo intval($_GET['ffID']); ?>" />
        <input type="hidden" name="q" value="<?php  echo htmlspecialchars($_GET['q']); ?>" />
        <input type="hidden" name="sortOrder" value="<?php  echo $sortOrder; ?>" />
		<input type="hidden" name="sortffID" value="<?php  echo intval($_GET['sortffID']); ?>" />
		<?php   if (intval($_GET['pageSize']) == 0) { $_GET['pageSize'] = 25; } ?>
		<input type="hidden" name="pageSize" value="<?php  echo intval($_GET['pageSize']); ?>" />
        Page 
		<select name="pageNum" onchange="$('#pageNumForm').submit();" style="width:80px;">
			<?php   for($i=1;$i<=$numPages;$i++) { ?>
			<option value="<?php  echo $i; ?>" <?php   if($_GET['pageNum'] == $i) { echo 'selected="selected"'; } ?> ><?php  echo $i; ?></option>
			<?php   } ?>
		</select>
    </form>
</div>
<div style="clear:both"></div>

<?php   } elseif ($action == 'export') { //Display export options ?>

<?php  echo Loader::helper('concrete/dashboard')->getDashboardPaneHeaderWrapper(t('Export Results'), false);?>
	<?php  echo $h->button_js( t('Return to Form List'), 'window.location = \'' . $this->url("/dashboard/sixeightforms/forms") . '\'','right','success'); ?>
	<form id="export-form" action="<?php  echo View::url('/dashboard/sixeightforms/forms','export?action=export&fID=' . $_GET['fID']); ?>" method="GET">
		<input type="hidden" name="action" value="export" />
		<input type="hidden" name="fID" value="<?php  echo $_GET['fID']; ?>" />
		<h3>1. <?php  echo t('Date range'); ?></h3>
		<p><input type="radio" name="dateRange" value="all" checked="checked" /> <?php  echo t('All records'); ?></p>
		<?php  if(intval($f->properties['exportTimestamp']) > 0) { ?>
		<p><input type="radio" name="dateRange" value="unexported" /> <?php  echo t('Records submitted since last export on'); ?> <?php  echo date('m/d/Y',$f->properties['exportTimestamp']); ?> <?php  echo t('at'); ?> <?php  echo date('g:i a',$f->properties['exportTimestamp']); ?></p>
		<?php  } ?>
		<p><input type="radio" name="dateRange" value="range" /> <?php  echo t('Records submitted between'); ?>
		<input class="export-date" name="startDate" type="text" size="10" value="<?php  echo date('Y-m-d',$f->properties['exportTimestamp']); ?>" />
		and
		<input class="export-date" name="endDate" type="text" size="10" value="<?php  echo date('Y-m-d'); ?>" />
		<script type="text/javascript">
		$(document).ready(function() { $(".export-date").datepicker({ changeYear: true, showAnim: 'fadeIn', dateFormat: 'yy-mm-dd' }); });
		</script>
		
		<h3>2. <?php  echo t('Approval status'); ?></h3>
		<p><input type="radio" name="requireApproval" value="0" checked="checked" /> <?php  echo t('All'); ?></p>
		<p><input type="radio" name="requireApproval" value="1" /> <?php  echo t('Approved Only'); ?></p>
		<h3>3. <?php  echo t('Fields to include'); ?></h3>
		<a href="#" class="sem-check-all-fields">Check All</a> | <a href="#" class="sem-uncheck-all-fields">Uncheck All</a><br /><br />
		<?php  foreach($f->getFields() as $ff) { ?>
			<div style="width:220px;float:left;margin:2px 0"><input class="field-checkbox" type="checkbox" name="ffID[]" value="<?php  echo $ff->ffID; ?>" checked="checked" /> <?php  echo $ff->shortLabel; ?></div>
		<?php  } ?>
		<div style="clear:both"></div>
		<br />
		<?php  echo $h->submit(t('Export'),'export-form','left','primary'); ?>
	</form>
	<div style="clear:both"></div>
	<script type="text/javascript">
	$(document).ready(function() {
		$('.sem-check-all-fields').click(function(e) {
			e.preventDefault();
			$('.field-checkbox').attr('checked',true);
		});

		$('.sem-uncheck-all-fields').click(function(e) {
			e.preventDefault();
			$('.field-checkbox').attr('checked',false);
		});
	});
	</script>

<?php   } elseif ($_GET['fID'] == '') { //Display form list ?>

<?php 
$pkg = Package::getByHandle('sixeightforms'); ?>

<?php  if(intval($_GET['newfID']) != 0) { ?>
<script type="text/javascript">
$(document).ready(function() {
	$('#sem-form-list-item-<?php  echo $_GET['newfID']; ?>').effect("highlight", {
		color:'#ffff99'
	}, 2000);
});
</script>
<?php  } ?>

<?php  echo Loader::helper('concrete/dashboard')->getDashboardPaneHeaderWrapper(t('Advanced Forms') . '(' . $pkg->getPackageVersion() . ')', false);?>
	<div style="margin:10px 0;">
		<?php  echo $h->button_js( t('Create a New Form'), 'startNewForm()','left','success'); ?>
		<?php  echo $h->button( t('Styles'), View::URL('/dashboard/sixeightforms/styles'),'left'); ?>
		<?php  echo $h->button( t('Tools'), View::URL('/dashboard/sixeightforms/import'),'left'); ?>
			<div style="clear:both"></div>
	</div>
	<hr />
	<?php  if(count($forms) > 0) { ?>
		<div id="sem-form-list">
		<?php  foreach($forms as $f) { ?>
			<div class="sem-form-list-item" id="sem-form-list-item-<?php  echo $f->fID; ?>">
					<h4 id="form-name-<?php  echo $f->fID; ?>" class="form-name" rel="<?php  echo $f->fID; ?>" style="cursor:pointer">
							<?php  if($f->properties['name']) { ?>
								<?php  echo $f->properties['name']; ?>
							<?php  } else { ?>
								Form <?php  echo $f->fID; ?>
							<?php  } ?>
					</h4>		
					<form class="sem-form-name-form" id="form-name-form-<?php  echo $f->fID; ?>" style="display:none;margin:0;">
						<input class="sem-form-name-id" type="hidden" value="<?php  echo $f->fID; ?>" />
						<input class="sem-form-name-input" type="text" value="<?php  echo $f->properties['name']; ?>" id="form-name-input-<?php  echo $f->fID; ?>" />
					</form>
					<div class="sem-note">
						<?php  echo $f->getFieldCount(); ?> fields, <?php  echo $f->getTotalAnswerSetCount(); ?> records
						<div style="float:right">
							<a href="<?php  echo $this->url($c->getCollectionPath(),'export','?fID=' . $f->fID);?>"><?php  echo t('Export');?></a> | <a href="<?php  echo $this->url($c->getCollectionPath(),'duplicateForm','?fID=' . $f->fID);?>"><?php  echo t('Duplicate'); ?></a>
						</div>
					</div>
					<a id="sem-form-btn-delete" href="javascript:void(0)" onclick="<?php  echo 'deleteForm(' . $f->fID . ');';?>">&times;</a>	
					
					<div class="sem-form-list-buttons">
						<a class="btn" href="javascript:void(0);" onclick="editSettings(<?php  echo $f->fID; ?>);"><span id="sem-form-btn-settings"><?php  echo t('Settings');?></span></a>
						<a class="btn" href="<?php  echo $this->url($c->getCollectionPath(),'results','?fID=' . $f->fID); ?>"><span id="sem-form-btn-records"><?php  echo t('Records'); ?></span></a>
						<a class="btn" href="<?php  echo $this->url($c->getCollectionPath(),'manageFields','?fID=' . $f->fID);?>"><span id="sem-form-btn-fields"><?php  echo t('Fields');?></span></a>
						<a class="btn" href="javascript:void(0)" onclick="<?php  echo 'setNotifications(' . $f->fID . ');';?>"><span id="sem-form-btn-notifications"><?php  echo t('Notifications');?></span></a>
						<a class="btn" href="javascript:void(0)" onclick="<?php  echo 'setPermissions(' . $f->fID . ');';?>"><span id="sem-form-btn-permissions"><?php  echo t('Permissions');?></span></a>
						<a class="btn" href="javascript:void(0)" onclick="<?php  echo 'manageRules(' . $f->fID . ');';?>"><span id="sem-form-btn-rules"><?php  echo t('Rules');?></span></a>
					</div>
			</div>
		<?php  } ?>
			<div style="clear:both"></div>
		</div>
	<?php  } else { ?>
		<h4><?php  echo t('Click "Create a New Form" to create your first Advanced Form!'); ?></h4>
	<?php  } ?>

<?php   } else { //Display field list ?>

<?php  echo Loader::helper('concrete/dashboard')->getDashboardPaneHeaderWrapper($f->properties['name'], false);?>
	<table width="100%">
		<tr>
			<td width="200" valign="top" class="ccm-search-form-advanced-col">
				<form method="get" action="<?php  echo $this->url($c->getCollectionPath(),'results'); ?>">
					<div id="ccm-user-search-advanced-fields" class="ccm-search-advanced-fields" >
						<h3><?php  echo t('Add New Field'); ?></h3>
						<div id="ccm-search-advanced-fields-inner">
							<div class="ccm-search-field">
								<div class="new-field-header">Text-Based Fields</div>
								<div class="new-field-link"><a dialog-title="<?php  echo t('Add Field'); ?>" dialog-width="600" dialog-height="350" dialog-modal="false" href="<?php  echo $uh->getToolsURL('new_field','sixeightforms'); ?>?type=sem-text-single-line&fID=<?php  echo $_GET['fID']; ?>"><img src="<?php   echo DIR_REL; ?>/packages/sixeightforms/images/textfield.png" align="absmiddle" /> Text (Single-Line) <span class="sem-quick-add">Quick Add</span></a></div>
								<div class="new-field-link"><a dialog-title="<?php  echo t('Add Field'); ?>" dialog-width="600" dialog-height="350" dialog-modal="false" href="<?php  echo $uh->getToolsURL('new_field','sixeightforms'); ?>?type=sem-text-multi-line&fID=<?php  echo $_GET['fID']; ?>"><img src="<?php   echo DIR_REL; ?>/packages/sixeightforms/images/textarea.png" align="absmiddle" /> Text (Multi-Line) <span class="sem-quick-add">Quick Add</span></a></div>
								<div class="new-field-link"><a dialog-title="<?php  echo t('Add Field'); ?>" dialog-width="600" dialog-height="350" dialog-modal="false" href="<?php  echo $uh->getToolsURL('new_field','sixeightforms'); ?>?type=sem-number&fID=<?php  echo $_GET['fID']; ?>"><img src="<?php   echo DIR_REL; ?>/packages/sixeightforms/images/number.png" align="absmiddle" /> Number <span class="sem-quick-add">Quick Add</span></a></div>
								<div class="new-field-link"><a dialog-title="<?php  echo t('Add Field'); ?>" dialog-width="600" dialog-height="350" dialog-modal="false" href="<?php  echo $uh->getToolsURL('new_field','sixeightforms'); ?>?type=sem-email-address&fID=<?php  echo $_GET['fID']; ?>"><img src="<?php   echo DIR_REL; ?>/packages/sixeightforms/images/email.png" align="absmiddle" /> Email Address <span class="sem-quick-add">Quick Add</span></a></div>
								<div class="new-field-link"><a dialog-title="<?php  echo t('Add Field'); ?>" dialog-width="600" dialog-height="350" dialog-modal="false" href="<?php  echo $uh->getToolsURL('new_field','sixeightforms'); ?>?type=sem-wysiwyg&fID=<?php  echo $_GET['fID']; ?>"><img src="<?php   echo DIR_REL; ?>/packages/sixeightforms/images/wysiwyg.png" align="absmiddle" /> WYSIWYG <span class="sem-quick-add">Quick Add</span></a></div>
								<div class="new-field-header">Option-Based Fields</div>
								<div class="new-field-link"><a dialog-title="<?php  echo t('Add Field'); ?>" dialog-width="600" dialog-height="350" dialog-modal="false" href="<?php  echo $uh->getToolsURL('new_field','sixeightforms'); ?>?type=sem-dropdown&fID=<?php  echo $_GET['fID']; ?>"><img src="<?php   echo DIR_REL; ?>/packages/sixeightforms/images/dropdown.png" align="absmiddle" /> Dropdown <span class="sem-quick-add">Quick Add</span></a></div>
								<div class="new-field-link"><a dialog-title="<?php  echo t('Add Field'); ?>" dialog-width="600" dialog-height="350" dialog-modal="false" href="<?php  echo $uh->getToolsURL('new_field','sixeightforms'); ?>?type=sem-multi-select&fID=<?php  echo $_GET['fID']; ?>"><img src="<?php   echo DIR_REL; ?>/packages/sixeightforms/images/multi_select.png" align="absmiddle" /> Multi-Select <span class="sem-quick-add">Quick Add</span></a></div>
								<div class="new-field-link"><a dialog-title="<?php  echo t('Add Field'); ?>" dialog-width="600" dialog-height="350" dialog-modal="false" href="<?php  echo $uh->getToolsURL('new_field','sixeightforms'); ?>?type=sem-radio-button&fID=<?php  echo $_GET['fID']; ?>"><img src="<?php   echo DIR_REL; ?>/packages/sixeightforms/images/radio.png" align="absmiddle" /> Radio Button <span class="sem-quick-add">Quick Add</span></a></div>
								<div class="new-field-link"><a dialog-title="<?php  echo t('Add Field'); ?>" dialog-width="600" dialog-height="350" dialog-modal="false" href="<?php  echo $uh->getToolsURL('new_field','sixeightforms'); ?>?type=sem-checkbox&fID=<?php  echo $_GET['fID']; ?>"><img src="<?php   echo DIR_REL; ?>/packages/sixeightforms/images/checkbox.png" align="absmiddle" /> Checkbox <span class="sem-quick-add">Quick Add</span></a></div>
								<div class="new-field-link"><a dialog-title="<?php  echo t('Add Field'); ?>" dialog-width="600" dialog-height="350" dialog-modal="false" href="<?php  echo $uh->getToolsURL('new_field','sixeightforms'); ?>?type=sem-true-false&fID=<?php  echo $_GET['fID']; ?>"><img src="<?php   echo DIR_REL; ?>/packages/sixeightforms/images/true_false.png" align="absmiddle" /> True/False <span class="sem-quick-add">Quick Add</span></a></div>
								<div class="new-field-header">Section Fields</div>
								<div class="new-field-link"><a dialog-title="<?php  echo t('Add Field'); ?>" dialog-width="600" dialog-height="350" dialog-modal="false" href="<?php  echo $uh->getToolsURL('new_field','sixeightforms'); ?>?type=sem-section-divider&fID=<?php  echo $_GET['fID']; ?>"><img src="<?php   echo DIR_REL; ?>/packages/sixeightforms/images/section_divider.png" align="absmiddle" /> Section Divider <span class="sem-quick-add">Quick Add</span></a></div>
								<div class="new-field-link"><a dialog-title="<?php  echo t('Add Field'); ?>" dialog-width="600" dialog-height="350" dialog-modal="false" href="<?php  echo $uh->getToolsURL('new_field','sixeightforms'); ?>?type=sem-next&fID=<?php  echo $_GET['fID']; ?>"><img src="<?php   echo DIR_REL; ?>/packages/sixeightforms/images/next.png" align="absmiddle" /> Next Button <span class="sem-quick-add">Quick Add</span></a></div>
								<div class="new-field-link"><a dialog-title="<?php  echo t('Add Field'); ?>" dialog-width="600" dialog-height="350" dialog-modal="false" href="<?php  echo $uh->getToolsURL('new_field','sixeightforms'); ?>?type=sem-previous&fID=<?php  echo $_GET['fID']; ?>"><img src="<?php   echo DIR_REL; ?>/packages/sixeightforms/images/previous.png" align="absmiddle" /> Previous Button <span class="sem-quick-add">Quick Add</span></a></div>
								<div class="new-field-header">Other Fields</div>
								<div class="new-field-link"><a dialog-title="<?php  echo t('Add Field'); ?>" dialog-width="600" dialog-height="350" dialog-modal="false" href="<?php  echo $uh->getToolsURL('new_field','sixeightforms'); ?>?type=sem-date&fID=<?php  echo $_GET['fID']; ?>"><img src="<?php   echo DIR_REL; ?>/packages/sixeightforms/images/date.png" align="absmiddle" /> Date <span class="sem-quick-add">Quick Add</span></a></div>
								<div class="new-field-link"><a dialog-title="<?php  echo t('Add Field'); ?>" dialog-width="600" dialog-height="350" dialog-modal="false" href="<?php  echo $uh->getToolsURL('new_field','sixeightforms'); ?>?type=sem-time&fID=<?php  echo $_GET['fID']; ?>"><img src="<?php   echo DIR_REL; ?>/packages/sixeightforms/images/time.png" align="absmiddle" /> Time <span class="sem-quick-add">Quick Add</span></a></div>
								<div class="new-field-link"><a dialog-title="<?php  echo t('Add Field'); ?>" dialog-width="600" dialog-height="350" dialog-modal="false" href="<?php  echo $uh->getToolsURL('new_field','sixeightforms'); ?>?type=sem-file-upload&fID=<?php  echo $_GET['fID']; ?>"><img src="<?php   echo DIR_REL; ?>/packages/sixeightforms/images/file_upload.png" align="absmiddle" /> File Upload <span class="sem-quick-add">Quick Add</span></a></div>
								<div class="new-field-link"><a dialog-title="<?php  echo t('Add Field'); ?>" dialog-width="600" dialog-height="350" dialog-modal="false" href="<?php  echo $uh->getToolsURL('new_field','sixeightforms'); ?>?type=sem-file-from-file-manager&fID=<?php  echo $_GET['fID']; ?>"><img src="<?php   echo DIR_REL; ?>/packages/sixeightforms/images/file_manager.png" align="absmiddle" /> File from File Manager <span class="sem-quick-add">Quick Add</span></a></div>
								<div class="new-field-link"><a dialog-title="<?php  echo t('Add Field'); ?>" dialog-width="600" dialog-height="350" dialog-modal="false" href="<?php  echo $uh->getToolsURL('new_field','sixeightforms'); ?>?type=sem-sellable-item&fID=<?php  echo $_GET['fID']; ?>"><img src="<?php   echo DIR_REL; ?>/packages/sixeightforms/images/sellable_item.png" align="absmiddle" /> Sellable Item <span class="sem-quick-add">Quick Add</span></a></div>
								<div class="new-field-link"><a dialog-title="<?php  echo t('Add Field'); ?>" dialog-width="600" dialog-height="350" dialog-modal="false" href="<?php  echo $uh->getToolsURL('new_field','sixeightforms'); ?>?type=sem-credit-card&fID=<?php  echo $_GET['fID']; ?>"><img src="<?php   echo DIR_REL; ?>/packages/sixeightforms/images/credit_card.png" align="absmiddle" /> Credit Card <span class="sem-quick-add">Quick Add</span></a></div>
								<div class="new-field-link"><a dialog-title="<?php  echo t('Add Field'); ?>" dialog-width="600" dialog-height="350" dialog-modal="false" href="<?php  echo $uh->getToolsURL('new_field','sixeightforms'); ?>?type=sem-hidden&fID=<?php  echo $_GET['fID']; ?>"><img src="<?php   echo DIR_REL; ?>/packages/sixeightforms/images/hidden.png" align="absmiddle" /> Hidden <span class="sem-quick-add">Quick Add</span></a></div>
								<div class="new-field-link"><a dialog-title="<?php  echo t('Add Field'); ?>" dialog-width="600" dialog-height="350" dialog-modal="false" href="<?php  echo $uh->getToolsURL('new_field','sixeightforms'); ?>?type=sem-text-no-user-input&fID=<?php  echo $_GET['fID']; ?>"><img src="<?php   echo DIR_REL; ?>/packages/sixeightforms/images/text.png" align="absmiddle" /> Text (no user input) <span class="sem-quick-add">Quick Add</span></a></div>
							</div>
						</div>
					</div>
				</form>
			</td>
			<td valign="top">
				<?php  echo $h->button_js( t('Return to Form List'), 'window.location = \'' . $this->url("/dashboard/sixeightforms/forms") . '\'','right','success'); ?>
				<h3>Fields</h3>
				<br />
				<div style="clear:both"></div>
				<?php  
				if(is_array($fields)) {
				?>
				<form id="field-sort-form" action="<?php  echo $this->url($c->getCollectionPath(),'processFields'); ?>" method="post">
				<input type="hidden" name="fID" value="<?php  echo intval($_GET['fID']); ?>" />
				<table cellpadding="4" cellspacing="0" border="0" width="100%" class="ccm-results-list" id="fields-table">
					<tr class="nodrag nodrop">
						<th width="20"><input type="checkbox" onclick="$(this).is(':checked') ? $('.ff-checkbox').attr('checked',true) : $('.ff-checkbox').attr('checked',false);" /></th>
						<th width="20"><?php  echo t('Sort'); ?></th>
						<th width="20"><?php  echo t('Type'); ?></th>
						<th style="text-align:center"><strong><?php  echo t('Label'); ?></strong></th>
						<th></th>
						<th></th>
						<th></th>
						<th></th>
						<th style="text-align:center" width="20"><strong><?php  echo t('Edit'); ?></strong></th>
						<th style="text-align:center" width="20"><strong><?php  echo t('Delete'); ?></strong></th>
					</tr>
					<?php   foreach($fields as $field) { ?>
					<tr class="ccm-list-record">
						<td style="text-align:center"><input type="checkbox" class="ff-checkbox" name="sem-ff[]" value="<?php  echo $field->ffID; ?>" /></td>
						<td style="text-align:center" class="drag-handle">
							<img src="<?php  echo ASSETS_URL_IMAGES?>/icons/up_down.png" style="cursor:move" />
							<input type="hidden" name="sort[]" value="<?php  echo $field->ffID; ?>" />
						</td>
						<td><img src="<?php  echo DIR_REL; ?>/packages/sixeightforms/images/<?php  echo $field->getFieldTypeHandle(); ?>.png" align="absmiddle"  /></td>
						<td>
							<div class="sem-field-name-container" id="sem-field-name-container-<?php  echo $field->ffID; ?>" rel="<?php  echo $field->ffID; ?>"><?php  echo $field->shortLabel; ?></div>
							<div class="sem-field-name-form" id="field-name-form-<?php  echo $field->ffID; ?>" style="display:none;margin:0;">
								<input class="sem-field-name-id" type="hidden" value="<?php  echo $field->ffID; ?>" />
								<input class="sem-field-name-input" type="text" value="<?php  echo $field->label; ?>" id="field-name-input-<?php  echo $field->ffID; ?>" />
							</div>
						</td>
						<td style="text-align:center">
							<?php   if($field->isRequired()) { ?>
								<a href="#" class="required-button" title="<?php  echo t('Field is required?'); ?>" rel="<?php  echo $field->ffID; ?>"><img src="<?php  echo DIR_REL; ?>/packages/sixeightforms/images/accept.png" /></a>
							<?php   } else { ?>
								<a href="#" class="required-button" title="<?php  echo t('Field is required?'); ?>" rel="<?php  echo $field->ffID; ?>"><img src="<?php  echo DIR_REL; ?>/packages/sixeightforms/images/accept-off.png" /></a>
							<?php   } ?>
						<td style="text-align:center">
							<?php  if($field->indexable) { ?>
								<a href="#" class="searchable-button" title="<?php  echo t('Field is searchable?'); ?>" rel="<?php  echo $field->ffID; ?>"><img src="<?php  echo DIR_REL; ?>/packages/sixeightforms/images/search.png" /></a>
							<?php  } else { ?>
								<a href="#" class="searchable-button" title="<?php  echo t('Field is searchable?'); ?>" rel="<?php  echo $field->ffID; ?>"><img src="<?php  echo DIR_REL; ?>/packages/sixeightforms/images/search-off.png" /></a>
							<?php  } ?>
						</td>
						<td style="text-align:center">
							<?php  if($field->excludeFromEmail) { ?>
								<a href="#" class="exclude-from-email-button" title="<?php  echo t('Include field in email notifications?'); ?>" rel="<?php  echo $field->ffID; ?>"><img src="<?php  echo DIR_REL; ?>/packages/sixeightforms/images/email-off.png" /></a>
							<?php  } else { ?>
								<a href="#" class="exclude-from-email-button" title="<?php  echo t('Include field in email notifications?'); ?>" rel="<?php  echo $field->ffID; ?>"><img src="<?php  echo DIR_REL; ?>/packages/sixeightforms/images/email.png" /></a>
							<?php  } ?>
						</td>
						<td style="text-align:center">
							<?php  if(($field->type == 'Dropdown') || ($field->type == 'Multi-Select') || ($field->type == 'Radio Button') || ($field->type == 'Checkbox')) { ?>
							<a href="#" class="alternate-options-button" title="<?php  echo t('Alternate options'); ?>" rel="<?php  echo $field->ffID; ?>"><img src="<?php  echo DIR_REL; ?>/packages/sixeightforms/images/rules.png" /></a>
							<?php  } ?>
						</td>
						<td style="text-align:center">
							<?php  echo $h->button_js( t('Edit'),'editField(' . $field->ffID . ')','right'); ?>
						</td>
						<td style="text-align:center">
							<?php  echo $h->button_js( t('Delete'),'confirmDeleteField(' . $field->ffID . ')','right','error'); ?>
						</td>
					</tr>
					<?php   } ?>
					<tr class="nodrag nodrop">
						<td colspan="10">
							<select name="action">
								<option value="">---</option>
								<option value="duplicate"><?php  echo t('Duplicate'); ?></option>
								<option value="delete"><?php  echo t('Delete'); ?></option>
							</select> <b><?php  echo t('selected fields'); ?></b>
							<input type="submit" value="<?php  echo t('Go'); ?>" />
						</td>
					</tr>
				<?php   } ?>
				</table>
				</form>
			</td>
		</tr>
	</table>
	<script type="text/javascript">
	$(document).ready(function() {
		$('.sem-field-name-container').click(function(e) {
			e.preventDefault();
			$(this).hide();
			var ffID = $(this).attr('rel');
			$('#field-name-form-' + ffID).show();
			$('#field-name-input-' + ffID).focus();
			editingFieldName = true;
		});
	
		$('.sem-field-name-input').blur(function(e) {
			e.preventDefault();
			$(this).parent().hide();
			$('.sem-field-name-container').show();
			editingFieldName = false;
		});
		
		$('.sem-field-name-form').submit(function(e) {
			e.preventDefault();
			$(this).hide();
			var ffID = $('.sem-field-name-id',this).val();
			$('#sem-field-name-container-' + ffID).show();
			return false;
		});
		
		$('#field-sort-form').submit(function(e) {
			if($('.sem-field-name-input').is(':focus')) {
				e.preventDefault();	
				$currentFieldElement = $('.sem-field-name-input:focus');
				$currentFieldContainer = $currentFieldElement.parent();
				var ffID = $('.sem-field-name-id',$currentFieldContainer).val();
				var label = $currentFieldElement.val();
				var formData = 'ffID=' + ffID + '&label=' + encodeURIComponent(label);
				$.ajax({
					url: '<?php  echo $uh->getToolsURL('update_field_label','sixeightforms'); ?>',
					data: formData,
					success: function(newShortLabel) {
						$('#sem-field-name-container-' + ffID).html(newShortLabel);
						$currentFieldElement.blur();
					}
				});
				return false;
			}
		});
	});
	</script>
<?php   } ?>