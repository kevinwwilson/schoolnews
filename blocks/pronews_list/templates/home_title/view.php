<?php  
	defined('C5_EXECUTE') or die(_("Access Denied."));
	$textHelper = Loader::helper("text"); 
	// now that we're in the specialized content file for this block type, 
	// we'll include this block type's class, and pass the block to it, and get
	// the content
	
	if (count($cArray) > 0) { ?>
	<?php  
	for ($i = 0; $i < count($cArray); $i++ ) {
		$cobj = $cArray[$i]; 
		$title = $cobj->getCollectionName();
		$author = $cobj->getAttribute('author');
		$dateline = $cobj->getAttribute('dateline');
		$feature = $cobj->getAttribute('regional_feature');?>		
	<li>
	<a href="<?php  echo $nh->getLinkToCollection($cobj)?>"><?php  echo $title?></a>    
	</li>
<?php  } }?>
<?php 
if(!$previewMode && $controller->rss) { 
			$bt = BlockType::getByHandle('pronews_list');
			$uh = Loader::helper('concrete/urls');
			$rssUrl = $controller->getRssUrl($b);
			?>
			<div class="backlink" style="float: right; font-size: 9px;">
				<?php echo t('built with ')?><a href="http://codestrat.com" alt="CodeStrat concrete5 addon addons packages blocks" title="CodeStrat.com Concrete5 Addons">CodeStrat.com</a><?php echo t(' products.')?>
			</div>
			<div class="rssIcon">
				<?php  echo t('Get this feed')?> &nbsp;<a href="<?php   echo $rssUrl?>" target="_blank"><img src="<?php echo BASE_URL.DIR_REL ?>/blocks/pronews_list/rss.png" alt="codestrat concrete5 addon development" title="CodeStrat Concrete5 Addon Development" width="14" height="14" /></a>
			</div>
			<link href="<?php  echo $rssUrl?>" rel="alternate" type="application/rss+xml" title="<?php  echo $controller->rssTitle?>" />
		<?php  
	} 
	
	if ($paginate && $num > 0 && is_object($pl)) {
		$pl->displayPaging();
	}
	
?>