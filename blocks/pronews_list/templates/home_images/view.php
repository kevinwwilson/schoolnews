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
        $CatImage = $cobj->getAttribute('thumbnail');
		$feature = $cobj->getAttribute('regional_feature');			
		$dateline = $cobj->getAttribute('dateline');					
			        $image = '';
			        if(is_object($CatImage)){
				    $image = '<img alt="" src="'.$CatImage->getRelativePath().'" height="117" width="167">';					
			        }
					?>
					<?php echo $image; ?>
	

<?php   } } 
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
?>

