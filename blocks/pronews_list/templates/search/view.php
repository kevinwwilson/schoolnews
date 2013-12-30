<?php  
	defined('C5_EXECUTE') or die(_("Access Denied."));
	$textHelper = Loader::helper("text"); 
	// now that we're in the specialized content file for this block type, 
	// we'll include this block type's class, and pass the block to it, and get
	// the content
	$snumber=0;
	global $c;
	
	
if (count($cArray) > 0) { ?>

	<?php  
	$lowdistrict = '';
	for ($i = 0; $i < count($cArray); $i++ ) {
	 
		$cobj = $cArray[$i];
		$title = $cobj->getCollectionName();
		$author = $cobj->getAttribute('author');
		$dateline = $cobj->getAttribute('dateline');
		$district = $cobj->getAttribute('district');				
		$dateline = $cobj->getAttribute('dateline');
		
		$c = $cobj;
		ob_start();
		$a = new Area('Main');
        $a->display($c);
		$con = ob_get_contents();
        ob_end_clean();
		$lowcon = strtolower($con);
		
		
		
		$lowauthor = strtolower($author);
		$lowtitle = strtolower($title);
		$lowsearch = strtolower($_GET['q']);
		if($_GET['q'] !=''){
			foreach($district as $dist)
			{
				$lowdistrict = '';
			 $lowdist = $dist->value;	
			 $lowdists = strtolower($lowdist);
			 if (strpos($lowdists, $lowsearch) !== false) {				 
				 $lowdistrict = 1;	
				 break;			 
			 }
			}			
			
		if (strpos($lowtitle, $lowsearch) !== false || strpos($lowauthor, $lowsearch) !== false || strpos($lowcon, $lowsearch) || $lowdistrict !='') { 			
		$pageids[] = $cobj->cID; 
		}
		
		
		
 }
 }
 
        $newsSectionList = new PageList();		
		$newsSectionList->filterByNewsSection(1);
		$newsSectionList->sortBy('cvName', 'asc');
		$tmpSections = $newsSectionList->get();
		$sections = array();
		foreach($tmpSections as $_c) {
			$sections[$_c->getCollectionID()] = $_c->getCollectionName();
		}
 
 
 
  
        $newsList = new PageList();
		$newsList->setItemsPerPage($controller->num);
		$keys = array_keys($sections);
			$keys[] = -1;
			$newsList->filterByParentID($keys);		
		//$newsSectionList->sortBy('cvName', 'asc');
		//$newsSectionList->filterByNewsSection(1);
          
           
                        if($pageids > 0){			               
			            $displayid = implode(",",$pageids);
			            
					    $newsList->filter(false, '( cv.cID in('.$displayid.') )');					    
					    
		                }
       $newsResults = $newsList->getPage();
       
  	    foreach($newsResults as $cobj) { 
		$title = $cobj->getCollectionName();
		$author = $cobj->getAttribute('author');
		$dateline = $cobj->getAttribute('dateline');
		$district = $cobj->getAttribute('district');				
		$dateline = $cobj->getAttribute('dateline');
		
		$c = $cobj;
		ob_start();
		$a = new Area('Main');
        $a->display($c);
		$con = ob_get_contents();
        ob_end_clean();
		$lowcon = strtolower($con);
		
		
		
		$lowauthor = strtolower($author);
		$lowtitle = strtolower($title);
		$lowsearch = strtolower($_GET['q']);
		if($_GET['q'] !=''){
		
			foreach($district as $dist)
			{
				$lowdistrict = '';
			 $lowdist = $dist->value;	
			 $lowdists = strtolower($lowdist);
			 if (strpos($lowdists, $lowsearch) !== false) {				 
				 $lowdistrict = 1;	
				 break;			 
			 }
			}	?>		
			
		
	<li>
	<h2 class="ccm-page-list-title"><a href="<?php  echo $nh->getLinkToCollection($cobj)?>"><?php  echo $title?></a></h2>    
    <strong class="date">by <?php echo $author ?></strong>    
	<p>
    <span class="dateline"><?php echo $dateline ?> — </span>
		<?php  
		if($use_content > 0){
			$block = $cobj->getBlocks('Main');
			foreach($block as $bi) {
				if($bi->getBlockTypeHandle()=='content'){
					$content = $bi->getInstance()->getContent();
				}
			}
		}else{
			$content = $cobj->getCollectionDescription();
		}
		if(!$controller->truncateSummaries){
			echo $content;
		}else{
			echo $textHelper->shorten($content,$controller->truncateChars);
		}
		?>
        <br/>
       <a href="<?php  echo $nh->getLinkToCollection($cobj)?>">More »</a> 
	</p>
	</li>
    <?php }	
	
	 }?>


<?php  } 

if(!$previewMode && $controller->rss) { 
			$bt = BlockType::getByHandle('pronews_list');
			$uh = Loader::helper('concrete/urls');
			$rssUrl = $controller->getRssUrl($b);
			?>
			
			<div class="rssIcon" style="margin-top:10px;">
				<?php  echo t('Subscribe')?> &nbsp;<a href="<?php   echo $rssUrl?>" target="_blank"><img src="<?php echo BASE_URL.DIR_REL ?>/blocks/pronews_list/rss.png" alt="codestrat concrete5 addon development" title="CodeStrat Concrete5 Addon Development" width="14" height="14" /></a>
			</div>
			<link href="<?php  echo $rssUrl?>" rel="alternate" type="application/rss+xml" title="<?php  echo $controller->rssTitle?>" />
		<?php  
	} 
	
	if ($paginate && $num > 0 && is_object($newsList)) {
		$newsList->displayPaging();
	}
	
?>