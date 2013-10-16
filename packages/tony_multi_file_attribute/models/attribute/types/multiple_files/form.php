  
<script>
function assignChooseMultiFileAttrFunc<?=$this->attributeKey->getAttributeKeyID() ?>(){

	ccm_chooseAsset = function (data){
		if(!parseInt(data.fID)) return false;  
		var html = '<li class="fileAttachmentRow" id="ak<?=$this->attributeKey->getAttributeKeyID() ?>_fileAttachmentRow'+data.fID+'">'; 
		html = html+'<table><tr><td><span class="ui-icon ui-icon-arrowthick-2-n-s"></span></td><td><input name="akID[<?=$this->attributeKey->getAttributeKeyID() ?>][fID][]" type="checkbox" checked="checked" value="'+data.fID+'" />'; 
		html = html+'<a class="fileAttachmentTitle" href="'+data.filePathDirect+'" target="_blank">'+data.title+'</a></td>';
		html = html+'<td><input type="text" placeholder="Caption" name="akID[<?=$this->attributeKey->getAttributeKeyID() ?>][fName][]" /></td> </tr></table></li>'; 
		$('#ak<?=$this->attributeKey->getAttributeKeyID() ?>_attachedFilesList').append(html); 
	}
	
}

</script>
 
<a onclick="assignChooseMultiFileAttrFunc<?=$this->attributeKey->getAttributeKeyID() ?>();ccm_alLaunchSelectorFileManager(''); return false" href="#"><?=t('Add a Image/File &raquo;') ?></a>
  
<ul id="ak<?=$this->attributeKey->getAttributeKeyID() ?>_attachedFilesList" class="filesList"> 
 
<?  

if (is_object($this->attributeValue)){
	$multiFilesValueObj = $this->attributeValue->getValue().'';
	//echo get_class( $fIDsStr );
	//$dirtyFIDs = explode(',',$fIDsStr); 
	foreach(MultipleFilesAttributeTypeController::getFiles($multiFilesValueObj) as $fileObj){ 
	$file = $fileObj[0];
	$fileName = $fileObj[1];
		$fv = $file->getApprovedVersion();
		?>
		<li class="fileAttachmentRow" id="ak<?=$this->attributeKey->getAttributeKeyID() ?>_fileAttachmentRow<?= $file->getFileID() ?>">
        <table><tr><td><span class="ui-icon ui-icon-arrowthick-2-n-s"></span></td><td>
			<input name="akID[<?=$this->attributeKey->getAttributeKeyID() ?>][fID][]" type="checkbox" checked="checked" value="<?= $file->getFileID() ?>" /> 
			<a class="fileAttachmentTitle" href="<?= $fv->getRelativePath() ?>" target="_blank"><?= $fv->getTitle() ?></a></td>
			<td><input type="text" placeholder="Link Text" name="akID[<?=$this->attributeKey->getAttributeKeyID() ?>][fName][]" value="<?php echo $fileName; ?>" /></td>
            
            </tr>
            </table>
		</li> 
	<? }  
} ?> 

</ul> 

<script type="text/javascript">
// drag and drop change_order.php dashboard js
$(document).ready(function(){ 
						   
	$(function() {
		$("#ak<?=$this->attributeKey->getAttributeKeyID() ?>_attachedFilesList").sortable({ opacity: 0.6, cursor: 'move', update: function() {
			var order = $(this).sortable("serialize"); 
			$.post("<?php  echo $this->action('ajax'); ?>", order, function(theResponse){
				$("#status").html(theResponse);				
			}); 															 
		}								  
		});
	});
 
});	
</script>
<style type="text/css">
.fileAttachmentRow {
padding: 5px !important;
background: #e3ecf2 ;
margin-bottom: 1px !important;
}
.fileAttachmentRow:hover {
background: #f5f5f5 ;
}

.filesList {
list-style: none !important;
margin: 0 !important;
padding: 0 !important;
}

</style>

