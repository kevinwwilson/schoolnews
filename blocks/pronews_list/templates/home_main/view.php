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
		$photo_caption = $cobj->getAttribute('photo_caption');
		$secondary_headline = $cobj->getAttribute('secondary_headline');
		$dateline = $cobj->getAttribute('dateline');
		
		
		$CatImage = $cobj->getAttribute('main_photo');					
			        $image = '';
			        if(is_object($CatImage)){
				    $image = '<img alt="" src="'.$CatImage->getRelativePath().'" height="331">';					
			        }  ?> 
		
    
    <div class="slide-block-noborder gallery-js-ready autorotation-active">
        <ul class="slideshow" style="height: 331px;">
            <li class="active" style="display: block;">
                <?php echo $image; ?>
                <strong class="caption" style="display: none;"><?php echo $photo_caption ?></strong>
            </li>
        </ul>
    </div>
    <article class="post">
        <h1><?php  echo $title?></h1>

    <h2><?php echo $secondary_headline ?></h2>
        

        
        <strong class="date">by <?php echo $author ?></strong>        
        <p>
        <span class="dateline"><?php echo $dateline?> — </span>
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
         <a href="<?php  echo $nh->getLinkToCollection($cobj)?>">READ FULL STORY »</a>
    </article>
    
<?php  }
if(!$previewMode && $controller->rss) { 
			$bt = BlockType::getByHandle('pronews_list');
			$uh = Loader::helper('concrete/urls');
			$rssUrl = $controller->getRssUrl($b);
			?>
			
			<div class="rssIcon" style="margin-top:10px">            
				<?php  echo t('Subscribe ')?> &nbsp;<a href="<?php   echo $rssUrl?>" target="_blank"><img src="<?php echo BASE_URL.DIR_REL ?>/blocks/pronews_list/rss.png" alt="codestrat concrete5 addon development" title="CodeStrat Concrete5 Addon Development" width="14" height="14" /></a>
			</div>
			<link href="<?php  echo $rssUrl?>" rel="alternate" type="application/rss+xml" title="<?php  echo $controller->rssTitle?>" />
		<?php  
	} 

 ?>
	

<?php  } 
	
	if ($paginate && $num > 0 && is_object($pl)) {
		$pl->displayPaging();
	}
	
?>