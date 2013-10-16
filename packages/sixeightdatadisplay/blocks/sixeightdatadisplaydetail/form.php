<?php    
defined('C5_EXECUTE') or die(_("Access Denied."));
$uh = Loader::helper('concrete/urls');
$form = Loader::helper('form');
$f = sixeightForm::getByID($fID);
$fields = $f->getFields();
?>
<script type="text/javascript">
$(document).ready(function() {
	if($('#method').val() == 'parameter') {
		$('#row-attribute').hide();
		$('#row-parameter').show();
		$('#row-field').show();
		$('#attribute-label').hide();
		$('#parameter-label').show();
	} else if ($('#method').val() == 'attribute') {
		if($('#pageAttributeHandle').val() == 'custom') {
			$('#row-attribute').show();
			$('#row-parameter').show();
			$('#row-field').show();
			$('#attribute-label').show();
			$('#parameter-label').hide();
		} else {
			$('#row-attribute').show();
			$('#row-parameter').hide();
			$('#row-field').show();
			$('#attribute-label').show();
			$('#parameter-label').hide();
		}	
	} else if(($('#method').val() == 'inherit') || ($('#method').val() == 'owner')) {
		$('#row-parameter').hide();
		$('#row-attribute').hide();
		$('#row-field').hide();
	} else if($('#method').val() == 'username') {
		$('#row-parameter').hide();
		$('#row-attribute').hide();
		$('#row-field').show();
	} else {
		$('#row-parameter').hide();
		$('#row-attribute').show();
	}					   
						   
	$('#method').change(function() {
		if($(this).val() == 'parameter') {
			$('#row-attribute').hide();
			$('#row-parameter').show();
			$('#row-field').show();
			$('#attribute-label').hide();
			$('#parameter-label').show();
		} else if ($('#method').val() == 'attribute') {
			if($('#pageAttributeHandle').val() == 'custom') {
				$('#row-attribute').show();
				$('#row-parameter').show();
				$('#row-field').show();
				$('#attribute-label').show();
				$('#parameter-label').hide();
			} else {
				$('#row-attribute').show();
				$('#row-parameter').hide();
				$('#row-field').show();
				$('#attribute-label').show();
				$('#parameter-label').hide();
			}
		} else if(($(this).val() == 'inherit') || ($(this).val() == 'owner')) {
			$('#row-parameter').hide();
			$('#row-attribute').hide();
			$('#row-field').hide();
		} else if($(this).val() == 'username') {
			$('#row-parameter').hide();
			$('#row-attribute').hide();
			$('#row-field').show();
		} else {
			$('#row-parameter').hide();
			$('#row-attribute').show();
		}
	});
	
	$('#pageAttributeHandle').change(function() {
		if($(this).val() == 'custom') {
			$('#row-parameter').show();
			$('#parameter-label').hide();
			$('#attribute-label').show();
		} else {
			$('#row-parameter').hide();
		}
	});
	
	$('#fID').change(function() {
		var fID = $(this).val();
		$.ajax({
			type: 'GET',
			url: '<?php  echo $uh->getToolsURL('getFormFieldsJSON','sixeightdatadisplay'); ?>',
			dataType: 'json',
			data: 'fID=' + fID,
			success: function(fields) {
				var options = '<option value="0">Record ID</option>';
				for(var i in fields) {
					options += '<option value="' + fields[i].ffID + '">' + fields[i].shortLabel + '</option>';
				}
				$('#ffID').html(options);
			}
		});
	});
});
</script>
<table cellpadding="2" cellspacing="1" border="0" width="100%">
    <tr>
        <td width="170"><?php  echo t('Selected Form');?></td>
        <td>
            <select name="fID" id="fID">
                <?php  foreach($forms as $f) { ?>
                    <option value="<?php  echo $f->fID; ?>" <?php  if ($fID == $f->fID) { echo 'selected="selected"'; } ?>><?php  echo $f->properties['name']; ?></option>
                <?php  } ?>
            </select>
        </td>
    </tr>
    <tr>
        <td><?php  echo t('Detail Template');?></td>
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
    	<td><?php  echo t('Method of specifying which item to display:'); ?></td>
        <td>
        	<select name="method" id="method">
            	<option value="parameter" <?php  if($method == 'parameter') { echo 'selected="selected"'; } ?>>URL Parameter</option>
                <option value="attribute" <?php  if($method == 'attribute') { echo 'selected="selected"'; } ?>>Page Attribute</option>
                <option value="inherit" <?php  if($method == 'inherit') { echo 'selected="selected"'; } ?>>Associated Record</option>
                <option value="username" <?php  if($method == 'username') { echo 'selected="selected"'; } ?>>Match current user to field value</option>
                <option value="owner" <?php  if($method == 'owner') { echo 'selected="selected"'; } ?>>Match current user to record owner</option>
            </select>
        </td>
    </tr>
    <tr id="row-attribute">
    	<td><?php  echo t('Page attribute'); ?></td>
        <td>
        	<select name="pageAttributeHandle" id="pageAttributeHandle">
            	<option value="cName" <?php  if($pageAttributeHandle == 'cName') { echo 'selected="selected"'; } ?>>Page Name</option>
                <option value="cDescription" <?php  if($pageAttributeHandle == 'cDescription') { echo 'selected="selected"'; } ?>>Page Description</option>
                <option value="cHandle" <?php  if($pageAttributeHandle == 'cHandle') { echo 'selected="selected"'; } ?>>Page Handle</option>
                <option value="custom" <?php  if($pageAttributeHandle == 'custom') { echo 'selected="selected"'; } ?>>Custom</option>
            </select>
        </td>
    </tr>
    <tr id="row-parameter">
    	<td>
    		<span id="parameter-label" style="display:none"><?php  echo t('Parameter name'); ?></span>
    		<span id="attribute-label"><?php  echo t('Attribute handle'); ?></span>
    	</td>
        <td>
        	<input type="text" name="parameterName" value="<?php  echo $parameterName; ?>">
        </td>
    </tr>
    <tr id="row-field">
    	<td><?php  echo t('Form field to match'); ?></td>
        <td>
        	<select name="ffID" id="ffID">
            	<option value="0">Record ID</option>
            <?php  foreach($fields as $field) { ?>
            	<option value="<?php  echo $field->ffID; ?>" <?php  if($field->ffID == $ffID) { echo 'selected="selected"'; } ?>><?php  echo $field->shortLabel; ?></option>
            <?php  } ?>
            </select>
        </td>
    </tr>
</table>
    