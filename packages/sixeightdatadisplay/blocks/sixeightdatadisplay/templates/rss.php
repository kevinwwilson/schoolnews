<?php    
defined('C5_EXECUTE') or die(_("Access Denied."));
global $c;
$nh = Loader::helper('navigation');

$u = new User();
//If the user has access to edit form records, 
if(($f->userCanEdit()) || ($f->ownerCanEdit())) {
?>
<script type="text/javascript">
$(document).ready(function() {
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
});
</script>
<?php  } ?>

<?php 
	if($displayList == 1) { //If we're displaying the list template
		
		//Output sort form
		if (($enableUserSort) && ($sortableFields != '')) {
			include($this->getBlockPath().'/view_sort_form.php');
		}

		//Output search form
		if ($enableSearch) {
			include($this->getBlockPath().'/view_search_form.php');
		}
		
		if(count($answerSets) == 0) { //See if there are results
			echo $listTemplate['templateEmpty']; //Output the empty template
		} else { //If there are results, start output of the list template
			
			//Output the template header
			echo $listTemplate['templateHeader'];
			
			//Start with the regular template content (not the alternate)
			$rowTemplate=$listTemplate['templateContent'];
			
			//Keep track of how many records have been display
			$answersDisplayed=0;
			
			//Loop through answer sets
			foreach($answerSets as $answerSet) {
			
				$answerSetId = $answerSet->asID;
				
				//Make sure that we are display unlimited results, or that we have not yet reached results limit
				if(($itemsPerPage == 0) || ($answersDisplayed<$itemsPerPage)) {
				
					//Generate the template content from the controller
					$row = $controller->generateTemplateContent($rowTemplate,$fields,$answerSet);
					
					//Pass the block ID to the page on which the detail list will be display, so that we know which block to render detail on
					if($detailPageId != $c->getCollectionId()) {
						$detailBID = 0;
					} else {
						$detailBID = $b->getBlockID();
					}
					
					//Replace Extra HTML Ampersand Conversions on > and <
					$row = str_replace('&amp;lt;','&lt;',$row);
					$row = str_replace('&amp;gt;','&gt;',$row);

					//Replace Detail URL placeholder
					$row = str_replace(array('{{DETAILURL}}','%7B%7BDETAILURL%7D%7D'),$nh->getLinkToCollection($detailPageObj) . '?ref_cID=' . $c->getCollectionId() . '&bID=' . $detailBID . '&dd_asId=' . $answerSetId,$row);
					
					//Replace Page URL placeholder
					if(intval($answerSet->cID) != 0) {
						$asPageObj = Page::getByID($answerSet->cID);
						$row = str_replace(array('{{PAGEURL}}','%7B%7BPAGEURL%7D%7D'),$nh->getLinkToCollection($asPageObj),$row);
					} else {
						$row = str_replace(array('{{PAGEURL}}','%7B%7BPAGEURL%7D%7D'),$nh->getLinkToCollection($detailPageObj) . '?ref_cID=' . $c->getCollectionId() . '&bID=' . $detailBID . '&dd_asId=' . $answerSetId,$row);
					}
					
					//Replace <?php  or <?php xml stuff from when we parse the template as XML (the <?php  tag is coming from uploading to C5.org)
					$row = str_replace('<?php  xml version="1.0"?>','',$row);
					$row = str_replace('<?php xml version="1.0"?>','',$row);
					$row = str_replace('<root>','',$row);
					$row = str_replace('</root>','',$row);
					
					//If the row was blank, it will be left with the self-closing <root /> tag.  Only output the row if it is not blank
					if ((trim($row) != '<root/>') && (trim($row) != '<root />')) {
						echo $row;
					}
					
					//Swap to alternate template (or back to original template) if necessary
					if(($alternateExists) && (!$usingAlternate)) {
						$rowTemplate=$listTemplate['templateAlternateContent'];
						$usingAlternate = true;
					} else {
						$rowTemplate=$listTemplate['templateContent'];
						$usingAlternate = false;
					}
				}
				$answersDisplayed++;
			}  //End looping through data
			
			//Output the template footer
			echo $listTemplate['templateFooter'];
		}  //End output of list template

		//Output the paginator, if necessary
		if($paginator && strlen($paginator->getPages())>0 && $displayPaginator==1){
		?>	 
		<div class="pagination" style="text-align:center">
			<div style="float:left"><?php  echo $paginator->getPrevious()?></div>
			<div style="float:right"><?php  echo $paginator->getNext()?></div>
			<?php  echo $paginator->getPages()?>
		</div>		
		<?php      
		} //End paginator display
		
	} else { //Display detail template
		$detail = $controller->generateTemplateContent($detailTemplateContent,$fields,$answerSets[$_GET['dd_asId']]);
		//Replace list URL placeholder			
		$detail = str_replace('{{LISTURL}}',DIR_REL . '/index.php?cID=' . intval($_GET['ref_cID']),$detail);
		echo $detail;
	} //End output of detail template
?>