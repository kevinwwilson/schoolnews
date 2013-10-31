<?php  
	defined('C5_EXECUTE') or die(_("Access Denied."));
	$textHelper = Loader::helper("text"); 
	// now that we're in the specialized content file for this block type, 
	// we'll include this block type's class, and pass the block to it, and get
	// the content
	
	$db = Loader::db();		 
	$row = $db->GetArray('SELECT * FROM btselectProNewsList');
	foreach($row as $data){
			if($data['active'] == 1){			
			$active_artid = $data['atID'];
		}
		
	}
	$articleids = explode('||',$active_artid);		
	$atids = array();
	                             foreach($articleids as $displayid){
	                             Loader::model('page_list');
	                             $pl = new PageList();	                             	                                 
	                             $pl->filter(false, '( cv.cID in('.$displayid.') )');
	                             $pl->filter(false,"ak_group_status like '%Active%'");	                             
	                             $pages = $pl->getPage(); 	                                                       
	                             foreach($pages as $cpage){ 
	                             
	                             $atids[] = $cpage->cID;
	                             
	                             }
	                             }	
	                                                          
	shuffle($atids);	      
	$m=0;
		foreach($atids as $displayid){	
	
	
	
	Loader::model('page_list');
	$plz = new PageList(); 
	$plz->filter(false,"ak_group_status like '%Active%'"); 
	global $u;
	if (!$u -> isLoggedIn ()) {
	$plz->filterByAttribute('approve',"1"); 
	}         
	$pages = $plz->get(); 
	         
    foreach($pages as $cobj){ 
    
     if($cobj->cID == $displayid && $m < 3){
     
    
	 $title = $cobj->getCollectionName();
     $author = $cobj->getAttribute('author');
     $dateline = $cobj->getAttribute('dateline'); ?>                            
	                             
	 <div class="box">
	 
	<strong class="title"><?php  echo $title?></strong>    
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
        </p>
       <a href="<?php  echo $nh->getLinkToCollection($cobj)?>">More »</a> 
	
	</div> 
	<?php if(!$previewMode && $controller->rss) { 
			$bt = BlockType::getByHandle('pronews_list');
			$uh = Loader::helper('concrete/urls');
			$rssUrl = $controller->getRssUrl($b);
			?>
			
			<div class="rssIcon" style="margin-top:10px;">
				<?php  echo t('Subscribe')?> &nbsp;<a href="<?php   echo $rssUrl?>" target="_blank"><img src="<?php echo BASE_URL.DIR_REL ?>/blocks/pronews_list/rss.png" alt="codestrat concrete5 addon development" title="CodeStrat Concrete5 Addon Development" width="14" height="14" /></a>
			</div>
			<link href="<?php  echo $rssUrl?>" rel="alternate" type="application/rss+xml" title="<?php  echo $controller->rssTitle?>" />
		
                           
	                             
	                             
<?php } $m++; } }  } 

if ($paginate && $num > 0 && is_object($pl)) {
		$pl->displayPaging();
	} ?>
	
	
