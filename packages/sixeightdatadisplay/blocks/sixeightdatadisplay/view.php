<?php    
defined('C5_EXECUTE') or die(_("Access Denied."));
global $c;
$nh = Loader::helper('navigation');
$uh = Loader::helper('concrete/urls');

if($_GET['ddMode'] == 'editRecord') {
	$afBT = BlockType::getByHandle('sixeightforms');
	$afBT->controller->fID = $f->fID;
	$afBT->render('view');
} else {

	//If the user has access to edit form records, 
	if(($f->userCanEdit()) || ($f->ownerCanEdit())) {
	?>
	<script type="text/javascript">
	$(document).ready(function() {
		$('.approve-answer-link').click(function(e) {
			e.preventDefault();
			$approvalButton = $(this);
			var asID = $approvalButton.attr('rel');
			var email = $approvalButton.attr('name');
			$approvalButton.html('<img src="<?php  echo DIR_REL; ?>/concrete/images/throbber_white_16.gif" />');
			e.preventDefault();
			$.ajax({
				url:'<?php  echo $uh->getToolsURL("update_approval","sixeightforms"); ?>?asID=' + $approvalButton.attr('rel'),
				success:function(data) {
					if(data == '1') {
						$approvalButton.html('<?php  echo t('Unapprove'); ?>');
						if(email != '') {
							if(confirm('<?php  echo t('A notification of approval will be sent to '); ?>' + email + '.\n<?php  echo t('Click OK to send.'); ?>')) {
								$.ajax({
									url:'<?php  echo $uh->getToolsURL("send_approval_notification","sixeightforms"); ?>?asID=' + $approvalButton.attr('rel'),
									success:function() {
										alert('<?php  echo t('Email sent!'); ?>');
									}
								});
							}
						}
					} else {
						$approvalButton.html('<?php  echo t('Approve'); ?>');
					}
				}
			});
		});
	});
	</script>
	<?php  } ?>
	
	<?php 
		if($displayList == 1) { //If we're displaying the list template
			//Output sort form
			if (($enableUserSort) && ($sortableFields != '')) {
				$bt->inc('view_sort_form.php',array('pageBase' => $pageBase, 'sortLabel' => $sortLabel, 'formField' => $formField, 'controller' => $controller, 'sortableFields' => $sortableFields, 'sortButtonLabel' => $sortButtonLabel, 'formFields' => $formFields));
			}
	
			//Output search form
			if ($enableSearch) {
				$bt->inc('view_search_form.php',array('pageBase' => $pageBase, 'bID' => $bID, 'searchPlaceholder' => $searchPlaceholder, 'searchButtonText' => $searchButtonText, 'enableSearchReset' => $enableSearchReset, 'searchResetButtonText' => $searchResetButtonText));
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
						
						//Replace Extra HTML Ampersand Conversions
						$row = str_replace('&amp;','&',$row);
	
						//Replace Detail URL placeholder
						$row = str_replace(array('{{DETAILURL}}','%7B%7BDETAILURL%7D%7D'),$nh->getLinkToCollection($detailPageObj) . '?ref_cID=' . $c->getCollectionId() . '&bID=' . $detailBID . '&dd_asId=' . $answerSetId,$row);
						$row = str_replace('{{CURRENTURL}}', htmlspecialchars((!empty($_SERVER['HTTPS'])) ? "https://".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'] : "http://".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI']),$row);
						
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
			$detail = str_replace('{{CURRENTURL}}', htmlspecialchars((!empty($_SERVER['HTTPS'])) ? "https://".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'] : "http://".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI']),$detail);
			$detail = str_replace('<?php  xml version="1.0"?>','',$detail);
			$detail = str_replace('<?php xml version="1.0"?>','',$detail);
			$detail = str_replace('<root>','',$detail);
			$detail = str_replace('</root>','',$detail);
			echo $detail;
		} //End output of detail template
} //End check for if($_GET['mode'] == 'editRecord')
?>