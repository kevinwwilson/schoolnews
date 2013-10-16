<?php  
	defined('C5_EXECUTE') or die(_("Access Denied."));
	$textHelper = Loader::helper("text"); 
	// now that we're in the specialized content file for this block type, 
	// we'll include this block type's class, and pass the block to it, and get
	// the content
	
	if (count($cArray) > 0) { ?>
	<div class="ccm-page-list">
	
	<?php  
	for ($i = 0; $i < count($cArray); $i++ ) {
		$cobj = $cArray[$i]; 
		$title = $cobj->getCollectionName(); 
		$newsDate = $cobj->getCollectionDatePublic(DATE_APP_GENERIC_MDY_FULL);
		$imgHelper = Loader::helper('image'); 
		$imageF = $cobj->getAttribute('thumbnail');
		if (isset($imageF)) { 
    		$image = $imgHelper->getThumbnail($imageF, 150,90)->src; 
		} 
	?>
		
<div class="news_headline">
	<h2 class="ccm-page-list-title"><a href="<?php echo $nh->getLinkToCollection($cobj)?>"><?php echo $title?></a></h2>
	<h4><?php  echo $newsDate ; ?></h4>
		<?php 
		if(!empty($image)){
			echo '<img src="'.$image.'" style="float:left; padding: 0px 10px 10px 0px;" alt="news_image"/>';
		 }
		?>
	<div class="ccm-page-list-description">
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
	</div>
</div>
<br style="clear: both;"/>
<?php  
	unset($image);
	} 
	if(!$previewMode && $controller->rss) { 
			$btID = $b->getBlockTypeID();
			$bt = BlockType::getByHandle('pronews_list');
			$uh = Loader::helper('concrete/urls');
			$rssUrl = $controller->getRssUrl($b);
			?>
			<div class="backlink" style="float: right; font-size: 9px;">
				<?php echo t('built with ')?><a href="http://codestrat.com" alt="CodeStrat concrete5 addon addons packages blocks" title="CodeStrat.com Concrete5 Addons">CodeStrat.com</a><?php echo t(' products.')?>
			</div>
			<div class="rssIcon">
				<?php  echo t('Get this feed')?> &nbsp;<a href="<?php   echo $rssUrl?>" target="_blank"><img src="http://codestrat.com/images/rss.png" alt="codestrat concrete5 addon development" title="CodeStrat Concrete5 Addon Development" width="14" height="14" /></a>
			</div>
			<link href="<?php  echo $rssUrl?>" rel="alternate" type="application/rss+xml" title="<?php  echo $controller->rssTitle?>" />
		<?php  
	}
	?>
</div>
<?php  
} 
	if ($paginate && $num > 0 && is_object($pl)) {
		$pl->displayPaging();
	}
	
?>