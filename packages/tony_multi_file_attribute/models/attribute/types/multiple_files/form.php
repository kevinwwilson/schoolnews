  
<script>
function assignChooseMultiFileAttrFunc<?=$this->attributeKey->getAttributeKeyID() ?>(){

	ccm_chooseAsset = function (data){
		if(!parseInt(data.fID)) return false;  
		var html = '<li class="fileAttachmentRow" id="ak<?=$this->attributeKey->getAttributeKeyID() ?>_fileAttachmentRow'+data.fID+'">'; 
		html = html+'<table class="multiple_files"><tr><td class="files_sort"><span class="ui-icon ui-icon-arrowthick-2-n-s"></span></td><td class="files_file"><input name="akID[<?=$this->attributeKey->getAttributeKeyID() ?>][fID][]" type="checkbox" checked="checked" value="'+data.fID+'" />'; 
		html = html+'<a class="fileAttachmentTitle" href="'+data.filePathDirect+'" target="_blank">'+data.title+'</a></td>';
		html = html+'<td class="files_caption"><input type="text" placeholder="Caption" name="akID[<?=$this->attributeKey->getAttributeKeyID() ?>][fName][]" /></td> </tr></table></li>'; 
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
        <table class="multiple_files">
            <tr>
                <td class="files_sort">
                    <span class="ui-icon ui-icon-arrowthick-2-n-s"></span>
                </td>
                <td class="files_file">
                    <input name="akID[<?=$this->attributeKey->getAttributeKeyID() ?>][fID][]" type="checkbox" checked="checked" value="<?= $file->getFileID() ?>" /> 
                    <a class="fileAttachmentTitle" href="<?= $fv->getRelativePath() ?>" target="_blank"><?= $fv->getTitle() ?></a>
                </td>
                <td class="files_caption">
                    <input type="text" placeholder="Link Text" name="akID[<?=$this->attributeKey->getAttributeKeyID() ?>][fName][]" value="<?php echo $fileName; ?>" />
                </td>   
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

    .filesList .multiple_files  {
        width: 100%;
    }
    
    .multiple_files .files_sort {
        width: 3%;
    }
    
    .multiple_files .files_file {
        width: 37%;
    }
    
    .multiple_files .files_caption input {
        width: inherit;
    }
    
    .multiple_files .files_caption {
        width: 98%;
    }

</style>

