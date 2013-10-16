<script type="text/javascript">
function deleteForm(fID) {
	$('#tf-row-' + fID).fadeOut();
}

function deleteField(ffID) {
	$('#tff-row-' + ffID).fadeOut();
}

function deleteAnswerSet(asID) {
	$('#tas-row-' + asID).fadeOut();
}

</script>
<div style="width:250px;float:left;margin-right:15px;">
	<h1><span><?php   echo t('Forms'); ?></span></h1>
	<div class="ccm-dashboard-inner">
		<?php   if(is_array($trashedForms)) { ?>
			<table cellpadding="8" cellspacing="0" border="0" class="ccm-results-list">
				<tr>
					<th><strong><?php   echo t('Name'); ?></strong></th>
					<th><strong><?php   echo t('Restore'); ?></strong></th>
					<th><strong><?php   echo t('Delete'); ?></strong></th>
				</tr>
				<?php   foreach($trashedForms as $tf) { ?>
				<tr id="tf-row-<?php   echo $tf['fID']; ?>">
					<td><?php   echo $tf['name']; ?></td>
					<td><a href="#" onclick="deleteForm(<?php   echo $tf['fID']; ?>)" class="delete-form-button"><?php   echo t('Restore'); ?></a></td>
					<td><a href="#" onclick="deleteForm(<?php   echo $tf['fID']; ?>)" class="delete-form-button"><?php   echo t('Delete'); ?></a></td>
				</tr>
				<?php   } ?>
			</table>
		<?php   } ?>
	</div>
</div>

<div style="width:250px;float:left;margin-right:15px;">
	<h1><span>Fields</span></h1>
	<div class="ccm-dashboard-inner">
		<?php   if(is_array($trashedFields)) { ?>
			<table cellpadding="8" cellspacing="0" border="0" class="ccm-results-list">
				<tr>
					<th><strong><?php   echo t('Name'); ?></strong></th>
					<th><strong><?php   echo t('Restore'); ?></strong></th>
					<th><strong><?php   echo t('Delete'); ?></strong></th>
				</tr>
				<?php   foreach($trashedFields as $tff) { ?>
				<tr id="tff-row-<?php   echo $tff['ffID']; ?>">
					<td><?php   echo $tff['label']; ?></td>
					<td><a href="#" onclick="deleteField(<?php   echo $tff['ffID']; ?>)" class="delete-form-button"><?php   echo t('Restore'); ?></a></td>
					<td><a href="#" onclick="deleteField(<?php   echo $tff['ffID']; ?>)" class="delete-form-button"><?php   echo t('Delete'); ?></a></td>
				</tr>
				<?php   } ?>
			</table>
		<?php   } ?>
	</div>
</div>


<div style="width:250px;float:left">
	<h1><span>Records</span></h1>
	<div class="ccm-dashboard-inner">
		<?php   if(is_array($trashedAnswerSets)) { ?>
			<table cellpadding="8" cellspacing="0" border="0" class="ccm-results-list">
				<tr>
					<th><strong><?php   echo t('Name'); ?></strong></th>
					<th><strong><?php   echo t('Restore'); ?></strong></th>
					<th><strong><?php   echo t('Delete'); ?></strong></th>
				</tr>
				<?php   foreach($trashedAnswerSets as $tas) { ?>
				<tr id="tas-row-<?php   echo $tas['asID']; ?>">
					<td><?php   echo $tas['asID']; ?></td>
					<td><a href="#" onclick="deleteAnswerSet(<?php   echo $tas['asID']; ?>)" class="delete-form-button"><?php   echo t('Restore'); ?></a></td>
					<td><a href="#" onclick="deleteAnswerSet(<?php   echo $tas['asID']; ?>)" class="delete-form-button"><?php   echo t('Delete'); ?></a></td>
				</tr>
				<?php   } ?>
			</table>
		<?php   } ?>
	</div>
</div>

<div style="clear:both"></div>