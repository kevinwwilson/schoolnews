<?php    
defined('C5_EXECUTE') or die(_("Access Denied."));
global $c;
?>
<h2>Semester <?php  echo $controller->defaultFilterValue; ?></h2>
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
				$answerSetId = $answerSet['asID'];
				if(($itemsPerPage == 0) || ($answersDisplayed<$itemsPerPage)) {
					$row = $controller->generateTemplateContent($rowTemplate,$questions,$answerSet);
					//Replace detail URL placeholder and strip out XML stuff
					if($detailPageId != $c->getCollectionId()) {
						$detailBID = 0;
					} else {
						$detailBID = $b->getBlockID();
					}
					$row = str_replace('{{DETAILURL}}',DIR_REL . '/index.php?cID=' . $detailPageId . '&ref_cID=' . $c->getCollectionId() . '&bID=' . $detailBID . '&dd_asId=' . $answerSetId,$row);
					$row = str_replace('%7B%7BDETAILURL%7D%7D',DIR_REL . '/index.php?cID=' . $detailPageId . '&ref_cID=' . $c->getCollectionId() . '&bID=' . $detailBID . '&dd_asId=' . $answerSetId,$row);
					$row = str_replace('<?php  xml version="1.0"?>','',$row);
					$row = str_replace('<?php xml version="1.0"?>','',$row);
					$row = str_replace('<root>','',$row);
					$row = str_replace('</root>','',$row);
					if ((trim($row) != '<root/>') && (trim($row) != '<root />')) {
						echo $row;
					}
					
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
<div class="pagination">
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