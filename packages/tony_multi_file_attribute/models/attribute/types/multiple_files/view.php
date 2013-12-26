<?php 
defined('C5_EXECUTE') or die("Access Denied.");
Loader::model('page_list');
Loader::model('product/model', 'core_commerce');


$db = Loader::db();


	$aBlocks = $controller->generateNav();
	$c = Page::getCurrentPage();
	$menus = array();
	if($c->cParentID == 1)
	{
		$title = $c->getCollectionName();
	}else{
		$title=Page::getByID($c->cParentID)->getCollectionName();
	}
	$cparent=Page::getById($c->cParentID);
			
  	echo('<div class="dropmenu ccm-remo-expand">
            <h3>'.$title.'</h3><ul>');
	$nh = Loader::helper('navigation');
	
	//this will create an array of parent cIDs 
	$inspectC=$c;
	$selectedPathCIDs=array( $inspectC->getCollectionID() );
	$parentCIDnotZero=true;	
	while($parentCIDnotZero){
		$cParentID=$inspectC->cParentID;
		if(!intval($cParentID)){
			$parentCIDnotZero=false;
		}else{
			$selectedPathCIDs[]=$cParentID;
			$inspectC=Page::getById($cParentID);
		}
	} 	
	
	foreach($selectedPathCIDs as $selectedPathCID){
	if(Page::getByID($selectedPathCID)->getCollectionAttributeValue('display_expand_collapse_in_this_page_below')){
	$expand_collapse=true;
	break;
	}else{
	$expand_collapse=false;
	}
	}
	
	
	$isFirst = true;
	$i=0;
	foreach($aBlocks as $ni) {
		$_c = $ni->getCollectionObject();
		if (!$_c->getCollectionAttributeValue('exclude_nav')) {

			$target = '_self';
			if ($target != '') {
				$target = 'target="' . $target . '"';
			}

			if ($ni->isActive($c) || strpos($c->getCollectionPath(), $_c->getCollectionPath()) === 0) {
					$navSelected ='class="active"';
			} else {
				$navSelected = '';
			}
			
			$pageLink = false;
			
			if ($_c->getCollectionAttributeValue('sub_navigation')){
				$subPage = $_c->getFirstChild();
				if ($subPage instanceof Page) {
					$pageLink = $nh->getLinkToCollection($subPage);
				}
			}
			
			if (!$pageLink) {
				$pageLink = $ni->getURL();
			}
			$childrens = $controller->getChildPages($_c);
			$counter = 0;
			foreach($childrens as $k){
					$productID = $db->GetOne('select productID from CoreCommerceProducts where cID = ?', array($k->getCollectionID()));
					if ($productID > 0) {
						$counter++;
					}
			}
			    $showMenu = true;
			    
				if(Page::getByID($_c->cParentID)->getCollectionAttributeValue('display_product_count')){
					$pcount=' ('.$counter.')';
					if($counter <= 0){
						$showMenu = false;
					}
				}else{
					$pcount='';
				}
				
				
				$hascategory=false;
				$tcchildrens = $controller->getChildPages($_c);
				foreach($tcchildrens as $_tcc){
					if($_tcc->getCollectionTypeHandle()=='store'){
						$hascategory=true;
						break;
					}else{
						$hascategory=false;
					}
				}
				
				if($expand_collapse && $hascategory){
				$active_title='id="ccm-remo-expand-title-'.$_c->getCollectionID().'" class="ccm-remo-expand-title ccm-remo-expand-closed active" data-expander-speed="500"';
				$inactive_title='id="ccm-remo-expand-title-'.$_c->getCollectionID().'" class="ccm-remo-expand-title ccm-remo-expand-closed" data-expander-speed="500"';
				}else{
				$active_title='class="active"';
				$inactive_title='';
				
				}
				if($showMenu){
				if ($c->getCollectionID() == $_c->getCollectionID()|| in_array($_c->cID,$selectedPathCIDs)) {
					echo('<li  '.$active_title.'   ><a href="' . $pageLink . '"  title="'.$ni->getName().'" ' . $target . '  >' . $ni->getName().$pcount.'</a>');
				} else {
					echo('<li '.$inactive_title.'  ><a href="' . $pageLink . '" title="'.$ni->getName().'" ' . $target . '  >' . $ni->getName().$pcount.'</a>');
				}
				
				}
				
			echo '</li>';
			if($showMenu){
				$cchildrens = $controller->getChildPages($_c);
				}else{
					$cchildrens = array();
				}
				if(sizeof($cchildrens)>0&& $cchildrens[0]!='' ){
				echo '<ul  id="ccm-remo-expand-content-'.$_c->getCollectionID().'" class="ccm-remo-expand-content" >';
				}
			foreach($cchildrens as $_cc){
			
			
			$ccchildrens = $controller->getChildPages($_cc);
			$ccounter = 0;
			foreach($ccchildrens as $k){
					$pproductID = $db->GetOne('select productID from CoreCommerceProducts where cID = ?', array($k->getCollectionID()));
					if ($pproductID > 0) {
						$ccounter++;
					}
			}
			
			$showMenu = true;
				if(Page::getByID($_c->cParentID)->getCollectionAttributeValue('display_product_count')){
					$ppcount=' ('.$ccounter.')';
					
					if($ccounter <= 0){
						$showMenu = false;
					}
					
					
				}else{
					$ppcount='';
				}
			
			//$ppcount='';
			if($showMenu){
			
			if($_cc->getCollectionTypeHandle()!='product_detail'){
			if ($c->getCollectionID() == $_cc->getCollectionID()|| in_array($_cc->cID,$selectedPathCIDs)) {
					echo('<li class="active" ><a href="' . Loader::helper('navigation')->getLinkToCollection($_cc) . '"  title="'.$_cc->getCollectionName().'" ' . $target . '  >&nbsp;&nbsp;&nbsp;&nbsp;' .$_cc->getCollectionName().$ppcount.'</a>');
				} else {
					echo('<li ><a href="' . Loader::helper('navigation')->getLinkToCollection($_cc) . '"  title="'.$_cc->getCollectionName().'" ' . $target . '  >&nbsp;&nbsp;&nbsp;&nbsp;' .$_cc->getCollectionName().$ppcount.'</a>');
				}
				
			echo '</li>';
			}	
			
			}	
			}
			if(sizeof($cchildrens)>0 && $cchildrens[0]!='' ){
			echo "</ul>";
			}
			$counter = 0;
			/*foreach($childrens as $k){
					$productID = $db->GetOne('select productID from CoreCommerceProducts where cID = ?', array($k->getCollectionID()));
					if ($productID > 0) {
						$counter++;
					}
			}
				if(Page::getByID($_c->cParentID)->getCollectionAttributeValue('display_product_count')){
					$pcount=' ('.$counter.')';
				}else{
					$pcount='';
				}
					
			*/
			$isFirst = false;			
		}
	}
	echo('</ul></div>');
	
?>