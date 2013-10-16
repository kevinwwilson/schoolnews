<?php    
defined('C5_EXECUTE') or die(_("Access Denied."));
global $c;
$nh = Loader::helper('navigation');
echo '<h2>' . ucwords(strtolower(htmlentities($_GET['q']))) . '</h2>';
?>

<?php 
	if($_GET['dd_asId']=='') {
		$displayList = 1;	
	} elseif ($_GET['bID'] == '0') {
		$displayList = 0;	
	} elseif ($_GET['bID'] != $b->getBlockID()) {
		$displayList = 1;
	}
	if($displayList == 1) { //If we're displaying the list template
		
		//Output sort form
		if (($enableUserSort) && ($sortableFields != '')) {
			include($this->getBlockPath().'/view_sort_form.php');
		}

		//Output search form
		if (($enableSearch) && ($searchableFields != '')) {
			include($this->getBlockPath().'/view_search_form.php');
		}
		
		if(!count($answerSets)) { //See if there are results
			echo $listTemplate['templateEmpty'];
		} else { //If so, start output of the list template
			
			//Output the template header
			echo $listTemplate['templateHeader'];
			
			
			$rowTemplate=$listTemplate['templateContent'];
			$answersDisplayed=0;
			
			//Loop through answer sets
			foreach($answerSets as $answerSet) {
				$answerSetId = $answerSet->asID;
				if(($itemsPerPage == 0) || ($answersDisplayed<$itemsPerPage)) {
					$row = $controller->generateTemplateContent($rowTemplate,$questions,$answerSet);
					
					//We pass the block ID so that we know which block to render as detail
					if($detailPageId != $c->getCollectionId()) {
						$detailBID = 0;
					} else {
						$detailBID = $b->getBlockID();
					}
					
					//Replace Extra HTML Ampersand Conversions
					$row = str_replace('&amp;','&',$row);

					//Replace Detail URL placeholder
					$row = str_replace(array('{{DETAILURL}}','%7B%7BDETAILURL%7D%7D'),$nh->getLinkToCollection($detailPageObj) . '?ref_cID=' . $c->getCollectionId() . '&bID=' . $detailBID . '&dd_asId=' . $answerSetId,$row);
					
					//Replace Page URL placeholder
					if(intval($answerSet->cID) != 0) {
						$asPageObj = Page::getByID($answerSet['cID']);
						$row = str_replace(array('{{PAGEURL}}','%7B%7BPAGEURL%7D%7D'),$nh->getLinkToCollection($asPageObj),$row);
					} else {
						$row = str_replace(array('{{PAGEURL}}','%7B%7BPAGEURL%7D%7D'),$nh->getLinkToCollection($detailPageObj) . '?ref_cID=' . $c->getCollectionId() . '&bID=' . $detailBID . '&dd_asId=' . $answerSetId,$row);
					}
					
					//Replace <?php  or <?php xml stuff from when we parse the template as XML (the <?php  tag is coming from uploading to C5.org)
					$row = str_replace('<?php  xml version="1.0"?>','',$row);
					$row = str_replace('<?php xml version="1.0"?>','',$row);
					$row = str_replace('<root>','',$row);
					$row = str_replace('</root>','',$row);
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
		
	} else { //Start output of detail template
		$detail = $controller->generateTemplateContent($detailTemplateContent,$questions,$answerSets[$_GET['dd_asId']]);
		//Replace list URL placeholder			
		$detail = str_replace('{{LISTURL}}',DIR_REL . '/index.php?cID=' . intval($_GET['ref_cID']),$detail);
		echo $detail;
	} //End output of detail template
?>