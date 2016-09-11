
<script>
function assignChooseMultiFileAttrFunc<?php echo $this->attributeKey->getAttributeKeyID() ?>(){
	ccm_chooseAsset = function (data){
		if(!parseInt(data.fID)) return false;
		var html = '<li class="fileAttachmentRow" id="ak<?php echo $this->attributeKey->getAttributeKeyID() ?>_fileAttachmentRow'+data.fID+'">';
		html = html+'<table class="multiple_files"><tr><td class="files_sort"><span class="ui-icon ui-icon-arrowthick-2-n-s"></span></td><td class="files_file"><input name="akID[<?php echo $this->attributeKey->getAttributeKeyID() ?>][fID][]" type="checkbox" checked="checked" value="'+data.fID+'" />';
		html = html+'<a class="fileAttachmentTitle" href="'+data.filePathDirect+'" target="_blank">'+data.title+'</a></td>';
		html = html+'<td class="files_caption"><input type="text" placeholder="Caption" name="akID[<?php echo $this->attributeKey->getAttributeKeyID() ?>][fName][][[' + data.fID + ']" /></td> </tr></table></li>';
		$('#ak<?php echo $this->attributeKey->getAttributeKeyID() ?>_attachedFilesList').append(html);
	}

}

</script>


<a onclick="assignChooseMultiFileAttrFunc<?php echo $this->attributeKey->getAttributeKeyID() ?>();ccm_alLaunchSelectorFileManager(''); return false" href="#"><?php echo t('Add a Image/File &raquo;') ?></a>

<ul id="ak<?php echo $this->attributeKey->getAttributeKeyID() ?>_attachedFilesList" class="filesList">

<?php

if (is_object($this->attributeValue)){
	$multiFilesValueObj = $this->attributeValue->getValue().'';
	//echo get_class( $fIDsStr );
	//$dirtyFIDs = explode(',',$fIDsStr);
	foreach(MultipleFilesAttributeTypeController::getFiles($multiFilesValueObj) as $fileObj){
	$file = $fileObj[0];
	$fileName = $fileObj[1];
		$fv = $file->getApprovedVersion();
		?>
    <li class="fileAttachmentRow" id="ak<?php echo $this->attributeKey->getAttributeKeyID() ?>_fileAttachmentRow<?php echo $file->getFileID() ?>">
        <table class="multiple_files">
            <tr>
                <td class="files_sort">
                    <span class="ui-icon ui-icon-arrowthick-2-n-s"></span>
                </td>
                <td class="files_file">
                    <input name="akID[<?php echo$this->attributeKey->getAttributeKeyID() ?>][fID][]" type="checkbox" checked="checked" value="<?php echo $file->getFileID() ?>" />
                    <a class="fileAttachmentTitle" href="<?php echo $fv->getRelativePath() ?>" target="_blank"><?php echo $fv->getTitle() ?></a>
                </td>
                <td class="files_caption">
                    <input type="text" placeholder="Caption" name="akID[<?php echo $this->attributeKey->getAttributeKeyID() ?>][fName][][<?php echo $file->getFileID() ?>]" value="<?php echo $fileName; ?>" />
                </td>
            </tr>
        </table>
    </li>
	<?php }
} ?>

</ul>

<script type="text/javascript">
// drag and drop change_order.php dashboard js
$(document).ready(function(){

	$(function() {
		$("#ak<?php echo $this->attributeKey->getAttributeKeyID() ?>_attachedFilesList").sortable({ opacity: 0.6, cursor: 'move', update: function() {
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
